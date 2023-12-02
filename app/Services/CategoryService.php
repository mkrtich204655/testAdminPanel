<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{

    public function __construct(
        private Category $category
    )
    {
    }

    /**
     * Get Categories
     *
     * @return array
     */
    public function getCategories(): array
    {
        return [
            'status' => true,
            'data' => $this->category->get()
        ];

    }
}
