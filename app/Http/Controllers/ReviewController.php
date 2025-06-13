<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Crear la consulta base
        $query = Review::query();
    
        // Filtrar por nombre si se pasa como parámetro
        if ($request->has('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
    
        // Filtrar por tipo de reseña si se pasa como parámetro
        if ($request->has('TypeOfReview')) {
            $query->where('TypeOfReview', 'LIKE', '%' . $request->input('TypeOfReview') . '%');
        }
    
        // Ejecutar la consulta
        $reviews = $query->get();
    
        // Verificar si se encontraron reseñas
        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'No reviews found'], 404);
        }
    
        // Devolver las reseñas encontradas
        return response()->json($reviews, 200);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'TypeOfReview' => 'required|string|max:255',
            'DNI' => 'required|string',
            'punctuation' => 'required|numeric|min:0|max:10',
            'review' => 'required|string',
        ]);


        $review = Review::create($validated);

        return response()->json($review, 201);  
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return response()->json($review);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'TypeOfReview' => 'required|string|max:255',
            'DNI' => 'required|string',
            'punctuation' => 'required|numeric|min:0|max:10',
            'review' => 'required|string',
        ]);


        $review->update($validated);

        return response()->json($review);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {

        $review->delete();

        return response()->json(null, 204);  
    } 

}
