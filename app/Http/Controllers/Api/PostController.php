<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Jobs\DeleteUserPosts;
use App\Models\Post;
use App\Models\PostImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::latest()->filter(request(['user_id', 'title']))->with('author', 'comments', 'images')->paginate(10);
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
        $postData = $request->validated();
        $postData['user_id'] = $request->user()->id;
        unset($postData['images']);

        $record = Post::create($postData);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('post_images');
                PostImages::create([
                    'post_id' => $record->id,
                    'image' => $imagePath
                ]);
            }

        }

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
        $post = Post::with('author', 'comments', 'images')->find($id);

        if (! $post)
            return ApiResponse::send(200, 'Post not found', null);

        return ApiResponse::send(200, 'Post retireved successfully . ', new PostResource($post));
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
        $post = Post::with('images')->find($id);

        if (!$post)
            return ApiResponse::send(200, 'Post not found', []);

        if (!auth()->user()->can('has-post', $post))
            return ApiResponse::send(403, 'You are not authorized to take this action', []);

        foreach($post->images as $element){
            Storage::delete($element->image);
            $element->delete();
        }
        $post->delete();
        return ApiResponse::send(200, 'Post deleted successfully . ', []);
    }

}
