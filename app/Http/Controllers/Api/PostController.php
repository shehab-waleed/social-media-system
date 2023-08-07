<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\DeleteUserPosts;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::latest()->filter(request(['user_id', 'content']))->with('author', 'comments')->paginate(100);
        if (count($posts) > 0) {
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
            return ApiResponse::send(200, 'No posts found', []);
        }
    }

    public function latest()
    {
        $posts = Post::latest()->filter(request(['user_id']))->take(5)->get();

        if (count($posts) > 0)
            return ApiResponse::send(200, 'Posts retireved successfully . ', PostResource::collection($posts));

        return ApiResponse::send(200, 'No posts found', []);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
        $postData = $request->validated();
        $postData['user_id'] = $request->user()->id;

        $record = Post::create($postData);

        if ($record) {
            return ApiResponse::send(201, 'Post created successfully .', new PostResource($record));
        } else {
            return ApiResponse::send(200, "Somthing went wrong", []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, $id)
    {
        //
        $post = Post::find($id);

        if (!$post)
            return ApiResponse::send(200, 'Post not found', []);

        if (!auth()->user()->can('has-post', $post))
            return ApiResponse::send(403, 'You are not allowed to take this action', null);

        $updatedPost = $post->update($request->validated());
        if ($updatedPost)
            return ApiResponse::send(201, 'Post updated successfully .', new PostResource($post));
        else
            return ApiResponse::send(200, 'Something went wrong . ', null);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //
        $post = Post::find($id);

        if (! $post)
            return ApiResponse::send(200, 'Post not found', []);

        if (! auth()->user()->can('has-post', $post))
            return ApiResponse::send(403, 'You are not authorized to take this action', []);

        $post->delete();
        return ApiResponse::send(200, 'Post deleted successfully . ', []);
    }


    // This is an excersise for queue concept
    // public function destroyAll(int $userId)
    // {
    //     $posts = Post::where('user_id', $userId)->get();

    //     if ($posts->count() == 0)
    //         return ApiResponse::send(200, 'User has not posts', []);

    //     DeleteUserPosts::dispatch($userId);
    //     return ApiResponse::send(200, 'Your request processing .', []);
    // }
}
