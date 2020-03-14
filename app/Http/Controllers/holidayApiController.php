<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yasumi\Yasumi;

class holidayApiController extends Controller
{
    public function index(Request $request) {
        if ($request->input('year')) {
            $year = $request->input('year');
        } else {
            // 指定がない場合は今年の祝日を取得します。
            $year =  date('Y');
        }

        $arr = array();
        $holidays = Yasumi::create('Japan', $year, 'ja_JP');
        $count = 0;
        foreach($holidays as $key => $holiday) {
            $arr[$count]['date'] = $holiday->format('Y-m-d');
            $arr[$count]['name'] = $holiday->getName();

            $count++;
        }
        return $arr;
    }
}
