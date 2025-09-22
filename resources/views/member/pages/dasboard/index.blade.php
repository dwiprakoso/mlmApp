@extends('member.layouts.app')
@section('content')
    <!-- Hero -->
    <div class="hero-card">
        <!-- Tagline -->
        <div class="mb-3">
            <h5 class="mb-0 text-white">Solusi pintar untuk uang pintar.</h5>
        </div>

        <!-- Balance Card -->
        <div class="balance-card">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="d-flex align-items-center gap-1">
                        <i class="bi bi-wallet2 text-gold"></i>
                        <small class="text-muted">IDR</small>
                    </div>
                    <h3 class="fw-bold text-gold mb-0">75,000</h3>
                    <small class="text-muted">Saldo akun</small>
                </div>
                <div class="text-end">
                    <small class="text-gold">Dompetku →</small>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('member.deposit.index') }}" class="btn btn-gold btn-sm flex-fill">
                    <i class="bi bi-arrow-down me-1"></i>Deposit
                </a>
                <a href="{{ route('member.withdraw.index') }}" class="btn btn-outline-gold btn-sm flex-fill">
                    <i class="bi bi-arrow-up me-1"></i>Penarikan
                </a>
            </div>
        </div>
    </div>

    <!-- Bantuan & Perawatan -->
    <div class="content-section">
        <h6 class="text-white mb-3">Bantuan & Perawatan</h6>
        <div class="row text-center">
            <div class="col">
                <div class="circle-icon bg-primary text-white">
                    <i class="bi bi-headset"></i>
                </div>
                <small class="text-muted">Layanan Pelanggan Online</small>
            </div>
            <div class="col">
                <div class="circle-icon bg-success text-white">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <small class="text-muted">Saluran WhatsApp</small>
            </div>
            <div class="col">
                <div class="circle-icon bg-gold text-dark">
                    <i class="bi bi-question-lg"></i>
                </div>
                <small class="text-muted">Layanan Mandiri</small>
            </div>
        </div>
    </div>

    <!-- Kartu Undangan -->
    <div class="content-section">
        <div class="card-dark shadow-sm p-3">
            <h6 class="text-white mb-2">Kartu undangan saya</h6>
            <p class="small text-muted">Undangan yang berhasil akan memberi Anda hak untuk komisi <span
                    class="text-gold fw-bold">35%</span> pada setiap investasi yang dibuat.</p>

            <div class="row text-center mb-3">
                <div class="col-4">
                    <div class="invitation-box" onclick="generateQR('{{ $referralLink }}')">
                        <i class="bi bi-qr-code fs-3 text-gold"></i>
                        <small class="text-muted d-block mt-2">Undang Kode QR</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="invitation-box" onclick="copyToClipboard('{{ $referralLink }}')">
                        <i class="bi bi-link-45deg fs-3 text-gold"></i>
                        <small class="text-muted d-block mt-2">Undang tautan</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="invitation-box" onclick="copyToClipboard('{{ $referralCode }}')">
                        <h5 class="fw-bold mb-0 text-gold">{{ $referralCode }}</h5>
                        <small class="text-muted d-block mt-2">Undang Kode</small>
                    </div>
                </div>
            </div>
            <button class="btn btn-gold w-100">Lihat tim saya</button>
        </div>
    </div>


    <!-- Pesanan saya yang valid -->
    <div class="content-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white mb-0">Pesanan saya yang valid</h6>
            <small class="text-gold">Lihat semua</small>
        </div>

        <div class="row g-2">
            <div class="col-4">
                <div class="order-card">
                    <small class="text-muted">IDR</small>
                    <h6 class="text-success fw-bold mb-0">175,000.00</h6>
                    <small class="text-muted">Jumlah Pesanan</small>
                    <hr class="my-2 border-secondary">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Periode Kembali</small>
                        <small class="text-white">25 Hari</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Total pendapatan</small>
                        <small class="text-white">IDR 1,575,000.00</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Hari Kerja</small>
                        <small class="text-white">6 Hari</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kegiatan Terbaru -->
    <div class="content-section" style="padding-bottom: 100px;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white mb-0">Kegiatan Terbaru</h6>
            <small class="text-gold">Lihat semua</small>
        </div>

        <div class="activity-list">
            <div class="activity-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white mb-1">Investasi</h6>
                        <small class="text-muted">2025-09-15 09:04:15</small>
                    </div>
                    <div class="text-end">
                        <span class="text-danger">IDR -175,000</span>
                        <small class="text-muted d-block">IDR 75,000</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Bisa pakai toast notification atau alert sederhana
                alert('Disalin ke clipboard!');
            });
        }

        function generateQR(url) {
            // Buka QR generator di tab baru
            window.open(`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(url)}`,
                '_blank');
        }
    </script>
@endsection
