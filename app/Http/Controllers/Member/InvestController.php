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

        return view('member.pages.invest.index', compact('products'));
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
                return back()->with('error', 'Produk ini tidak tersedia.');
            }

            // Generate unique reference number
            $reference = $this->generateReference();

            // Create transaction with explicit null values
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'reference' => $reference,
                'amount' => $product->price,
                'type' => 'purchase',
                'status' => 'pending',
                'payment_method' => null,
                'payment_proof' => null,
                'approved_by' => null,
            ]);

            DB::commit();

            Log::info('Investment Transaction Created', [
                'transaction_id' => $transaction->id,
                'reference' => $reference
            ]);

            return redirect()->route('member.invest.show', $transaction->id)
                ->with('success', 'Transaksi berhasil dibuat. Silakan lanjutkan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Investment Store Error', [
                'error' => $e->getMessage(),
                'product_id' => $request->product_id,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage());
        }
    }

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

        return view('member.pages.invest.log', compact('transactions'));
    }

    public function uploadPaymentProof(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
            'payment_method' => 'required|string|in:bank_transfer,mobile_banking,atm,wallet_gris'
        ]);

        try {
            $transaction = Transaction::where('id', $request->transaction_id)
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->firstOrFail();

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');

                // Create filename with proper path
                $filename = 'invest/proofs/invest_proof_' . $transaction->id . '_' . time() . '.' . $file->getClientOriginalExtension();

                // Store file in storage/app/public directory
                $file->storeAs('public', $filename);

                // Update transaction - save path without 'storage/' prefix
                $transaction->update([
                    'payment_proof' => $filename,
                    'payment_method' => $request->payment_method,
                    'status' => 'waiting_confirmation'
                ]);

                return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
            }

            return back()->with('error', 'File bukti pembayaran tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Upload Payment Proof Error', [
                'error' => $e->getMessage(),
                'transaction_id' => $request->transaction_id
            ]);

            return back()->with('error', 'Terjadi kesalahan saat upload bukti pembayaran.');
        }
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
