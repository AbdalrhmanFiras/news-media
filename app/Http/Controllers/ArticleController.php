<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\updateArticleRequest;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ArticleController extends Controller
{

    use ApiResponse;

    public function index()
    {
        $userId = Auth::id();
        $articles = Article::byPublisher($userId)->paginate(20);
        return $this->successResponse(
            'Articles list fetched successfully.',
            ArticleResource::collection($articles),
            200,
            [
                'total' => $articles->total(),
                'per_page' => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page' => $articles->lastPage(),
            ]
        );
    }


    public function store(StoreArticleRequest $request)
    {
        $userId = Auth::id();
        $data = $request->validated();
        $data['publisher_id'] = $userId;
        $article = Article::create($data);
        return $this->successResponse('Article Create Successfully.', new ArticleResource($article), 201);
    }


    public function show($id)
    {
        try {
            $userId = Auth::id();
            $article = Article::byPublisher($userId)->findOrFail($id);
            return $this->successResponse('Article', new ArticleResource($article), 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Article not Found', null, 404);
        }
    }


    public function update(updateArticleRequest $request, $id)
    {
        try {
            $userId = Auth::id();
            $data = $request->validated();
            $article = Article::byPublisher($userId)->findOrFail($id);
            $article->update($data);
            return $this->successResponse('Article Updated Successfully.', new ArticleResource($article), 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Article not found', null, 404);
        }
    }


    public function destroy($id)
    {
        try {
            $userId = Auth::id();
            $article = Article::byPublisher($userId)->findOrFail($id);
            $article->delete();
            return $this->successResponse('Article deleted successfull', null, 200);
        } catch (ModelNotFoundException) {
            return $this->errorResponse('Article not found Or already deleted', null, 404);
        }
    }
}
