<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:31
 */

namespace Cianyi\Tp6flowrestrrictions;


/**
 * Class Context
 * @method LeakyBucket|TokenBucket setBurst(int $burst)
 * @method LeakyBucket|TokenBucket setRate(int $rate)
 * @method SlideTimeWindow|SpeedCounter setLimitTime(int $rate)
 * @method SlideTimeWindow|SpeedCounter setMaxCount(int $rate)
 * @package Cianyi\Tp6flowrestrrictions
 */
class Context
{
    /**
     * @var AbstractStrategy|null
     */
    private $strategy;

    /**
     * Context constructor.
     * @param string $strategy
     * @param string|null $cachePrefix
     * @throws FlowRestrictionException
     */
    public function __construct(string $strategy, $cachePrefix = null)
    {
        //获取已有的策略
        $data = include_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";
        if (!$data || !isset($data[$strategy])) {
            throw new FlowRestrictionException("strategy not undefined");
        }
        $this->strategy = new $data[$strategy]($cachePrefix);
    }

    /**
     * @return bool
     */
    public function algorithm()
    {
        return $this->strategy->algorithm();
    }
}