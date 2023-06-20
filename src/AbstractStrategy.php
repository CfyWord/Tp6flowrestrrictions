<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:29
 */

namespace Cianyi\Tp6flowrestrrictions;

/**
 * Class AbstractStrategy
 * @package Cianyi\Tp6flowrestrrictions
 *@method LeakyBucket|TokenBucket setBurst(int $burst)
 * @method LeakyBucket|TokenBucket setRate(int $rate)
 * @method SlideTimeWindow|SpeedCounter setLimitTime(int $rate)
 * @method SlideTimeWindow|SpeedCounter setMaxCount(int $rate)
 */
abstract class AbstractStrategy
{
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
    public abstract function algorithm();
}