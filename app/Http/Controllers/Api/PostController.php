<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PostService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Actions\PostActions\CreatePostAction;

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

            return ApiResponse::send(200, 'Posts retrieved successfully .', $data);

        } else {
            return ApiResponse::send(404, 'No posts found', []);
        }
    }

    public function latest()
    {
        $posts = Post::filter(request(['user_id']))->take(5)->get();
        if (count($posts) > 0) {
            return ApiResponse::send(200, 'Posts retireved successfully . ', PostResource::collection($posts));
        }

        return ApiResponse::send(404, 'No posts found', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = (new CreatePostAction)->execute(Auth::user()->id , $request->validated());

        if ($post) {
            return ApiResponse::send(201, 'Post created successfully .', new PostResource($post));
        } else {
            return ApiResponse::send(500, 'Somthing went wrong', []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::with('author', 'comments', 'images')->findOrFail($id);

        return ApiResponse::send(200, 'Post retireved successfully . ', new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        if (! Auth()->user()->can('has-post', $post)) {
            return ApiResponse::send(403, 'You are not allowed to take this action', null);
        }

        $updatedPost = $post->update($request->validated());

        if ($updatedPost) {
            return ApiResponse::send(200, 'Post updated successfully .', new PostResource($post));
        } else {
            return ApiResponse::send(500, 'Something went wrong . ', null);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id, PostService $postService)
    {
        $post = Post::with('images')->findOrFail($id);

        if ($postService->destroy($post)) {
            return ApiResponse::send(200, 'Post deleted successfully . ', []);
        } else {
            return ApiResponse::send(500, 'Something went wrong. ');
        }
    }
}
