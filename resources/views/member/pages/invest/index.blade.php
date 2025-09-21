@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Investasi VIP</h5>
        </div>
    </div>

    <!-- Info Banner -->
    <div class="alert alert-info mb-3"
        style="background-color: var(--secondary-dark); border: 1px solid var(--border-color); color: var(--text-muted);">
        <small>Jagakeuangan - Indonesia Investments terlibat dengan regulator untuk membawa aset crypto ke arus
            utama.</small>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-4">
        <div class="row g-1">
            <div class="col-4">
                <button class="btn btn-gold w-100 tab-btn active" data-tab="rencana">
                    <small>Rencana Lanjutan</small>
                    <span class="badge bg-danger position-absolute top-0 end-0 translate-middle-y"
                        style="font-size: 8px;">HOT</span>
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-gold w-100 tab-btn" data-tab="pendapatan">
                    <small>Pendapatan Stabil</small>
                </button>
            </div>
            <div class="col-4">
                <button class="btn btn-outline-gold w-100 tab-btn" data-tab="keuntungan">
                    <small>Keuntungan VIP</small>
                </button>
            </div>
        </div>
    </div>

    <!-- VIP Plans Container -->
    <div id="vip-plans-container">
        <!-- Rencana Lanjutan Tab -->
        <div class="tab-content active" id="rencana-tab">
            <!-- VIP Plan 1 -->
            <div class="card-dark p-3 mb-3">
                <h6 class="text-white mb-3">Acara VIP</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Setiap Harga</small>
                            <h6 class="text-gold mb-0">IDR 100,000</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Pengembalian Periodik</small>
                            <h6 class="text-gold mb-0">IDR 35,000</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Periode Kembali</small>
                            <h6 class="text-gold mb-0">17 Hari</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Pendapatan</small>
                            <h6 class="text-gold mb-0">IDR 595,000</h6>
                        </div>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-gold circle-icon me-2" style="width: 30px; height: 30px; font-size: 14px;">
                            <i class="bi bi-gem text-dark"></i>
                        </div>
                        <small class="text-white">Butuh VIP1</small>
                    </div>
                    <button class="btn btn-gold btn-sm px-3">
                        <small>Investasi Sekarang</small>
                    </button>
                </div>
            </div>

            <!-- VIP Plan 2 -->
            <div class="card-dark p-3 mb-3">
                <h6 class="text-white mb-3">Acara VIP</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Setiap Harga</small>
                            <h6 class="text-gold mb-0">IDR 220,000</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Pengembalian Periodik</small>
                            <h6 class="text-gold mb-0">IDR 79,200</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Periode Kembali</small>
                            <h6 class="text-gold mb-0">17 Hari</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Pendapatan</small>
                            <h6 class="text-gold mb-0">IDR 1,346,400</h6>
                        </div>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-gold circle-icon me-2" style="width: 30px; height: 30px; font-size: 14px;">
                            <i class="bi bi-gem text-dark"></i>
                        </div>
                        <small class="text-white">Butuh VIP1</small>
                    </div>
                    <button class="btn btn-gold btn-sm px-3">
                        <small>Investasi Sekarang</small>
                    </button>
                </div>
            </div>
        </div>

        <!-- Pendapatan Stabil Tab -->
        <div class="tab-content" id="pendapatan-tab">
            <div class="card-dark p-3 mb-3">
                <h6 class="text-white mb-3">Paket Stabil Premium</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Setiap Harga</small>
                            <h6 class="text-gold mb-0">IDR 500,000</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Pengembalian Periodik</small>
                            <h6 class="text-gold mb-0">IDR 25,000</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Periode Kembali</small>
                            <h6 class="text-gold mb-0">30 Hari</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Pendapatan</small>
                            <h6 class="text-gold mb-0">IDR 750,000</h6>
                        </div>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-gold circle-icon me-2" style="width: 30px; height: 30px; font-size: 14px;">
                            <i class="bi bi-shield-check text-dark"></i>
                        </div>
                        <small class="text-white">Risiko Rendah</small>
                    </div>
                    <button class="btn btn-gold btn-sm px-3">
                        <small>Investasi Sekarang</small>
                    </button>
                </div>
            </div>
        </div>

        <!-- Keuntungan VIP Tab -->
        <div class="tab-content" id="keuntungan-tab">
            <div class="card-dark p-3 mb-3">
                <h6 class="text-white mb-3">VIP Elite Package</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Setiap Harga</small>
                            <h6 class="text-gold mb-0">IDR 1,000,000</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Pengembalian Periodik</small>
                            <h6 class="text-gold mb-0">IDR 120,000</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <small class="text-muted">Periode Kembali</small>
                            <h6 class="text-gold mb-0">15 Hari</h6>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Total Pendapatan</small>
                            <h6 class="text-gold mb-0">IDR 1,800,000</h6>
                        </div>
                    </div>
                </div>
                <hr style="border-color: var(--border-color);">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-gold circle-icon me-2" style="width: 30px; height: 30px; font-size: 14px;">
                            <i class="bi bi-star-fill text-dark"></i>
                        </div>
                        <small class="text-white">VIP Elite</small>
                    </div>
                    <button class="btn btn-gold btn-sm px-3">
                        <small>Investasi Sekarang</small>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('btn-gold', 'active');
                        btn.classList.add('btn-outline-gold');
                    });

                    // Add active class to clicked button
                    this.classList.remove('btn-outline-gold');
                    this.classList.add('btn-gold', 'active');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show target tab content
                    document.getElementById(targetTab + '-tab').classList.add('active');
                });
            });
        });
    </script>
@endsection
