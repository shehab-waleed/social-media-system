<?php

namespace App\Http\Controllers\Api;

use App\Actions\PostActions\CreatePostAction;
use App\Actions\PostActions\DeletePostAction;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $posts = Post::filter(request(['user_id', 'title']))->with('author', 'comments', 'images')->paginate(10);

        if ($posts->count()) {
            if ($posts->total() > $posts->perPage()) {
                $data = [
                    'data' => PostResource::collection($posts),
                    'pagination links' => [
                        'current page' => $posts->currentPage(),
                        'per page' => $posts->perPage(),
                        'total' => $posts->total(),
                        'links' => [
                            'first' => $posts->url(1),
                            'last' => $posts->url($posts->lastPage()),
                        ],
                    ],
                ];
            } else {
                $data = PostResource::collection($posts);
            }

            return ApiResponse::send(JsonResponse::HTTP_OK, 'Posts retrieved successfully .', $data);

        } else {
            return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'No posts found', []);
        }
    }

    public function latest()
    {
        $posts = Post::filter(request(['user_id']))->take(5)->get();
        if (count($posts) > 0) {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Posts retireved successfully . ', PostResource::collection($posts));
        }

        return ApiResponse::send(JsonResponse::HTTP_NOT_FOUND, 'No posts found', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request, CreatePostAction $createPostAction)
    {
        $post = $createPostAction->execute(Auth::user()->id, $request->validated());

        if ($post) {
            return ApiResponse::send(JsonResponse::HTTP_CREATED, 'Post created successfully .', new PostResource($post));
        } else {
            return ApiResponse::send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong', []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('author', 'comments', 'images')->findOrFail($id);

        return ApiResponse::send(JsonResponse::HTTP_OK, 'Post retireved successfully . ', new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        if (! Auth()->user()->can('has-post', $post)) {
            return ApiResponse::send(JsonResponse::HTTP_FORBIDDEN, 'You are not allowed to take this action', null);
        }

        $updatedPost = $post->update($request->validated());

        if ($updatedPost) {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Post updated successfully .', new PostResource($post));
        } else {
            return ApiResponse::send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong . ', null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id, DeletePostAction $deletePostAction)
    {
        $post = Post::with('images')->findOrFail($id);

        if (! Auth()->user()->can('has-post', $post)) {
            abort(403, 'Your are not allowed to take this action');
        }

        if ($deletePostAction->execute($post)) {
            return ApiResponse::send(JsonResponse::HTTP_OK, 'Post deleted successfully . ', []);
        } else {
            return ApiResponse::send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, 'Something went wrong. ');
        }
    }
}
