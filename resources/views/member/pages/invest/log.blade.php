@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-center mb-4 position-relative">
        <a href="{{ route('member.dompet.index') }}" class="text-white position-absolute start-0">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h5 class="text-white mb-0">Pesanan saya</h5>
    </div>

    <!-- Investment Orders List -->
    <div class="investment-log-container">
        <!-- Order 1 - Active/Pendapatan -->
        <div class="log-item">
            <div class="log-content">
                <!-- Header dengan Amount dan Status -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white mb-1">IDR 175,000.00</h6>
                        <small class="text-muted">Jumlah Pesanan</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success">Pendapatan</span>
                        <div class="mt-1">
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>

                <!-- Progress Dots -->
                <div class="progress-dots mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="dot-container">
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                    </div>
                </div>

                <!-- Investment Details -->
                <div class="investment-details">
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Bagian investasi</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">1 bagian</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Jenis Produk</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">Rencana Lanjutan</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Periode Kembali</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">25 Hari</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Nilai saat ini</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">378,000.00</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Pendapatan yang ditetapkan</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">0.00</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Buat Waktu</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">09:04 AM, 15 Sep 25</small>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-6">
                            <small class="text-muted">Waktu kadaluwarsa</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">09:04 AM, 10 Oct 25</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order 2 - Active/Pendapatan -->
        <div class="log-item">
            <div class="log-content">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white mb-1">IDR 50,000.00</h6>
                        <small class="text-muted">Jumlah Pesanan</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success">Pendapatan</span>
                        <div class="mt-1">
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>

                <!-- Progress Dots -->
                <div class="progress-dots mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="dot-container">
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot active"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                            <div class="progress-dot"></div>
                        </div>
                    </div>
                </div>

                <div class="investment-details">
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Bagian investasi</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">1 bagian</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Jenis Produk</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">Rencana Lanjutan</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Periode Kembali</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">25 Hari</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Nilai saat ini</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">105,000.00</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Pendapatan yang ditetapkan</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">0.00</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Buat Waktu</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">15:51 PM, 14 Sep 25</small>
                        </div>
                    </div>
                    <div class="row g-0">
                        <div class="col-6">
                            <small class="text-muted">Waktu kadaluwarsa</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">15:51 PM, 09 Oct 25</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order 3 - Completed/Selesai -->
        <div class="log-item">
            <div class="log-content">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-white mb-1">IDR 50,000.00</h6>
                        <small class="text-muted">Jumlah Pesanan</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-secondary">Selesai</span>
                        <div class="mt-1">
                            <small class="text-muted">Status</small>
                        </div>
                    </div>
                </div>

                <!-- Progress Dots - Completed -->
                <div class="progress-dots mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="dot-container">
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                            <div class="progress-dot completed"></div>
                        </div>
                    </div>
                </div>

                <div class="investment-details">
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Bagian investasi</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">1 bagian</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Jenis Produk</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">Keuntungan VIP</small>
                        </div>
                    </div>
                    <div class="row g-0 mb-2">
                        <div class="col-6">
                            <small class="text-muted">Periode Kembali</small>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-white">1 Hari</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Spacing -->
    <div style="height: 100px;"></div>

    <style>

    </style>
@endsection
