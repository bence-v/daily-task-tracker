<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Models\User;
use App\Servicies\CategoryCacheService;

class CreateCategory
{

    public function execute(array $categoryData, User $user): Category
    {
        $category = $user->categories()->create($categoryData);

        return $category;
    }
}
