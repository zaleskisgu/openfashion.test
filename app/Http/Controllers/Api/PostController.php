<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['user', 'comments'])->get();
        return response()->json([
            'message' => 'All posts retrieved successfully',
            'data' => PostResource::collection($posts)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = Post::create($request->validated());
        
        return response()->json([
            'message' => 'Post created successfully',
            'data' => new PostResource($post)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $post = Post::with(['user', 'comments'])->findOrFail($id);
        return response()->json([
            'message' => "Post ID {$id} retrieved successfully",
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->update($request->validated());
        
        return response()->json([
            'message' => 'Post updated successfully',
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();
        
        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

    /**
     * Get all comments for a specific post.
     */
    public function comments(string $id): JsonResponse
    {
        $post = Post::with('comments')->findOrFail($id);
        $comments = $post->comments;
        
        $message = $comments->count() > 0 
            ? "Comments for post ID {$id}" 
            : "No comments found for post ID {$id}";
            
        return response()->json([
            'message' => $message,
            'data' => CommentResource::collection($comments)
        ]);
    }
}
