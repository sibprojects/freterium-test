<?php

namespace App\Service;

use App\Service\Cache\FileCache;

class ResourceGenerator
{
    public function getResource($id)
    {
        $cache = new FileCache('/var/www/cache/', 10);
        $cache->cleanOldCache();

        if(!$result = $cache->getCache($id)){
            sleep(5);
            $result = $id;

            $cache->save($id, $result);
        }

        return $result;
    }
}