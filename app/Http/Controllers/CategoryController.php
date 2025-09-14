<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Traits\ApiResponse;

class CategoryController extends Controller
{
    use ApiResponse;

    public function index()
    {
        return $this->successResponse('Category fetched successfully.', Category::all(), 200);
    }


    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        $category = Category::firstOrCreate(
            ['title' => $data['title']],
            $data
        );

        if (!$category->wasRecentlyCreated) {
            return $this->errorResponse('This Category has already been added', $category, 400);
        }

        return $this->successResponse('Category added successfully.', $category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->successResponse('Category', $category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $categoery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $categoery)
    {
        $categoery->delete;
        return $this->successResponse('Category deleted successfully.', null, 200);
    }
}
