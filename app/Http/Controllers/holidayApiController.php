<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yasumi\Yasumi;
use Illuminate\Support\Facades\Log;

class holidayApiController extends Controller
{
    public function getHolidays(Request $request) {
        Log::channel('holidayApi')->info('start holidayApi');
        if ($request->input('year')) {
            $year = $request->input('year');
        } else {
            // 指定がない場合は今年の祝日を取得します。
            $year =  date('Y');
        }
        Log::channel('holidayApi')->info('traget year: ' . $year);
        $resArr = array();
        try {
            $holidays = Yasumi::create('Japan', $year, 'ja_JP');
        } catch (\Exception $e) {
            Log::channel('holidayApi')->warning('Yasumi library error!! traget year: ' . $year . '. errorCd: ' . $e->getCode() . '.' . 'errorMessage: '. $e->getMessage());
            return json_encode(array('errorCd' => $e->getCode(), 'mesagge' => $e->getMessage()));
        }

        if (is_null($holidays) || empty($holidays)) {
            Log::channel('holidayApi')->warning(config('consts.errorCds.RESPONSE_NULL.message'));
            return json_encode(config('consts.errorCds.RESPONSE_NULL'));
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
