@extends('member.layouts.app')
@section('content')
    <!-- Hero Section dengan Balance -->
    <div class="hero-card mb-0">

        <!-- Balance Card -->
        <div class="balance-card">
            <div class="text-center mb-3">
                <h2 class="text-white mb-1">IDR 75,000</h2>
                <p class="text-muted mb-0">Saldo akun</p>
            </div>

            <!-- Action Buttons -->
            <div class="row g-2">
                <div class="col-6">
                    <a href="{{ route('member.deposit.index') }}"
                        class="btn btn-success w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-download me-2"></i>
                        <span>Deposit</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('member.withdraw.index') }}"
                        class="btn btn-warning w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-upload me-2"></i>
                        <span>Penarikan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats Grid -->
    <div class="content-section">
        <div class="row g-3">
            <!-- Messages Card -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-chat-left-text text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-white mb-1">Semua Pesanan</h6>
                    <small class="text-muted">Pesanan saya</small>
                </div>
            </div>

            <!-- Bank Card -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-credit-card text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat info kartu →</small>
                        </a>
                    </div>
                    <h6 class="text-white mb-1">0821***778</h6>
                    <small class="text-muted">Kartu bank</small>
                </div>
            </div>

            <!-- Deposit Balance -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-wallet text-muted"></i>
                        </div>
                        <a href="{{ route('member.deposit.log') }}" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR 75,000</h6>
                    <small class="text-muted">Saldo deposit</small>
                </div>
            </div>

            <!-- Withdrawal Balance -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-calendar-event text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR 0</h6>
                    <small class="text-muted">Saldo penarikan</small>
                </div>
            </div>

            <!-- Total Deposit -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-down text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR 400,000</h6>
                    <small class="text-muted">Total deposit</small>
                </div>
            </div>

            <!-- Total Withdrawal -->
            <div class="col-6">
                <div class="card-dark p-3 h-100">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-arrow-up text-muted"></i>
                        </div>
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR 80,000</h6>
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
                    <h6 class="text-gold mb-1">IDR 0</h6>
                    <small class="text-muted">Total hadiah</small>
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
                        <a href="#" class="text-gold text-decoration-none">
                            <small>Lihat Log →</small>
                        </a>
                    </div>
                    <h6 class="text-gold mb-1">IDR 0</h6>
                    <small class="text-muted">Total komisi</small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row g-3 mt-3 mb-5" style="margin-bottom: 120px !important;">
            <div class="col-3">
                <a href="{{ route('member.invest.index') }}" class="text-decoration-none">
                    <div class="text-center">
                        <div class="bg-gold circle-icon mb-2">
                            <i class="bi bi-gem text-dark"></i>
                        </div>
                        <small class="text-white">VIP</small>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('member.invest.index') }}" class="text-decoration-none">
                    <div class="text-center">
                        <div class="bg-gold circle-icon mb-2">
                            <i class="bi bi-graph-up text-dark"></i>
                        </div>
                        <small class="text-white">Investasi</small>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('member.invest.index') }}" class="text-decoration-none">
                    <div class="text-center">
                        <div class="bg-gold circle-icon mb-2">
                            <i class="bi bi-gift text-dark"></i>
                        </div>
                        <small class="text-white">Bonus</small>
                    </div>
                </a>
            </div>
            <div class="col-3">
                <a href="{{ route('member.invest.index') }}" class="text-decoration-none">
                    <div class="text-center">
                        <div class="bg-gold circle-icon mb-2">
                            <i class="bi bi-people text-dark"></i>
                        </div>
                        <small class="text-white">Referral</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
