<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Media;
use App\Helpers\FileHelper;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Requests\UpdatePostImageRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{

    use ApiResponse;

    public function index()
    {
        $userId = Auth::id();
        $posts = Post::byPublisher($userId)->with('media')->paginate(20);
        return $this->successResponse('Posts list fetched successfully.', PostResource::collection($posts), 200,   [
            'total'        => $posts->total(),
            'per_page'     => $posts->perPage(),
            'current_page' => $posts->currentPage(),
            'last_page'    => $posts->lastPage(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();
        $post = Post::create([
            'title' => $data['title'],
            'body' => $data['body'],
            'publisher_id' => $userId,
        ]);
        foreach ($data['media'] as $item) {
            $file = $item['file'];
            $type = $item['type'];

            $path = FileHelper::storeFileMedia($file, $type, 'post', 'public');
            $post->media()->create([
                'type' => $type,
                'url'  => $path,
            ]);
        }
        $post->load('media');

        return $this->successResponse('Post Created Suucessfully.', new PostResource($post), 201);
    }


    public function show($postId)
    {
        try {
            $userId = Auth::id();
            $post = Post::byPublisher($userId)->with('media')->findOrFail($postId);
            return $this->successResponse('Post', new PostResource($post));
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Post not found', null, 404);
        }
    }


    public function update(UpdatePostRequest $request, $id)
    {
        try {
            $userId = Auth::id();
            $data = $request->validated();
            $post = Post::byPublisher($userId)->with('media')->findOrFail($id);
            $post->update($data);
            return $this->successResponse('Post updated Successfully.', new PostResource($post), 200);
        } catch (ModelNotFoundException) {
            return  $this->errorResponse('Post not found', null, 404);
        }
    }
    //    public function updateImages(UpdatePostImageRequest $request, $postId)
    //     {
    //         try {
    //             $data = $request->validated();
    //             $post = Post::findOrFail($postId);

    //             // امسح الصور القديمة من التخزين + الداتا بيس
    //             foreach ($post->images as $oldImage) {
    //                 Storage::disk('public')->delete($oldImage->url);
    //                 $oldImage->delete();
    //             }

    //             // خزّن الصور الجديدة
    //             $contentType = 'post';
    //             foreach ($data['media'] as $item) {
    //                 $file = $item['file'];
    //                 $type = $item['type'];

    //                 $dateFolder = date('Y/m/d');
    //                 $bucket = $type === 'image' ? 'images' : 'videos';
    //                 $subdir = "$bucket/{$contentType}/{$dateFolder}";

    //                 $ext = $file->extension();
    //                 $filename = (string) Str::uuid() . '.' . $ext;

    //                 $path = $file->storeAs($subdir, $filename, 'public');

    //                 $post->media()->create([
    //                     'type' => $type,
    //                     'url'  => $path,
    //                 ]);
    //             }

    //             return response()->json(new PostResource($post->load('media')), 200);
    //         } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
    //             return response()->json(['message' => 'Post not found'], 404);
    //          }
    //     }// fix it later and add it the other customer 


    public function destroy($postId)
    {
        try {
            $userId = Auth::id();
            $post = Post::byPublisher($userId)->findOrFail($postId);
            foreach ($post->media as $media) {
                if ($media->url && Storage::disk('public')->exists($media->url)) {
                    Storage::disk('public')->delete($media->url);
                }
                $media->delete();
            }

            $post->delete();

            return $this->successResponse('Post deleted successfully', null, 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Post not found or already deleted', null, 404);
        }
    }
}
