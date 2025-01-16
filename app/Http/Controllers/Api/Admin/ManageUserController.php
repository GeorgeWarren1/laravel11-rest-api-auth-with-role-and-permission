<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

class ManageUserController extends Controller
{
    protected $pathImage = 'public/user';
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResource
    {
        $data = getAllPaginate(
            new User,
            'name',
            10
        );
        return UserResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $image = uploadImage($request, $this->pathImage, 'image');

        $validated['image'] = $image;

        $role = $validated['role'];

        unset($validated['role']);

        $user = User::create($validated);

        $user->assignRole($role);

        return response()->json(['message' => 'Success Add User']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $image = uploadImage($request, $this->pathImage, 'image', $user->image);

        $validated['image'] = $image;

        $role = $validated['role'];

        unset($validated['role']);

        $user->update($validated);

        $user->syncRoles($role);

        return response()->json(['message' => 'Success Update User']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
{
    // Check if the user has an image and delete it
    if ($user->image) {
        Storage::delete($this->pathImage . '/' . $user->image);
    }

    // Delete the user and return a success response if deleted
    if ($user->delete()) {
        return response()->json([
            'message' => 'User Deleted',
        ], 200);
    }

    // Return a failure response if the delete operation fails
    return response()->json([
        'message' => 'User could not be deleted',
    ], 500);
}

}
