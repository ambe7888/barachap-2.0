<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryListsResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ChildCategoryResource;
use App\Http\Resources\SubCategoryResource;
use App\Models\Backend\Category;
use App\Models\Backend\ChildCategory;
use App\Models\Backend\SubCategory;
use Illuminate\Http\Request;

class CategoryManageController extends Controller
{

    public function categoriesWithService(Request $request){

        $search = $request->query('name');
        $categories = Category::where('status', 1)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'categories' => CategoryResource::collection($categories->items()),
            'pagination' => [
                'total' => $categories->total(),
                'count' => $categories->count(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'next_page_url' => $categories->nextPageUrl(),
                'prev_page_url' => $categories->previousPageUrl(),
            ]
        ], 200);
    }

    public function allCategory(Request $request){

        $search = $request->query('name');
        $categories = Category::where('status', 1)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(30);

        return response()->json([
            'categories' => CategoryResource::collection($categories->items()),
            'pagination' => [
                'total' => $categories->total(),
                'count' => $categories->count(),
                'per_page' => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'next_page_url' => $categories->nextPageUrl(),
                'prev_page_url' => $categories->previousPageUrl(),
            ]
        ], 200);
    }


    public function allSubCategory(Request $request)
    {
        $search = $request->query('name');
        $subCategories = SubCategory::where('status', 1)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'sub_categories' => SubCategoryResource::collection($subCategories->items()),
            'pagination' => [
                'total' => $subCategories->total(),
                'count' => $subCategories->count(),
                'per_page' => $subCategories->perPage(),
                'current_page' => $subCategories->currentPage(),
                'last_page' => $subCategories->lastPage(),
                'next_page_url' => $subCategories->nextPageUrl(),
                'prev_page_url' => $subCategories->previousPageUrl(),
            ]
        ], 200);
    }

    public function allChildCategory(Request $request)
    {
        $search = $request->query('name');
        $childCategories = ChildCategory::where('status', 1)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return response()->json([
            'child_categories' => SubCategoryResource::collection($childCategories->items()),
            'pagination' => [
                'total' => $childCategories->total(),
                'count' => $childCategories->count(),
                'per_page' => $childCategories->perPage(),
                'current_page' => $childCategories->currentPage(),
                'last_page' => $childCategories->lastPage(),
                'next_page_url' => $childCategories->nextPageUrl(),
                'prev_page_url' => $childCategories->previousPageUrl(),
            ]
        ], 200);
    }

}
