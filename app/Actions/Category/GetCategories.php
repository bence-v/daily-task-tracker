<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Servicies\CategoryCacheService;
use Illuminate\Support\Facades\Cache;

class GetCategories
{

    public function __construct(private readonly CategoryCacheService $categoryCacheService)
    {
    }


    public function execute(int $userId): array
    {
        return $this->categoryCacheService->remember('categories.user.'.$userId, 3600, fn() => Category::query()
            ->where('user_id', $userId)
            ->findorderBy('name')
            ->pluck('name','uuid')
            ->toArry());
    }
}
