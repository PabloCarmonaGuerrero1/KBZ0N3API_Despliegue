<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all(); 
        return response()->json($products);  
    }
    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    $productsData = $request->all();


    if (!is_array($productsData) || array_keys($productsData) !== range(0, count($productsData) - 1)) {
        $productsData = [$productsData]; 
    }

    $createdProducts = [];

    foreach ($productsData as $productData) {
        $validated = validator($productData, [
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|url',
            'main_meals' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'allergens' => 'nullable|string',
            'diet' => 'nullable|string',
            'TypeOfProduct' => 'required|string|max:255',
        ])->validate();

        $createdProducts[] = Product::create($validated);
    }

    return response()->json([
        'message' => count($createdProducts) . ' producto(s) creado(s) exitosamente',
        'products' => $createdProducts
    ], 201);
}

    
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'main_meals' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'allergens' => 'nullable|string',
            'diet' => 'nullable|string',
            'image' => 'nullable|string',
            'TypeOfProduct' => 'required|string|max:255',
        ]);


        $product->update($validated);

        return response()->json($product);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Elimina el producto
        $product->delete();

        return response()->json(null, 204);  // Retorna una respuesta vacía con el código de estado 204 (sin contenido)
    }
    /**
    * Display the specified resource.
    */
    public function show(Product $product)
    {
        return response()->json($product);  // Devuelve el producto encontrado en formato JSON
    }
    public function searchByName($name)
    {
        // Busca el usuario con el nombre exacto
        $product = Product::where('name', $name)->first();
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        return response()->json($product);
    }
    public function search(Request $request)
{
    $query = Product::query();

    // Filtrar por precio
    if ($request->has('price')) {
        $query->where('price', '<=', $request->price);
    }

    // Filtrar por main_meals
    if ($request->has('main_meals')) {
        $query->where('main_meals', $request->main_meals);
    }

    // Búsqueda parcial por nombre (letra por letra)
    if ($request->has('name')) {
        $query->where('name', 'LIKE', '%' . $request->name . '%');
    }

    // Filtrar por dieta específica
    if ($request->has('diet')) {
        $query->where('diet', $request->diet);
    }

    // Excluir productos que contengan alérgenos específicos
    if ($request->has('allergens')) {
        $allergens = explode(',', $request->allergens);
        foreach ($allergens as $allergen) {
            $query->where('allergens', 'NOT LIKE', '%'.$allergen.'%');
        }
    }

    // Obtener resultados filtrados
    $products = $query->get();

    return response()->json($products);
}


}
