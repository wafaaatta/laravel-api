<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller

{
    public function index()
    {
        return Product::all();
    }


   /* public function store(Request $request)
{
    $categories = $request->input('categories');
    // Vérifier si la catégorie n'est pas vide et n'est pas un tableau
    if (!empty($categories) && !is_array($categories)) {
        $categories = json_decode($categories); // Transformer en chaîne JSON  
        // Merge de la nouvelle catégorie dans la requête
        $request->merge(['categories' => $categories]);
    }
    

    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categories' => 'nullable|array',
        'categories.*' => 'exists:categories,id', 
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,gif|max:5120',
        
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    $product = Product::create($request->all());

    return response()->json($product, 201); 
    
}*/
public function store(Request $request)
{
    $categories = $request->input('categories');
    // Vérifier si la catégorie n'est pas vide et n'est pas un tableau
    if (!empty($categories) && !is_array($categories)) {
        $categories = json_decode($categories); // Transformer en chaîne JSON  
        // Merge de la nouvelle catégorie dans la requête
        $request->merge(['categories' => $categories]);
    }
    
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'categories' => 'nullable|array',
        'categories.*' => 'exists:categories,id', 
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,gif|max:5120',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time().'.'.$image->getClientOriginalExtension();
        
        $image->move(public_path('images'), $imageName);
        $request->merge(['image' => $imageName]);
    }



    $product = Product::create($request->all());

    return response()->json($product, 201); 
}


    public function show($id)
    {
        return Product::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}

