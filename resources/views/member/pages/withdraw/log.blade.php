@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-center mb-4 position-relative">
        <a href="{{ route('member.withdraw.index') }}" class="position-absolute start-0 text-gold">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h5 class="text-white mb-0">Catatan Penarikan</h5>
    </div>

    <!-- Deposit Log List -->
    <div class="deposit-log-container" style="padding-bottom: 100px;">

        <!-- Log Item 1 - Dalam Pembayaran -->
        <div class="log-item">
            <div class="log-content">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-muted">Penarikan Uang</span>
                    <div class="text-end">
                        <small class="text-muted">IDR</small>
                        <span class="text-white fw-bold">50,000,000</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Buat Waktu</span>
                    <span class="text-white">11:46 AM, 21 Sep 25</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-warning text-dark">Dalam Pembayaran</span>
                </div>
            </div>
        </div>

    </div>
@endsection
