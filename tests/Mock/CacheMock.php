<?php

namespace Tests\Mock;

use Psr\SimpleCache\CacheInterface;

class CacheMock implements CacheInterface
{
    public function get($key, $default = null)
    {
        return $default;
    }

    public function set($key, $value, $ttl = null)
    {
        return $value;
    }

    public function delete($key)
    {
        return;
    }

    public function clear()
    {
        return;
    }

    public function getMultiple($keys, $default = null)
    {
        return $default;
    }

    public function setMultiple($values, $ttl = null)
    {
        return;
    }

    public function deleteMultiple($keys)
    {
        return;
    }

    public function has($key)
    {
        return true;
    }
}
