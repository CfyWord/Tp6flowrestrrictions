<?php
/**
 * Created by PhpStorm.
 * User: Cianyi
 * Date: 2023/6/20
 * Time: 11:29
 */

namespace Cianyi\Tp6flowrestrrictions;


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