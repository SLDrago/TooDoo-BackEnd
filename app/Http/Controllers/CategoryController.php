<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function addCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create($validatedData);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }
}
