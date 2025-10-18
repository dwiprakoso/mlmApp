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
                'presentase' => 'required|integer|min:1',
                'is_active' => 'required|boolean',
            ]);

            $price = $request->price;
            $presentase = $request->presentase;
            $duration = $request->duration;

            $total_profit = ($price * $presentase) / 100;
            $profit = $total_profit / $duration;

            Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'price' => $request->price,
                'type' => $request->type,
                'presentase' => $request->presentase,
                'total_profit' => $total_profit,
                'profit' => $profit,
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
                'presentase' => 'required|integer|min:1',
                'is_active' => 'required|boolean',
            ]);

            $price = $request->price;
            $presentase = $request->presentase;
            $duration = $request->duration;

            $total_profit = ($price * $presentase) / 100;
            $profit = $total_profit / $duration;

            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'duration' => $request->duration,
                'price' => $request->price,
                'type' => $request->type,
                'presentase' => $request->presentase,
                'total_profit' => $total_profit,
                'profit' => $profit,
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
