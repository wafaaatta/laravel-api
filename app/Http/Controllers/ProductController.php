<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA; 

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/products",
     *     summary="Récupérer tous les produits",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des produits récupérée avec succès",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        $products = Product::with("categories")->get();
        return $products;
    }

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Créer un nouveau produit",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produit créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $categories = $request->input('categories');
        // Vérifier si la catégorie n'est pas vide et n'est pas un tableau
        if (!empty($categories) && !is_array($categories)) 
        {
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

        // Stockage de l'image et récupération du chemin
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            $imagePath = null;
        }

        $product = new Product([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'image' => $imagePath, 
        ]);

        $product->save(); 

        if (!empty($categories)) {
            $product->categories()->attach($categories);
        }

        $products = Product::with("categories")->get();
        
        return response()->json($products, 201);
    }

    /**
     * @OA\Get(
     *     path="/products/{id}",
     *     summary="Récupérer un produit par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit à récupérer",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit récupéré avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function show($id)
    {
        return Product::with('categories')->findOrFail($id);
    }

    /**
     * @OA\Put(
     *     path="/products/{id}",
     *     summary="Mettre à jour un produit par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit à mettre à jour",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit mis à jour avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        $product->load('categories'); 
        return $product;
    }

    /**
     * @OA\Delete(
     *     path="/products/{id}",
     *     summary="Supprimer un produit par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du produit à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produit supprimé avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Produit non trouvé"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}


/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;


class ProductController extends Controller

{
    public function index()
    {
        $products = Product::with("categories")->get();
        return $products;
    }



    public function store(Request $request)

{
    
    $categories = $request->input('categories');
        // Vérifier si la catégorie n'est pas vide et n'est pas un tableau
        if (!empty($categories) && !is_array($categories)) 
        {
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

    // Stockage de l'image et récupération du chemin
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images', 'public');
    } else {
        $imagePath = null;
    }

    $product = new Product([
        'name' => $request->get('name'),
        'description' => $request->get('description'),
        'price' => $request->get('price'),
        'stock' => $request->get('stock'),
        'image' => $imagePath, 
        
    ]);

    $product->save(); 

    if (!empty($categories)) {
        $product->categories()->attach($categories);
    }

    $products = Product::with("categories")->get();
    
    return response()->json($products, 201);
}



    public function show($id)
    {
        return Product::with('categories')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        $product->load('categories'); 
        return $product;
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}*/

