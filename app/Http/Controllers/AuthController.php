<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController
{

    /**
     * Register Users as a manager and create token
     *
     * @param Request $request
     * @param AuthService $service
     * @return JsonResponse
     */
    public function registration(Request $request, AuthService $service): JsonResponse
    {
        try {
            return response()->json($service->registration($request->all()));
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to register"
            ], 400);
        }
    }

    /**
     * Login Users and create token
     *
     * @param Request $request
     * @param AuthService $service
     * @return JsonResponse
     */
    public function login(Request $request, AuthService $service): JsonResponse
    {
        if ($request->isMethod('get')) {
            abort(404);
        }
        try {
            return response()->json($service->auth($request->only(['email', 'password'])));
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to login"
            ], 400);
        }
    }

    /**
     * Logout users
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            return response()->json([
                'status' => boolval(auth()->user()->tokens()->delete()),
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to log out"
            ], 400);
        }

    }
}
