<?php

namespace App\Service\Cache;

use App\Service\Cache\AbstractCache;

class FileCache extends AbstractCache
{
    public function getFilename($cache_id)
    {
        $hash = $this->hash($cache_id);
        return $this->cacheDir . $hash . '.json';
    }

    public function load($cache_id)
    {
        $filename = $this->getFilename($cache_id);
        return file_exists($filename) ?
            json_decode(file_get_contents($filename),true) : false;
    }

    public function save($cache_id, $data)
    {
        $filename = $this->getFilename($cache_id);
        file_put_contents($filename, json_encode($data));

        // apply policy LRU
        $this->cleanOldCache();
    }

    public function cleanOldCache()
    {
        $dir = opendir($this->cacheDir);
        $files = [];
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {

                if ( !is_dir($this->cacheDir . $file) ) {
                    $ftime = filemtime($this->cacheDir.'/'.$file);
                    $files[] = [
                        'filename' => $file,
                        'time' => $ftime,
                    ];
                }
            }
        }
        closedir($dir);
        usort($files, function($a, $b){
            if($a['time'] == $b['time']) return 0;
            return $a['time'] > $b['time'] ? 1 : -1;
        });

        foreach ($files as $key => $file){
            if(count($files)<=10) break;
            unlink($this->cacheDir . $file['filename']);
            unset($files[$key]);
        }
    }

    public function getCache($cache_id)
    {
        return $this->load($cache_id);
    }

    public function clear()
    {
        $dir = opendir($this->cacheDir);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {

                if ( !is_dir($this->cacheDir . $file) ) {
                    unlink($this->cacheDir . $file);
                }
            }
        }
        closedir($dir);
    }

    public function getCacheResourceCount()
    {
        $cnt = 0;
        $dir = opendir($this->cacheDir);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {

                if ( !is_dir($this->cacheDir . $file) ) {
                    $cnt++;
                }
            }
        }
        closedir($dir);
        return $cnt;
    }
}