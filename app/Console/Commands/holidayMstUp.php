<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HolidayApiController;
use Illuminate\Http\Request;
use App\Service\DatabaseAccess\holidayMst;
use Illuminate\Support\Facades\DB;


class holidayMstUp extends Command
{
    /**
     * The name and signature of the console command.
     * 引数なしの場合でもエアラーハンドリングできるように引数なしも許可する 
     *
     * @var string
     */
    protected $signature = 'batch:holidayMstUp {year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update c.target year is required';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $targetYear = $this->argument('year');
        // 引数の年が+-3年以内かチェックします
        if ($targetYear <= date('Y', strtotime('+3 year', time())) &&
            $targetYear >= date('Y', strtotime('-3 year', time()))) {
        } else {
            Log::channel('holidayMstUp')->warning(config('consts.errorCds.TARGET_OUT_OF_RANGE'));
            exit;
        }

        // apiを使用し、祝日を取得
        $json = app()->call('App\Http\Controllers\HolidayApiController@getHolidays', ['request' => new Request(['year' => $targetYear])]);
        $holidays = json_decode($json);
        // エラーコード存在確認
        if (array_key_exists('errorCd', $holidays)) {
            Log::channel('holidayMstUp')->warning(print_r($holidays, true));
            exit;
        }

        $holidayids = holidayMst::holidayMstWhereYear($targetYear);
        // 指定した年の祝日が存在する場合は、削除フラグを立てる
        if ($holidayids) {
            holidayMst::holidayMstDeleteWhereYear($holidayids);
            Log::channel('holidayMstUp')->info('update holiday_iddelete_fld = 1 for holidayids: ' . print_r($holidayids, true));
        }
        try {
            DB::beginTransaction();
            holidayMst::holidayMstInsertHolidays($holidays);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('holidayMstUp')->warning('An error occurred when inserting a holiday_mst. errorMsg: ' . $e->getMessage());
            exit;
        }
       
    }
}
