ThinkPHP 6.0 限流插件
===============

> 运行环境要求PHP7.2+


## 主要新特性


## 安装

~~~
composer require cianyi/tp6flowrestrrictions=1.0.1
~~~


## 使用方法
说明：支持四种策略SpeedCounter(计数限流),SlideTimeWindow(滑动窗口),LeakyBucket(漏桶),TokenBucket(令牌桶)

--------------
* 计数限流
~~~
<?php
namespace app\controller;

use \Cianyi\Tp6flowrestrrictions\Context;

class Index 
{
    public function index()
    {
        
        //限流标识
        $key = "api";
        //限流策略
        $strategy = "SpeedCounter";
        $Content = new Context($strategy, $key);
        //每分钟只能发起60次
        $result = $Content
            //间隔设置时间
            ->setLimitTime(60)
            //最大请求数
            ->setMaxCount(60)
            ->algorithm();
        if (!$result){
            #接口请求数达到最大限制
        }
    }
}
~~~

* 滑动窗口
~~~
<?php
namespace app\controller;

use \Cianyi\Tp6flowrestrrictions\Context;

class Index 
{
    public function index()
    {
        
        //限流标识
        $key = "api";
        //限流策略
        $strategy = "SlideTimeWindow";
        $Content = new Context($strategy, $key);
        //每隔分钟时间范围内只能请求60次
        $result = $Content
            //间隔设置时间
            ->setLimitTime(60)
             //最大请求数
            ->setMaxCount(60)
            ->algorithm();
        if (!$result){
            #接口请求数达到最大限制
        }
    }
}
~~~

* 漏桶
~~~
<?php
namespace app\controller;

use \Cianyi\Tp6flowrestrrictions\Context;

class Index 
{
    public function index()
    {
        
        //限流标识
        $key = "api";
        //限流策略
        $strategy = "LeakyBucket";
        $Content = new Context($strategy, $key);
        //每分钟不得超过60个并发数
        $result = $Content
            //漏桶容量(最大并发请求数)
            ->setBurst(60)
             //每秒放行请求数
            ->setRate(1)
            ->algorithm();
        if (!$result){
            #接口请求数达到最大限制
        }
    }
}
~~~

* 令牌桶
~~~
<?php
namespace app\controller;

use \Cianyi\Tp6flowrestrrictions\Context;

class Index 
{
    public function index()
    {
        
        //限流标识
        $key = "api";
        //限流策略
        $strategy = "LeakyBucket";
        $Content = new Context($strategy, $key);
        //每隔分钟时间范围内只能请求60次
        $result = $Content
            //令牌桶容量(可以存放多少空闲请求)
            ->setBurst(60)
            //每秒增加空闲请求数
            ->setRate(1)
            ->algorithm();
        if (!$result){
            #接口请求数达到最大限制
        }
    }
}
~~~

## 版权信息



请查阅 [LICENSE.txt](LICENSE.txt)
