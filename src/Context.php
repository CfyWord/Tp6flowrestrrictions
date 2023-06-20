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
 * @method AbstractStrategy  algorithm()
 * @package Cianyi\Tp6flowrestrrictions
 */
class Context
{

    /**
     * @param string $strategy
     * @param null $cachePrefix
     * @return AbstractStrategy
     * @throws FlowRestrictionException
     */
    public static function make(string $strategy, $cachePrefix = null)
    {
        //获取已有的策略
        $data = include_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";
        if (!$data || !isset($data[$strategy])) {
            throw new FlowRestrictionException("strategy not undefined");
        }
        return new $data[$strategy]($cachePrefix);
    }

}