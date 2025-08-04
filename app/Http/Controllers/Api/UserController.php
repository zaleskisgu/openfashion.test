<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json([
            'message' => 'All users retrieved successfully',
            'data' => UserResource::collection($users)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());
        
        return response()->json([
            'message' => 'User created successfully',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json([
            'message' => "User ID {$id} retrieved successfully",
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        
        return response()->json([
            'message' => 'User updated successfully',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Get all posts for a specific user.
     */
    public function posts(string $id): JsonResponse
    {
        $user = User::with('posts')->findOrFail($id);
        $posts = $user->posts;
        
        $message = $posts->count() > 0 
            ? "Posts for user ID {$id}" 
            : "No posts found for user ID {$id}";
            
        return response()->json([
            'message' => $message,
            'data' => PostResource::collection($posts)
        ]);
    }

    /**
     * Get all comments for a specific user.
     */
    public function comments(string $id): JsonResponse
    {
        $user = User::with('comments')->findOrFail($id);
        $comments = $user->comments;
        
        $message = $comments->count() > 0 
            ? "Comments for user ID {$id}" 
            : "No comments found for user ID {$id}";
            
        return response()->json([
            'message' => $message,
            'data' => CommentResource::collection($comments)
        ]);
    }
}
