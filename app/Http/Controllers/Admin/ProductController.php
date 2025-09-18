<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.pages.product.index', compact('products'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
            ]);

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => $request->has('is_active') ? 1 : 0, // Explicitly convert to 1 or 0
            ]);

            return redirect()->route('admin.product.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
            ]);

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => $request->has('is_active') ? 1 : 0, // Explicitly convert to 1 or 0
            ]);

            return redirect()->route('admin.product.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('admin.product.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
