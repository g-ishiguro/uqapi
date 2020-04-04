<?php

namespace App\Service\DatabaseAccess;

use Illuminate\Support\Facades\DB;

class holidayMst {

    /**
     * 年をキーに祝日をその年の祝日Idを取得します。
     */
    public static function holidayMstWhereYear($year) {
        $holidayids = array();
        $records = DB::table('holiday_mst')->select('holiday_id')
                                            ->whereBetween('holiday_date', [$year. '-01-01', $year. '-12-31'])
                                            ->where('delete_flg', 0)
                                            ->get();
        foreach($records as $record) {
            $holidayids[] = $record->holiday_id;
        }
        return $holidayids;
    }

    /**
     * 祝日Idをキーに削除フラグを立てます。
     */
    public static function holidayMstDeleteWhereYear ($holidayids) {
        DB::table('holiday_mst')->select('holiday_id')
                                ->whereIn('holiday_id', $holidayids)
                                ->update(['delete_flg' => 1]);
    }

    /**
     * 祝日Apiから取得した祝日を祝日マスタに設定します。
     */
    public static function holidayMstInsertHolidays ($holidays) {
        foreach($holidays as $holiday) {
            DB::table('holiday_mst')->insert(['holiday_name' => $holiday->name,'holiday_date' => $holiday->date]);
        }
    }

}