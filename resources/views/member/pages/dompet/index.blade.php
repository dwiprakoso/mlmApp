@extends('member.layouts.app')
@section('content')
    <!-- Hero Section dengan Balance -->
    <div class="hero-card mb-0">

        <!-- Balance Card -->
        <div class="balance-card">
            <div class="text-center mb-3">
                <h2 class="text-white mb-1">IDR {{ number_format($balance, 0, ',', '.') }}</h2>
                <p class="text-muted mb-0">Saldo akun</p>
            </div>

            <!-- Action Buttons -->
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('member.deposit.index') }}"
                        class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-upload me-2"></i>
                        <span>Deposit</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('member.withdraw.index') }}"
                        class="btn btn-warning w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-download me-2"></i>
                        <span>Penarikan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats Grid -->
    <div class="content-section mb-5 pb-5">
        <div class="row g-3">
            <!-- Messages Card -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-graph-up d-block fs-5"></i>
                        </div>
                        <a href="{{ route('member.invest.log') }}" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-white mb-1">Semua Pesanan</h6>
                    <small class="text-muted">Pesanan saya</small>
                </div>
            </div>

            <!-- Bank Card - Update bagian ini di dashboard -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-credit-card text-muted"></i>
                        </div>
                        <a href="{{ route('member.dompet.detail') }}" class="text-gold text-decoration-none">
                            <small>Lihat info kartu →</small>
                        </a>
                    </div>
                    @php
                        $primaryWallet = auth()->user()->primaryWallet ?? auth()->user()->wallets()->first();
                    @endphp

                    @if ($primaryWallet)
                        @if ($primaryWallet->wallet_type === 'bank')
                            <h6 class="text-white mb-1">
                                {{ substr($primaryWallet->bank_account, 0, 4) }}***{{ substr($primaryWallet->bank_account, -3) }}
                            </h6>
                            <small class="text-muted">{{ $primaryWallet->bank_name }}</small>
                        @else
                            <h6 class="text-white mb-1">
                                {{ substr($primaryWallet->ewallet_number, 0, 4) }}***{{ substr($primaryWallet->ewallet_number, -3) }}
                            </h6>
                            <small class="text-muted">{{ $primaryWallet->ewallet_provider }}</small>
                        @endif
                    @else
                        <h6 class="text-white mb-1">Belum ada wallet</h6>
                        <small class="text-muted">Tambah wallet</small>
                    @endif
                </div>
            </div>

            <!-- Total Deposit -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-upload me-2"></i>
                        </div>
                        <a href="{{ route('member.deposit.log') }}" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR {{ number_format($totalDeposit, 0, ',', '.') }}</h6>
                    <small class="text-muted">Total deposit</small>
                </div>
            </div>

            <!-- Total Withdrawal -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-download me-2"></i>
                        </div>
                        <a href="{{ route('member.withdraw.log') }}" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR {{ number_format($totalWithdraw, 0, ',', '.') }}</h6>
                    <small class="text-muted">Total penarikan</small>
                </div>
            </div>

            <!-- Total Reward -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-gift text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR {{ number_format($totalRevenue, 0, ',', '.') }}</h6>
                    <small class="text-muted">Total Profit Harian</small>
                </div>
            </div>

            <!-- Total Commission -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-percent text-muted"></i>
                        </div>
                        <a href="{{ route('member.revenue.index') }}" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR {{ number_format($totalCommission, 0, ',', '.') }}</h6>
                    <small class="text-muted">Total Komisi</small>
                </div>
            </div>
        </div>
    </div>
@endsection
