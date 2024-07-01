<?php

namespace App\Services\User;

use App\Models\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserService
{
    /**
     * Return a result set of users
     */
    public function list(array $filters = [])
    {
        $paginateFilter = array_key_exists('paginate', $filters)
            ? filter_var($filters['paginate'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            : null;

        $rowsFilter = array_key_exists('rows', $filters)
            ? $filters['rows']
            : null;

        $limitFilter = array_key_exists('limit', $filters)
            ? $filters['limit']
            : null;

        $isActiveFilter = array_key_exists('is_active', $filters)
            ? filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            : null;

        try {
            $users = User::latest();
            $userResults = $users->get();

            if ($userResults->isEmpty()) {
                return 'There are no users available.';
            }
            if ($isActiveFilter)
                $users = $users->where('enabled', $isActiveFilter);

            if ($limitFilter)
                $users = $users->take($limitFilter);

            if ($paginateFilter)
                return $users->paginate(
                    $rowsFilter
                        ? $rowsFilter
                        : 100
                );

            return $users->get();
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Error when obtaining user list');
        }
    }

    /**
     * Get a user by id
     */
    public function getById(int $id)
    {
        try {
            return User::findOrFail($id);
        } catch (\Exception $e) {
            throw new BadRequestHttpException('User not found');
        }
    }

    /**
     * Store a new user
     */
    public function save(array $data)
    {
        try {

            if (isset($data['users'])) {
                foreach ($data['users'] as $user) {
                    $data[$user] = true;
                }
            }
            User::create($data);

            return true;
        } catch (\Exception $e) {
            throw new BadRequestException("Error saving new user");
        }
    }

    /**
     * Delete user by id
     */
    public function delete(int $id)
    {
        try {
            $user = $this->getById($id);

            $user->delete();
        } catch (\Exception $e) {
            throw new BadRequestException("User not found");
        }
    }

    /**
     * Update the user information
     */
    public function update(int $id, array $data)
    {
        try {
            $user = $this->getById($id);

            $user->update([
                'first_name' => $data['first_name'] ?? $user->first_name,
                'last_name' => $data['last_name'] ?? $user->last_name,
                'email' => $data['email'] ?? $user->email,
                'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
                'address' => $data['address'] ?? $user->address,
                'phone' => $data['phone'] ?? $user->phone,
                'phone_2' => $data['phone_2'] ?? $user->phone_2,
                'postal_code' => $data['postal_code'] ?? $user->postal_code,
                'birth_date' => $data['birth_date'] ?? $user->birth_date,
                'gender' => $data['gender'] ?? $user->gender,
            ]);

            return $user;
        } catch (\Exception $e) {
            throw new BadRequestException('Error updating user');
        }
    }
}
