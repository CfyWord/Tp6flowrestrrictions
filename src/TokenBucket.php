<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/19
 * Time: 17:53
 */

namespace Cianyi\Tp6tokenbucket;

use think\facade\Cache;

/**
 * 令牌桶
 * Class TokenBucket
 * @package FlowRestrictions
 */
class TokenBucket
{
    /**
     * 当前令牌数量
     * @var int
     */
    protected $tokens;
    /**
     * 漏桶总量
     * @var int
     */
    protected $burst;
    /**
     * 令牌放入速度
     * @var int
     */
    protected $rate;
    /**
     * 记录每次请求的时间
     * @var int
     */
    protected $lastTime;

    /**
     * 上次时间缓存关键词
     * @var string
     */
    protected $cacheLastTimeKey = 'TokenBucketLastCacheKey';
    /**
     * 剩余水量缓存关键词
     * @var string
     */
    protected $cacheTokenKey = 'TokenBucketTokenCacheKey';

    /**
     * @var string|null
     */
    protected $cachePrefix;

    /**
     * AbstractStrategy constructor.
     * @param string|null $cachePrefix 关键词
     */
    public function __construct(string $cachePrefix = null)
    {
        $this->cachePrefix = $cachePrefix;
    }

    /**
     * 具体算法
     * @return bool
     */
    public function algorithm()
    {
        $nowTime = time();
        //获取上次请求时间
        $this->lastTime = Cache::store('redis')->get($this->cachePrefix . $this->cacheLastTimeKey, $nowTime);
        //获取当前令牌总量
        $this->tokens = Cache::store('redis')->get($this->cachePrefix . $this->cacheTokenKey, 0);
        //计算生成的令牌数量
        //因为rate是固定的，所以可以认为“时间间隔 * rate”即为生成的令牌量
        $s = $nowTime - $this->lastTime;//间隔时间=当前时间-上次访问时间
        //计算生成的令牌数
        $addToken = $s * $this->rate; //生成数=间隔时间*放入令牌速率
        //当前令牌数=剩余令牌+现在增加的令牌数（不能超出桶容量）
        $this->tokens   = min($this->burst, $this->tokens + $addToken);
        $this->lastTime = $nowTime;//更新最后请求时间
        //缓存
        Cache::store('redis')->set($this->cachePrefix . $this->cacheLastTimeKey, $this->lastTime);
        Cache::store('redis')->set($this->cachePrefix . $this->cacheTokenKey, $this->tokens);
        return true;
    }
}