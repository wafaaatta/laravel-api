<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories); 
    }

    public function show($id)
    {

        $category = Category::findOrFail($id);
        return response()->json($category);

    }
    public function store(Request $request) 
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable'

        ]);

        if ($validator->fails()){
            return response()->json(['error' => $validator->errors()],400);
        }

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable'
            
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Category::findOrFail($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    }





