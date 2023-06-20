<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:41
 */

namespace Cianyi\Tp6flowrestrrictions;


use think\facade\Cache;

/**
 * 滑动窗口
 * Class SlideTimeWindow
 * @package FlowRestrictions
 */
class SlideTimeWindow extends AbstractStrategy
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
     * 缓存关键词
     * @var string
     */
    protected $cacheKey = 'SlideTimeWindowCacheKey';

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
     * 具体算法
     * @return bool
     */
    public function algorithm()
    {
        //当前时间戳
        $nowTime = time();
        $redis   = Cache::store('redis')->handler();
        //使用管道提升性能
        $pipe = $redis->multi();
        //value 和 score 都是用时间戳，应为相同的元素会覆盖
        $pipe->zadd($this->cachePrefix . $this->cacheKey, $nowTime, $nowTime);
        //移除时间窗口之前的行为，剩下的都是时间窗口内的
        $pipe->zremrangebyscore($this->cachePrefix . $this->cacheKey, 0, $nowTime - $this->limitTime);
        //获取窗口内的行为数量
        $pipe->zcard($this->cachePrefix . $this->cacheKey);
        //设置过期时间
        $pipe->expire($this->cachePrefix . $this->cacheKey, $this->limitTime + 1);
        //执行管道命令
        $replies = $pipe->exec();
        return $replies['2'] <= $this->maxCount ? true : false;
    }
}