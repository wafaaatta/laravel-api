<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="API de gestion des catégories",
 *     version="1.0",
 *     description="Cette API permet de gérer les opérations CRUD sur les catégories.",
 * )
 */
class CategoryController extends Controller
{
    /**
     * Annotation de l'opération HTTP :
     * @OA\Get( 
     *     path="/categories",
     *     summary="Liste de toutes les catégories",
     *     @OA\Response(
     *         response=200,
     *         description="Liste de toutes les catégories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Category")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        return response()->json($categories); 
    }

    /**
     * Annotation de l'opération HTTP :
     * @OA\Get(
     *     path="/categories/{id}",
     *     summary="Afficher une catégorie par ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie à afficher",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie récupérée avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return response()->json($category);
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     summary="Créer une nouvelle catégorie",
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/NewCategory")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation des données en échec"
     *     )
     * )
     */
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'description' => 'nullable'
        ]);

        if ($validator->fails()){
            return response()->json(['error' => $validator->errors()], 400);
        }

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     summary="Modifier une catégorie existante",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie à modifier",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCategory")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie modifiée avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation des données en échec"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     summary="Supprimer une catégorie existante",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie à supprimer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Catégorie supprimée avec succès"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}


/*namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;


class CategoryController extends Controller
{
    //
    public function index()
    {


        $categories = Category::with('products')->get();
        return response()->json($categories); 
        //$categories = Category::all();
       // return response()->json($categories); 
    }

    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
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

    }*/





