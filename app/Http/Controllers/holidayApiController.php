<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yasumi\Yasumi;
use Illuminate\Support\Facades\Log;

class holidayApiController extends Controller
{
    public function index(Request $request) {
        Log::channel('holidayApi')->info('start holidayApi');
        if ($request->input('year')) {
            // 引数の年が現在の+-10年以内かチェックします。 strtodateだとバグる・・・
            $year = $request->input('year');
            if (date('Y') + 10 < $year || date('Y') - 10 > $year) {
                Log::channel('holidayApi')->warning(config('consts.errorCds.TARGET_OUT_OF_RANGE'));
                return json_encode(array('errorcd' => (config('consts.errorCds.TARGET_OUT_OF_RANGE'))));
            }
        } else {
            // 指定がない場合は今年の祝日を取得します。
            $year =  date('Y');
        }
        Log::channel('holidayApi')->info('traget year: ' . $year);
        $resArr = array();
        try {
            $holidays = Yasumi::create('Japan', $year, 'ja_JP');
        } catch (\Exception $e) {
            Log::channel('holidayApi')->warning('Yasumi library error!! traget year: ' . $year . '. errorCd: ' . $e->getCode() . '.' . 'errorMessage: '. $e->getMessage() . '.' . 'trace: ' . $e->getTrace());
        }

        if (is_null($holidays) || empty($holidays)) {
            Log::channel('holidayApi')->warning('Yasumi result is null or empty!!');
            return json_encode(array('errorcd' => (config('consts.errorCds.RESPONSE_NULL'))));
        }

        $count = 0;
        foreach($holidays as $key => $holiday) {
            $resArr[$count]['date'] = $holiday->format('Y-m-d');
            $resArr[$count]['name'] = $holiday->getName();
            $count++;
        }
        $jsonArr = json_encode($resArr, JSON_UNESCAPED_UNICODE);
        Log::channel('holidayApi')->info('end holidayApi');
        return $jsonArr;
    }
}
