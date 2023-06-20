<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:42
 */

namespace Cianyi\Tp6flowrestrrictions;

use think\facade\Cache;

/**
 * 计数限流模式
 * Class SpeedCounter
 * @package FlowRestrictions
 */
class SpeedCounter extends AbstractStrategy
{
    /**
     * @var int 时间间隔
     */
    protected $limitTime = 60;
    /**
     * @var int 最大请求数
     */
    protected $maxCount = 10;
    /**
     * @param int $limitTime
     * @return $this
     */
    public function setLimitTime(int $limitTime){
        $this->limitTime = $limitTime;
        return $this;
    }

    /**
     * @param int $maxCount
     * @return $this
     */
    public function setMaxCount(int $maxCount){
        $this->maxCount = $maxCount;
        return $this;
    }
    /**
     * 缓存关键词
     * @var string
     */
    protected $cacheKey = 'SpeedCounterCacheKey';

    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function algorithm()
    {
        $last_count = Cache::store('redis')->get($this->cachePrefix . $this->cacheKey, 0);
        $last_count++;
        if ($last_count > $this->maxCount) {
            return false;
        }
        Cache::store('redis')->set($this->cachePrefix . $this->cacheKey, $last_count, $this->limitTime);
        return true;
    }
}