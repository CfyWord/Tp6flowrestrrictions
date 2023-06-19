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
//实例化
$TokenBucket = new \Cianyi\Tp6tokenbucket\TokenBucket($TokenBucketKey);
//执行限流
$result = $TokenBucket->algorithm();

```