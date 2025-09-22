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
                'duration' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'type' => 'required|string|in:Rencana Lanjutan,Pendapatan Stabil,Keuntungan VIP',
                'is_active' => 'required|boolean',
            ]);

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'price' => $request->price,
                'type' => $request->type,
                'is_active' => $request->is_active == '1' ? 1 : 0,
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
                'duration' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
                'type' => 'required|string|in:Rencana Lanjutan,Pendapatan Stabil,Keuntungan VIP',
                'is_active' => 'required|boolean',
            ]);

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'price' => $request->price,
                'type' => $request->type,
                'is_active' => $request->is_active == '1' ? 1 : 0,
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
