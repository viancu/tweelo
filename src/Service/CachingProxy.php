<?php

namespace Tweelo\Service;

use Moust\Silex\Cache\CacheInterface;

class CachingProxy
{
    /** @var CacheInterface|null */
    private $cache = null;
    private $instance = null;
    /** @var  integer */
    private $cachingLifeTime;

    public function __construct(CacheInterface $cache, $instance, $cachingLifeTime)
    {
        $this->cache = $cache;
        $this->instance = $instance;
        $this->cachingLifeTime = $cachingLifeTime;
    }

    public function __call($method, $arguments)
    {
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

        return $result;
    }
}