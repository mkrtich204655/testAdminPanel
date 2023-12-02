<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PostController
{
    /**
     * Create new Post
     *
     * @param Request $request
     * @param PostService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPost(Request $request, PostService $service): JsonResponse{
        try {
            return response()->json($service->createNewPost($request->all()));
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to create post"
            ], 400);
        }
    }

    /**
     * If the user`s role is a manager, all posts created by his employees will be displayed
     * If the user`s role is an employee, all posts created by him will be displayed
     *
     * @param PostService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts(PostService $service): JsonResponse{
        try {
            return response()->json($service->getPosts());
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to get posts"
            ], 400);
        }
    }
}
