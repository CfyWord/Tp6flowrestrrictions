居于thinkphp6 开发的接口限流组件

使用方法

一.先安装插件
```php
composer require cianyi/tp6tokenbucket
```
二.使用案例
```php
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

```