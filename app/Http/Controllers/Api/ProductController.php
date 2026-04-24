<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function show(Product $product)
    {
        return response()->json($product->load('category'));
    }

    public function categories()
    {
        return response()->json(Category::active()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric',
            'stock_quantity' => 'nullable|integer',
            'min_stock_level' => 'nullable|integer',
            'unit_type' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'available_for_preorder' => 'nullable|boolean',
            'preorder_hours_needed' => 'nullable|integer',
            'allergens' => 'nullable|json',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $imagePath;
        }

        // Convert allergens from JSON string to array
        if (isset($data['allergens']) && is_string($data['allergens'])) {
            $data['allergens'] = json_decode($data['allergens'], true);
        }

        $product = Product::create($data);
        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'cost_price' => 'nullable|numeric',
            'stock_quantity' => 'nullable|integer',
            'min_stock_level' => 'nullable|integer',
            'unit_type' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'available_for_preorder' => 'nullable|boolean',
            'preorder_hours_needed' => 'nullable|integer',
            'allergens' => 'nullable|json',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_url'] = '/storage/' . $imagePath;
        }

        // Convert allergens from JSON string to array
        if (isset($data['allergens']) && is_string($data['allergens'])) {
            $data['allergens'] = json_decode($data['allergens'], true);
        }

        $product->update($data);
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }

    public function archive(Product $product)
    {
        $product->archive();
        return response()->json(['message' => 'Product archived successfully', 'product' => $product]);
    }

    public function unarchive(Product $product)
    {
        $product->unarchive();
        return response()->json(['message' => 'Product unarchived successfully', 'product' => $product]);
    }

    public function getAll()
    {
        return response()->json(Product::select('id', 'name', 'price', 'stock_quantity')->get());
    }
}