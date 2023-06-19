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
//实例化
$TokenBucket = new \Cianyi\Tp6tokenbucket\TokenBucket($TokenBucketKey);
//执行限流
$result = $TokenBucket->algorithm();
