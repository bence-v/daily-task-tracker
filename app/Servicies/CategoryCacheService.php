<?php

namespace App\Servicies;

use Illuminate\Cache\CacheManager;

class CategoryCacheService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private CacheManager $cache)
    {
        //
    }

    /**
     * @param int $userId
     * @param \Closure(): array $callback
     * @return array
     */
    public function remember(int $userId, \Closure $callback): array
    {
        return $this->cache->remember($this->getKey($userId), 3600, $callback);
    }

    public function getKey(int $userId)
    {
        return 'categories.user' . $userId;
    }

    public function clear(int $id): int
    {
        return $this->cache->forget($this->getKey($id));
    }
}
