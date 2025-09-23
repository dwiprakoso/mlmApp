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
                    <h3 class="fw-bold text-gold mb-0">{{ number_format($balance, 0, ',', '.') }}</h3>
                    <small class="text-muted">Saldo akun</small>
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
    <div class="content-section mb-5 pb-4">
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
