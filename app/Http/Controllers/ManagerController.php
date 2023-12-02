<?php

namespace App\Http\Controllers;

use App\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ManagerController
{

    /**
     * Create new user as an Employee
     *
     * @param Request $request
     * @param EmployeeService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function createEmployee(Request $request, EmployeeService $service): JsonResponse{
        try {
            return response()->json($service->createNewEmployee($request->all()));
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to create employee"
            ], 400);
        }
    }

    /**
     * Get Manager Employees
     *
     * @param EmployeeService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmployees(EmployeeService $service) : JsonResponse{
        try {
            return response()->json($service->getEmployee());
        } catch (\Exception $e) {
            Log::error(__METHOD__ . "->" . $e->getMessage());
            return response()->json([
                'status' => false,
                'error' => "failed to get employees"
            ], 400);
        }
    }
}
