<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__ . './src/LianLianPay.php';

$app->account->phoneVerifyCodeApply(123);