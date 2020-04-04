<?php

return [
    // レスポンスがnull or empty
    'RESPONSE_NULL' => [
        'errorCd' => 56,
        'message' => 'Yasumi result is null or empty!!'
    ],
    // 指定の年がプラス、マイナス３年いないではない
    'TARGET_OUT_OF_RANGE' => [
        'errorCd' => 8151,
        'message' => 'Target out of range!!'
    ],
    // バッチの引数が足りません。
    'BATCH_ARG_IS_NULL' => [
        'errorCd' => 360,
        'message' => 'Please enter an argument!!'
    ],
];
