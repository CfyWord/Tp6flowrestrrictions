<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/19
 * Time: 17:55
 */

include_once "./TokenBucket.php";
//限流标识
$TokenBucketKey = "api_";
//并发数量
$burst = 60;
//速率
$rate = 1;
//实例化
$TokenBucket = new \Cianyi\Tp6tokenbucket\TokenBucket( $burst, $rate,$TokenBucketKey);
//执行限流
$result = $TokenBucket->algorithm();
if($result){
    #在应许内
}else{
    #超出限制
}