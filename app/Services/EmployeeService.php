<?php

namespace App\Services;

use App\Enums\Roles;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeService
{

    public function __construct(
        private UserService $userService
    )
    {
    }

    /**
     * Get Employees by manager id
     *
     * @return array
     */
    public function getEmployee(): array
    {
        $employees = $this->userService->getUserByFields(
            fields: ['manager_id' => auth()->id()],
            get: true
        );

        return [
            'status' => true,
            'data' => $employees
        ];
    }

    /**
     * Create new user as an Employee and assign role
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function createNewEmployee(array $data): array
    {
        $this->newEmployeeValidate($data);

        $this->userService->create($data, Roles::EMPLOYEE->value);

        return [
            'status' => true,
            'message' => 'A new Employee created successfully'
        ];

    }

    /**
     * Validate new Employee data
     *
     * @param $data
     * @return array
     * @throws ValidationException
     */
    private function newEmployeeValidate($data): array
    {
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

}
