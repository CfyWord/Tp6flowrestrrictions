<?php
require __DIR__ . '/../../vendor/autoload.php';

use \Cianyi\Tp6flowrestrrictions\Context;

//限流标识
$key = "api";
//限流策略目前支持四种策略(SpeedCounter,LeakyBucket,SlideTimeWindow,TokenBucket)
$strategy = "SpeedCounter";
$Content =  Context::make($strategy, $key);
$result = $Content->setLimitTime(60)
    ->setMaxCount(60)
    ->algorithm();
if (!$result){
    #接口请求数达到最大限制
}