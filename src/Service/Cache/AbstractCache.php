<?php

namespace App\Service\Cache;

abstract class AbstractCache
{
    public $cacheDir;
    public $maxCount;

    public function __construct($cacheDir, $maxCount)
    {
        $this->cacheDir = $cacheDir;
        $this->maxCount = $maxCount;
    }

    public function hash($cache_id)
    {
        return md5($cache_id);
    }

    abstract function getFilename($cache_id);

    abstract function load($cache_id);

    abstract function save($cache_id, $data);

    abstract function cleanOldCache();

    abstract function getCache($cache_id);

    abstract function clear();

    abstract function getCacheResourceCount();
}