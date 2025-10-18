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

        $purchasableBalance = Transaction::getPurchasableBalance(Auth::id());
        $balanceBreakdown = Transaction::getBalanceBreakdown(Auth::id());

        return view('member.pages.invest.index', compact('products', 'purchasableBalance', 'balanceBreakdown'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            if (!$product->is_active) {
                DB::rollBack();
                return back()->with('error', 'Produk ini tidak tersedia.');
            }

            $existingPurchases = Transaction::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('type', 'purchase')
                ->count();

            if ($existingPurchases >= 3) {
                DB::rollBack();
                return back()->with('error', 'Anda sudah mencapai batas maksimal pembelian produk ini (3 kali). Silahkan pilih produk lain.');
            }

            $purchaseValidation = Transaction::canPurchase(Auth::id(), $product->price);

            if (!$purchaseValidation['can_purchase']) {
                DB::rollBack();

                $balanceBreakdown = Transaction::getBalanceBreakdown(Auth::id());

                $errorMessage = 'Saldo deposit tidak mencukupi untuk pembelian ini. ' .
                    'Saldo deposit Anda: Rp ' . number_format($purchaseValidation['available'], 0, ',', '.') . ', ' .
                    'Harga produk: Rp ' . number_format($product->price, 0, ',', '.') . '. ' .
                    'Pembelian hanya dapat menggunakan saldo dari deposit.';

                if ($balanceBreakdown['revenue'] > 0 || $balanceBreakdown['commission'] > 0) {
                    $errorMessage .= ' (Revenue: Rp ' . number_format($balanceBreakdown['revenue'], 0, ',', '.') .
                        ', Commission: Rp ' . number_format($balanceBreakdown['commission'], 0, ',', '.') .
                        ' tidak dapat digunakan untuk pembelian)';
                }

                return back()->with('error', $errorMessage);
            }

            $reference = $this->generateReference();
            $durationDays = (int) $product->duration;
            $expiredAt = now()->addDays($durationDays);

            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'reference' => $reference,
                'amount' => $product->price,
                'type' => 'purchase',
                'source_balance_type' => 'deposit',
                'status' => 'pending',
                'payment_method' => 'balance',
                'payment_proof' => null,
                'approved_by' => null,
                'expired_at' => $expiredAt,
            ]);

            DB::commit();

            Log::info('Investment transaction created', [
                'transaction_id' => $transaction->id,
                'reference' => $reference,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'amount' => $product->price,
                'expired_at' => $expiredAt
            ]);

            $successMessage = 'Transaksi pembelian berhasil dibuat untuk produk ' . $product->name .
                '. Transaksi Anda sedang menunggu persetujuan admin. ' .
                'Masa aktif: ' . $durationDays . ' hari (hingga ' . $expiredAt->format('d/m/Y') . ')';

            return redirect()->route('member.invest.log')
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Investment store error', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
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

        $currentBalance = Transaction::calculateUserBalance(Auth::id());

        return view('member.pages.invest.log', compact('transactions', 'currentBalance'));
    }

    private function generateReference()
    {
        $reference = 'INV-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);

        while (Transaction::where('reference', $reference)->exists()) {
            $reference = 'INV-' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        }

        return $reference;
    }
}
