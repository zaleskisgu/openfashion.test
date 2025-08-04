<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $comments = Comment::with(['user', 'post'])->get();
        return response()->json([
            'message' => 'All comments retrieved successfully',
            'data' => CommentResource::collection($comments)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): JsonResponse
    {
        $comment = Comment::create($request->validated());
        
        return response()->json([
            'message' => 'Comment created successfully',
            'data' => new CommentResource($comment)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $comment = Comment::with(['user', 'post'])->find($id);
        
        if (!$comment) {
            return response()->json([
                'message' => "Comment with ID {$id} not found",
                'error' => 'Comment not found'
            ], 404);
        }
        
        return response()->json([
            'message' => "Comment ID {$id} retrieved successfully",
            'data' => new CommentResource($comment)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, string $id): JsonResponse
    {
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json([
                'message' => "Comment with ID {$id} not found",
                'error' => 'Comment not found'
            ], 404);
        }
        
        $comment->update($request->validated());
        
        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => new CommentResource($comment)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $comment = Comment::find($id);
        
        if (!$comment) {
            return response()->json([
                'message' => "Comment with ID {$id} not found",
                'error' => 'Comment not found'
            ], 404);
        }
        
        $comment->delete();
        
        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }
}
