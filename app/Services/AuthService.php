<?php

namespace App\Services;

use App\Enums\Roles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthService
{

    public function __construct(
        private UserService $userService
    )
    {
    }

    /**
     * Create new user and token
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function registration(array $data): array
    {
        $this->registrationValidate($data);

        $user = $this->userService->create($data, Roles::MANAGER->value);

        $token = $user->createToken($user->email, ['*'], now()->addMonth())->plainTextToken;

        return [
            'status' => true,
            'token' => $token,
        ];
    }

    /**
     * Find user by email and create token
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function auth(array $data): array
    {
        $this->loginValidate($data);

        $user = $this->userService->getUserByFields(['email' => $data['email']]);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return [
                'status' => false,
                'message' => 'invalid credentials'
            ];
        }

        $token = $user->createToken($user->email, ['*'], now()->addMonth())->plainTextToken;

        return [
            'status' => true,
            'token' => $token,
        ];
    }

    /**
     * Validate new users data
     *
     * @param $data
     * @return array
     * @throws ValidationException
     */
    private function registrationValidate($data): array
    {
        $validator = Validator::make($data, [
            'name' => ['string', 'max:20'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);

        }

        return $validator->validated();
    }

    /**
     * Validate existing users data
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    private function loginValidate(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
