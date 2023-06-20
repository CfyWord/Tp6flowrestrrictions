<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:40
 */

namespace Cianyi\Tp6flowrestrrictions;


use think\facade\Cache;

/**
 * 漏桶算法
 * Class LeakyBucket
 * @package FlowRestrictions
 */
class LeakyBucket extends AbstractStrategy
{
    /**
     * 漏斗当前水量 （请求数）
     * @var int
     */
    protected $water;
    /**
     * 漏斗总量（超过直接舍弃）
     * @var int
     */
    protected $burst=60;
    /**
     * 漏斗出水率（限流速度）
     * @var int
     */
    protected $rate=1;
    /**
     * 记录每次请求的时间（因为需要记录每次请求之间的出水量也就是请求数）
     * @var int
     */
    protected $lastTime;

    /**
     * 上次时间缓存关键词
     * @var string
     */
    protected $cacheLastTimeKey = 'LeakyBucketLastCacheKey';
    /**
     * 剩余水量缓存关键词
     * @var string
     */
    protected $cacheWaterKey = 'LeakyBucketWaterCacheKey';

    /**
     * 设置漏斗总量
     * @param int $burst
     * @return $this
     */
    public function setBurst(int $burst)
    {
        $this->burst = $burst;
        return $this;
    }

    /**
     * 设置漏斗出水率
     * @param int $rate
     * @return $this
     */
    public function setRate(int $rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * 具体算法
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function algorithm()
    {
        //当前时间戳
        $nowTime = time();
        //获取上次请求时间
        $this->lastTime = Cache::store('redis')->get($this->cachePrefix . $this->cacheLastTimeKey, $nowTime);
        //获取当前水量（请求数）
        $this->water = Cache::store('redis')->get($this->cachePrefix . $this->cacheWaterKey, 0);
        //计算出水量
        //因为rate是固定的，所以可以认为“时间间隔 * rate”即为漏出的水量
        $s = $nowTime - $this->lastTime;//时间间隔=当前时间-上次访问时间
        //已漏出水量=时间间隔*出水速率(上次到现在)
        $outCount = $s * $this->rate;//漏出的水量（请求数）
        //当前水量 = 当前水量-已漏出水量(如果漏出的水量大于当前水量就等于0)
        $this->water = min(0, $this->water - $outCount);

        //请求数量大于漏斗容量(水量溢出)
        if ($this->water > $this->burst) {
            return false;
        }
        $this->lastTime = $nowTime;//更新最后请求时间
        $this->water++;//更新当前出水量
        //缓存
        Cache::store('redis')->set($this->cachePrefix . $this->cacheLastTimeKey, $this->lastTime);
        Cache::store('redis')->set($this->cachePrefix . $this->cacheWaterKey, $this->water);
        return true;
    }
}