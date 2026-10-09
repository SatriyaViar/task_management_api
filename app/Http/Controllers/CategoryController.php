<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'message' => 'Daftar kategori berhasil diambil',
            'data' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data' => $category
        ], 201);
    }
}