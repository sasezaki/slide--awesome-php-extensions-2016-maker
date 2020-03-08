<?php
declare(strict_types=1);

namespace GethnaProjectAnalyze\Storage;

use Zend\Cache\Storage\StorageInterface as ZendCacheStorage;

class CacheStorage implements StorageInterface
{
    private $storage;

    public function __construct(ZendCacheStorage $zendCacheStorage)
    {
        $this->storage = $zendCacheStorage;
    }

    public function has(string $key) : bool
    {
        return $this->storage->hasItem($key);
    }

    public function get(string $key) : string
    {
        return $this->storage->getItem($key);
    }

    public function save(string $key, string $value) : bool
    {
        return $this->storage->setItem($key, $value);
    }
}