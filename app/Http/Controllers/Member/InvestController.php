<?php

namespace App\Http\Controllers\Member;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvestController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->orderBy('type')
            ->orderBy('price')
            ->get()
            ->groupBy('type');

        // Get current user balance
        $currentBalance = Transaction::calculateUserBalance(Auth::id());

        return view('member.pages.invest.index', compact('products', 'currentBalance'));
    }

    public function store(Request $request)
    {
        // Debug: Log the request
        Log::info('Investment Store Request', [
            'product_id' => $request->product_id,
            'user_id' => Auth::id(),
            'all_data' => $request->all()
        ]);

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            // Get product details
            $product = Product::findOrFail($request->product_id);

            // Check if product is active
            if (!$product->is_active) {
                DB::rollBack();
                return back()->with('error', 'Produk ini tidak tersedia.');
            }

            // Check user balance
            $currentBalance = Transaction::calculateUserBalance(Auth::id());

            if ($currentBalance < $product->price) {
                DB::rollBack();
                return back()->with('error', 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($currentBalance, 0, ',', '.') . ', Harga produk: Rp ' . number_format($product->price, 0, ',', '.'));
            }

            // Generate unique reference number
            $reference = $this->generateReference();

            // Create successful purchase transaction
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'reference' => $reference,
                'amount' => $product->price,
                'type' => 'purchase',
                'status' => 'success',
                'payment_method' => 'balance',
                'payment_proof' => null,
                'approved_by' => null,
            ]);

            DB::commit();

            Log::info('Investment Transaction Created Successfully', [
                'transaction_id' => $transaction->id,
                'reference' => $reference,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'amount' => $product->price,
                'previous_balance' => $currentBalance,
                'new_balance' => $currentBalance - $product->price
            ]);

            return redirect()->route('member.invest.log')
                ->with('success', 'Pembelian berhasil! Produk ' . $product->name . ' telah ditambahkan ke portofolio Anda.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Investment Store Error', [
                'error' => $e->getMessage(),
                'product_id' => $request->product_id,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }

    // Method show masih bisa dipakai kalau ada case khusus yang butuh
    public function show($id)
    {
        $transaction = Transaction::with(['product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('member.pages.invest.show', compact('transaction'));
    }

    public function log()
    {
        $transactions = Transaction::with('product')
            ->where('user_id', Auth::id())
            ->where('type', 'purchase')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get current balance for display
        $currentBalance = Transaction::calculateUserBalance(Auth::id());

        return view('member.pages.invest.log', compact('transactions', 'currentBalance'));
    }

    private function generateReference()
    {
        // Generate reference dengan format INV-6 digit random
        $reference = 'INV-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Pastikan reference unik
        while (Transaction::where('reference', $reference)->exists()) {
            $reference = 'INV-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        }

        return $reference;
    }
}
