<?php

namespace Tweelo\Service;

use Moust\Silex\Cache\CacheInterface;
use Tweelo\Exception\TweeloException;

/**
 * Class CachingProxy
 * @package Tweelo\Service
 */
class CachingProxy
{
    /** @var CacheInterface|null */
    private $cache = null;
    private $instance = null;
    /** @var  integer */
    private $cachingLifeTime;

    /**
     * CachingProxy constructor.
     * @param CacheInterface $cache
     * @param $instance
     * @param $cachingLifeTime
     */
    public function __construct(CacheInterface $cache, $instance, $cachingLifeTime)
    {
        $this->cache = $cache;
        $this->instance = $instance;
        $this->cachingLifeTime = $cachingLifeTime;
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws TweeloException
     */
    public function __call($method, $arguments)
    {
        try {
            if (substr($method, 0, 3) !== 'get') {
                $result = call_user_func_array([$this->instance, $method], $arguments);
            } else {
                $uniqueId = $method . md5(serialize($arguments));
                $result = $this->cache->fetch($uniqueId);
                if ($result === false) {
                    $result = call_user_func_array([$this->instance, $method], $arguments);
                    $this->cache->store($uniqueId, $result, $this->cachingLifeTime);
                }
            }
        } catch (TweeloException $e) {
            throw new TweeloException($e->getMessage());
        }
        die();
        return $result;
    }
}