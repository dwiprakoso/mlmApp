@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.invest.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Riwayat Investasi</h5>
        </div>
        <div>
            <a href="{{ route('member.invest.index') }}" class="btn btn-gold btn-sm">
                <i class="bi bi-plus-circle me-1"></i>
                <small>Investasi Baru</small>
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-2 mb-4">
        <div class="col-6">
            <div class="card-dark p-3 text-center">
                <h6 class="text-gold mb-1">{{ $transactions->where('status', 'success')->count() }}</h6>
                <small class="text-muted">Berhasil</small>
            </div>
        </div>
        <div class="col-6">
            <div class="card-dark p-3 text-center">
                <h6 class="text-gold mb-1">
                    {{ $transactions->where('status', 'pending')->count() + $transactions->where('status', 'waiting_confirmation')->count() }}
                </h6>
                <small class="text-muted">Menunggu</small>
            </div>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="mb-3">
        <div class="btn-group w-100" role="group">
            <button type="button" class="btn btn-outline-gold active filter-btn" data-status="all">
                <small>Semua</small>
            </button>
            <button type="button" class="btn btn-outline-gold filter-btn" data-status="pending">
                <small>Pending</small>
            </button>
            <button type="button" class="btn btn-outline-gold filter-btn" data-status="success">
                <small>Berhasil</small>
            </button>
            <button type="button" class="btn btn-outline-gold filter-btn" data-status="failed">
                <small>Gagal</small>
            </button>
        </div>
    </div>

    <!-- Transaction List -->
    <div id="transaction-list">
        @if ($transactions->count() > 0)
            @foreach ($transactions as $transaction)
                <div class="card-dark p-3 mb-3 transaction-item" data-status="{{ $transaction->status }}"
                    onclick="viewTransaction('{{ route('member.invest.show', $transaction->id) }}')">

                    <!-- Header with Status -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div
                                class="status-icon me-2 
                                @if ($transaction->status === 'success') text-success
                                @elseif($transaction->status === 'failed') text-danger
                                @elseif($transaction->status === 'waiting_confirmation') text-warning
                                @else text-info @endif">
                                <i
                                    class="bi 
                                    @if ($transaction->status === 'success') bi-check-circle-fill
                                    @elseif($transaction->status === 'failed') bi-x-circle-fill
                                    @elseif($transaction->status === 'waiting_confirmation') bi-clock-fill
                                    @else bi-info-circle-fill @endif fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0">{{ $transaction->reference }}</h6>
                                <small class="text-muted">{{ $transaction->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span
                                class="badge 
                                @if ($transaction->status === 'success') bg-success
                                @elseif($transaction->status === 'failed') bg-danger
                                @elseif($transaction->status === 'waiting_confirmation') bg-warning text-dark
                                @else bg-info @endif">
                                @if ($transaction->status === 'pending')
                                    Menunggu Konfirmasi Admin
                                @elseif($transaction->status === 'waiting_confirmation')
                                    Konfirmasi
                                @elseif($transaction->status === 'success')
                                    Berhasil
                                @elseif($transaction->status === 'failed')
                                    Gagal
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Product Info -->
                    @if ($transaction->product)
                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <small class="text-white fw-bold">{{ $transaction->product->name }}</small>
                                        <br>
                                        <small class="text-muted">{{ $transaction->product->type }} •
                                            {{ $transaction->product->duration }} Hari</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="text-gold mb-0">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</h6>
                                <small class="text-muted">{{ $transaction->type }}</small>
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="col-8">
                                <small class="text-muted">Produk tidak tersedia</small>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="text-gold mb-0">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</h6>
                                <small class="text-muted">{{ $transaction->type }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- ✅ NEW: Expired At Info -->
                    @if ($transaction->expired_at)
                        <div class="mb-3 p-2 rounded" style="background-color: rgba(68, 68, 68, 0.3);">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    Berakhir {{ \Carbon\Carbon::parse($transaction->expired_at)->format('d M Y') }}
                                </small>
                            </div>
                        </div>
                    @endif

                    <!-- Progress Indicator -->
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill 
                                @if ($transaction->status === 'success') bg-success
                                @elseif($transaction->status === 'failed') bg-danger
                                @elseif($transaction->status === 'waiting_confirmation') bg-warning
                                @else bg-info @endif"
                                style="width: 
                                    @if ($transaction->status === 'pending') 50%
                                    @elseif($transaction->status === 'success') 100%
                                    @elseif($transaction->status === 'failed') 100% @endif">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Dibuat</small>
                            <small class="text-muted">
                                @if ($transaction->status === 'pending')
                                    Menunggu Konfirmasi Admin
                                @elseif($transaction->status === 'success')
                                    Selesai
                                @elseif($transaction->status === 'failed')
                                    Gagal
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            @if ($transactions->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links('pagination::bootstrap-4') }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-inbox fs-1 text-muted"></i>
                </div>
                <h6 class="text-white mb-2">Belum Ada Riwayat Investasi</h6>
                <p class="text-muted mb-4">Anda belum memiliki transaksi investasi.</p>
                <a href="{{ route('member.invest.index') }}" class="btn btn-gold">
                    <i class="bi bi-plus-circle me-2"></i>
                    Mulai Investasi
                </a>
            </div>
        @endif
    </div>

    <!-- No Results Message (Hidden by default) -->
    <div id="no-results" class="text-center py-5" style="display: none;">
        <div class="mb-3">
            <i class="bi bi-search fs-1 text-muted"></i>
        </div>
        <h6 class="text-white mb-2">Tidak Ada Hasil</h6>
        <p class="text-muted mb-0">Tidak ada transaksi dengan status yang dipilih.</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            const transactionItems = document.querySelectorAll('.transaction-item');
            const noResults = document.getElementById('no-results');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const status = this.dataset.status;

                    // Update active button
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active', 'btn-gold');
                        btn.classList.add('btn-outline-gold');
                    });
                    this.classList.remove('btn-outline-gold');
                    this.classList.add('active', 'btn-gold');

                    // Filter transactions
                    let visibleCount = 0;
                    transactionItems.forEach(item => {
                        const itemStatus = item.dataset.status;
                        if (status === 'all' || itemStatus === status) {
                            item.style.display = 'block';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (visibleCount === 0) {
                        noResults.style.display = 'block';
                    } else {
                        noResults.style.display = 'none';
                    }
                });
            });
        });

        // View transaction detail
        function viewTransaction(url) {
            window.location.href = url;
        }

        // Refresh page function
        function refreshTransactions() {
            location.reload();
        }
    </script>

    <style>
        .card-dark {
            background-color: var(--secondary-dark, #2c2c2c);
            border: 1px solid var(--border-color, #444);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .card-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-color: var(--gold-color, #ffd700);
        }

        .transaction-item {
            position: relative;
        }

        .transaction-item::after {
            content: '';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            border-right: 2px solid var(--gold-color, #ffd700);
            border-top: 2px solid var(--gold-color, #ffd700);
            transform: translateY(-50%) rotate(45deg);
            opacity: 0.7;
        }

        .circle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .expired-info {
            transition: all 0.3s ease;
        }

        .expired-info:hover {
            background-color: rgba(255, 215, 0, 0.15) !important;
        }

        .progress-container {
            margin-top: 1rem;
        }

        .progress-bar {
            background-color: var(--border-color, #444);
            height: 4px;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .btn-gold {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
            font-weight: 500;
        }

        .btn-outline-gold {
            border-color: var(--gold-color, #ffd700);
            color: var(--gold-color, #ffd700);
        }

        .btn-outline-gold.active,
        .btn-outline-gold:hover {
            background-color: var(--gold-color, #ffd700);
            color: #000;
        }

        .text-gold {
            color: var(--gold-color, #ffd700) !important;
        }

        .bg-gold {
            background-color: var(--gold-color, #ffd700) !important;
        }

        .filter-btn {
            border-radius: 0;
        }

        .filter-btn:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .filter-btn:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .badge {
            font-size: 0.75rem;
        }

        /* Pagination Styling */
        .pagination {
            margin: 0;
        }

        .page-link {
            background-color: var(--secondary-dark, #2c2c2c);
            border-color: var(--border-color, #444);
            color: var(--gold-color, #ffd700);
        }

        .page-link:hover {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
        }

        .page-item.active .page-link {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
        }

        @media (max-width: 768px) {
            .transaction-item::after {
                right: 10px;
                width: 6px;
                height: 6px;
            }

            .d-flex.gap-2 {
                gap: 0.5rem !important;
            }
        }
    </style>
@endsection
