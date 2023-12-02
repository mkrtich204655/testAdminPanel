<?php

namespace App\Services;

use App\Enums\Roles;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PostService
{
    public function __construct(
        private Post $model
    )
    {
    }

    /**
     * get posts for different role users (for manager, for employee)
     *
     * @return array
     */
    public function getPosts(): array
    {
        $user = auth()->user();
        $posts = $this->model->with('category');

        if ($user->hasRole(Roles::MANAGER->value)) {
            $posts->with('employee')
                ->whereHas('employee', function ($q) use ($user) {
                    return $q->where('manager_id', $user->id);
                });
        } else {
            $posts = $posts->where('user_id', $user->id);
        }
        return [
            'status' => true,
            'data' => $posts->get()
        ];
    }

    /**
     * Create a new post
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function createNewPost(array $data): array
    {
        $this->newPostValidate($data);

        $userId = auth()->id();

        $path = $data['image']->store('images/posts/' . $userId, 'public');
        $data['image'] = "storage/{$path}";

        $this->model->create([
            'user_id' => $userId,
            ...$data
        ]);

        return [
            'status' => true,
            'message' => 'A new post created successfully'
        ];

    }

    /**
     * Validate new post data
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    private function newPostValidate(array $data): array
    {
        $validator = Validator::make($data, [
            'image' => ['required', 'file', 'mimes:jpeg,jpg,png,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:20'],
            'category_id' => ['required', 'numeric', 'exists:categories,id'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
