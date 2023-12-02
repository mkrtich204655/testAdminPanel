<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CategoryController
{

    /**
     * Get Categories
     *
     * @param CategoryService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(CategoryService $service) : JsonResponse{
        try {
            return response()->json($service->getCategories());
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to get categories"
            ], 400);
        }
    }
}
