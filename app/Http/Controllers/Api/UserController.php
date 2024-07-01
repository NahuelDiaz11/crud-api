<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\User\UserService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $_userService)
    {
        $this->userService = $_userService;
    }

    /**
     *
     * Display a listing of the users.
     *
     */
    public function index()
    {
        $users = $this->userService
            ->list([
                'paginate' => false,
                'rows' => 20
            ]);

        return response()->json(
            $users,
            200
        );
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(UserRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = bcrypt($validatedData['password']);

        try {
            $this->userService->save($validatedData);

            return response()->json(['message' => 'User created successfully'], 201);
        } catch (BadRequestException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->userService
                ->delete($id);

            return response()->json(['message' => 'User deleted successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserRequest $request, int $id)
    {
        try {
            $validatedData = $request->validated();

            $this->userService->update($id, $validatedData);

            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred'], 500);
        }
    }
}
