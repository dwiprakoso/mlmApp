@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Riwayat Komisi</h5>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-2 mb-4">
        <div class="col-6">
            <div class="card-dark p-3 text-center">
                <h6 class="text-gold mb-1">{{ $commissions->count() }}</h6>
                <small class="text-muted">Total Komisi</small>
            </div>
        </div>
        <div class="col-6">
            <div class="card-dark p-3 text-center">
                <h6 class="text-gold mb-1">IDR {{ number_format($commissions->sum('amount'), 0, ',', '.') }}</h6>
                <small class="text-muted">Total Diterima</small>
            </div>
        </div>
    </div>

    <!-- Commission List -->
    <div id="commission-list">
        @if ($commissions->count() > 0)
            @foreach ($commissions as $c)
                <div class="card-dark p-3 mb-3 commission-item">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <div class="status-icon me-2 text-success">
                                <i class="bi bi-cash-stack fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0">{{ $c->reference }}</h6>
                                <small class="text-muted">{{ $c->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Komisi Info -->
                    <div class="row">
                        <div class="col-8">
                            <small class="text-muted">
                                Dari: {{ $c->sourceUser ? $c->sourceUser->phone : 'Tidak diketahui' }}
                            </small>
                        </div>
                        <div class="col-4 text-end">
                            <h6 class="text-gold mb-0">IDR {{ number_format($c->amount, 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-wallet2 fs-1 text-muted"></i>
                </div>
                <h6 class="text-white mb-2">Belum Ada Komisi</h6>
                <p class="text-muted mb-4">Anda belum menerima komisi dari referral.</p>
            </div>
        @endif
    </div>

    <style>
        .card-dark {
            background-color: var(--secondary-dark, #2c2c2c);
            border: 1px solid var(--border-color, #444);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .card-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-color: var(--gold-color, #ffd700);
        }

        .commission-item {
            position: relative;
        }

        .btn-gold {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
            font-weight: 500;
        }

        .text-gold {
            color: var(--gold-color, #ffd700) !important;
        }

        .bg-gold {
            background-color: var(--gold-color, #ffd700) !important;
        }

        .badge {
            font-size: 0.75rem;
        }
    </style>
@endsection
