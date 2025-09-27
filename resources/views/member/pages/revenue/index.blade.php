@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Riwayat Revenue</h5>
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
                <h6 class="text-gold mb-1">{{ $revenues->count() }}</h6>
                <small class="text-muted">Total Revenue</small>
            </div>
        </div>
        <div class="col-6">
            <div class="card-dark p-3 text-center">
                <h6 class="text-gold mb-1">IDR {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                <small class="text-muted">Total Earned</small>
            </div>
        </div>
    </div>

    <!-- Revenue List -->
    <div id="revenue-list">
        @if ($revenues->count() > 0)
            @foreach ($revenues as $revenue)
                <div class="card-dark p-3 mb-3 revenue-item">

                    <!-- Header with Revenue Count -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="status-icon me-2 text-success">
                                <i class="bi bi-cash-coin fs-5"></i>
                            </div>
                            <div>
                                <h6 class="text-white mb-0">Revenue ke-{{ $revenue->revenue_count }}</h6>
                                <small class="text-muted">{{ $revenue->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <div class="text-end">
                            @if (isset($revenue->day_in_cycle))
                                <span class="badge bg-success">
                                    Hari {{ $revenue->day_in_cycle }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    @if ($revenue->product)
                        <div class="row mb-3">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <small class="text-white fw-bold">{{ $revenue->product->name }}</small>
                                        <br>
                                        <small class="text-muted">{{ $revenue->product->type }} •
                                            {{ $revenue->product->duration }} Hari</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="text-gold mb-0">IDR {{ number_format($revenue->amount, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    @else
                        <div class="row mb-3">
                            <div class="col-8">
                                <small class="text-muted">Produk tidak tersedia</small>
                            </div>
                            <div class="col-4 text-end">
                                <h6 class="text-gold mb-0">IDR {{ number_format($revenue->amount, 0, ',', '.') }}</h6>
                                <small class="text-muted">{{ $revenue->reference }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- Progress Indicator -->
                    @if ($revenue->product && isset($revenue->day_in_cycle))
                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill bg-success"
                                    style="width: {{ ($revenue->day_in_cycle / $revenue->product->duration) * 100 }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Hari {{ $revenue->day_in_cycle }}</small>
                                <small class="text-muted">dari {{ $revenue->product->duration }} hari</small>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-cash-stack fs-1 text-muted"></i>
                </div>
                <h6 class="text-white mb-2">Belum Ada Revenue</h6>
                <p class="text-muted mb-4">Anda belum mendapatkan revenue dari investasi.</p>
                <a href="{{ route('member.invest.index') }}" class="btn btn-gold">
                    <i class="bi bi-plus-circle me-2"></i>
                    Mulai Investasi
                </a>
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

        .revenue-item {
            position: relative;
        }

        .circle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
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
