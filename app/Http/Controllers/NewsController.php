<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\News;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\NewsResource;
use Illuminate\Support\Facades\Date;
use App\Http\Requests\StoreNewsRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    use ApiResponse;

    public function index()
    {

        $news = News::with('media')->paginate(20);
        return $this->successResponse(
            'News list fetched successfully',
            NewsResource::collection($news),
            200,
            [
                'total'        => $news->total(),
                'per_page'     => $news->perPage(),
                'current_page' => $news->currentPage(),
                'last_page'    => $news->lastPage(),
            ]
        );
    }


    public function store(StoreNewsRequest $request)
    {
        $data = $request->validated();

        $news = News::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'publish_at' => $data['publish_at'] ?? null,
        ]);
        $contentType = 'news';
        foreach ($data as $item) {
            $file = $data['media_file'];
            $type = $data['media_type'];
        }

        $path = FileHelper::storeFile($file, $type, 'news');
        //! app/public/images/news/2034/2/4/98248248.png

        $news->media()->create([
            'type' => $type,
            'url' => $path
        ]);

        $news->load('media');

        return $this->successResponse('News Created Successfully.', new NewsResource($news), 201);
    }


    public function show($id)
    {
        try {
            $news = News::findOrFail($id);
            return $this->successResponse('news', new NewsResource($news));
        } catch (ModelNotFoundException) {
            return $this->errorResponse('news not found', null, 404);
        }
    }


    public function destroy($newsId)
    {
        try {
            $news = News::findOrFail($newsId);

            foreach ($news->media as $media) {
                if ($media->url && Storage::disk('public')->exists($media->url)) {
                    Storage::disk('public')->delete($media->url);
                }
                $media->delete();
            }

            $news->delete();

            return response()->json(['message' => 'News deleted successfully'], 200);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'News not found or already deleted'], 404);
        }
    }
}
