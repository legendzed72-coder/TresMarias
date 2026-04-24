<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index()
    {
        return response()->json(Ingredient::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_type' => 'required|string',
            'stock_quantity' => 'required|numeric|min:0',
            'min_stock_level' => 'nullable|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $ingredient = Ingredient::create($request->only([
            'name', 'unit_type', 'stock_quantity', 'min_stock_level', 'cost_per_unit', 'supplier'
        ]));
        return response()->json($ingredient, 201);
    }

    public function show(Ingredient $ingredient)
    {
        return response()->json($ingredient);
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit_type' => 'required|string',
            'stock_quantity' => 'required|numeric|min:0',
            'min_stock_level' => 'nullable|numeric|min:0',
            'cost_per_unit' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        $ingredient->update($request->only([
            'name', 'unit_type', 'stock_quantity', 'min_stock_level', 'cost_per_unit', 'supplier'
        ]));
        return response()->json($ingredient);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted']);
    }
}