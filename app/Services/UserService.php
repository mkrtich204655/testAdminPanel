<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function __construct(
        private User $model
    )
    {
    }

    /**
     * Create new user and assign role
     *
     * @param array $data
     * @param string $role
     * @return User
     */
    public function create(array $data, string $role): User
    {
        $user = $this->model->create([
            'manager_id' => auth()->id() ?? null,
            ...$data
        ]);

        $user->assignRole($role);
        return $user;
    }

    /**
     * Find or get Users by fields with relations
     *
     * @param array $fields
     * @param array $relations
     * @param bool|null $get
     * @return User|Collection|null
     */
    public function getUserByFields(array $fields, array $relations = [], bool|null $get = false): User|null|Collection
    {
        $query = $this->model->with($relations);

        foreach ($fields as $field => $value) {
            $query = $query->where($field, $value);
        }

        $query = $get ? $query->get() : $query->first();

        return $query;
    }
}
