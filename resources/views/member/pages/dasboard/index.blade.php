@extends('member.layouts.app')
@section('content')
    <!-- Hero -->
    <div class="hero-card">
        <!-- Tagline -->
        <div class="mb-3">
            <h5 class="mb-0 text-white">{{ $headerText }}</h5>
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
    <!-- Bantuan & Perawatan -->
    <div class="content-section">
        <h6 class="text-white mb-3">Bantuan & Perawatan</h6>
        <div class="row text-center">
            <div class="col">
                <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="text-decoration-none">
                    <div class="circle-icon bg-primary text-white">
                        <i class="bi bi-headset"></i>
                    </div>
                    <small class="text-muted">Layanan Pelanggan Online</small>
                </a>
            </div>
            <div class="col">
                <a href="{{ $whatsappChannel }}" target="_blank" class="text-decoration-none">
                    <div class="circle-icon bg-success text-white">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <small class="text-muted">Saluran WhatsApp</small>
                </a>
            </div>
            <div class="col">
                <a href="{{ route('member.dashboard.index') }}" class="text-decoration-none">
                    <div class="circle-icon bg-gold text-dark">
                        <i class="bi bi-question-lg"></i>
                    </div>
                    <small class="text-muted">Layanan Mandiri</small>
                </a>
            </div>
        </div>
    </div>

    <!-- Kartu Undangan -->
    <div class="content-section mb-5 pb-4">
        <div class="card-dark shadow-sm p-3">
            <h6 class="text-white mb-2">Kartu undangan saya</h6>
            <p class="small text-muted">Undangan yang berhasil akan memberi Anda hak untuk komisi <span
                    class="text-gold fw-bold">{{ $commissionRate }}%</span> pada setiap investasi yang dibuat.</p>

            <div class="row text-center mb-3">
                <div class="col-4">
                    <div class="invitation-box d-flex flex-column align-items-center justify-content-center"
                        onclick="generateQR('{{ $referralLink }}')">
                        <i class="bi bi-qr-code fs-3 text-gold"></i>
                        <small class="text-muted mt-2" style="font-size: 0.7rem; line-height: 1.2;">Undang Kode QR</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="invitation-box d-flex flex-column align-items-center justify-content-center"
                        onclick="copyToClipboard('{{ $referralLink }}')">
                        <i class="bi bi-link-45deg fs-3 text-gold"></i>
                        <small class="text-muted mt-2" style="font-size: 0.7rem; line-height: 1.2;">Undang tautan</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="invitation-box d-flex flex-column align-items-center justify-content-center"
                        onclick="copyToClipboard('{{ $referralCode }}')">
                        <div class="text-gold fw-bold mb-1"
                            style="font-size: 0.9rem; word-break: break-all; line-height: 1.1;">{{ $referralCode }}</div>
                        <small class="text-muted" style="font-size: 0.7rem; line-height: 1.2;">Undang Kode</small>
                    </div>
                </div>
            </div>
            <a href="{{ route('member.team.index') }}" class="btn btn-gold w-100">
                <i class="bi bi-people me-2"></i>Lihat Tim Saya
            </a>
        </div>
    </div>

    <style>
        .invitation-box {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 80px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .invitation-box:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .invitation-box:active {
            transform: translateY(0);
        }

        /* Memastikan semua kotak memiliki lebar yang sama */
        .row .col-4 {
            padding-left: 4px;
            padding-right: 4px;
        }

        /* Responsif untuk mobile */
        @media (max-width: 576px) {
            .invitation-box {
                min-height: 70px;
                padding: 8px 4px;
            }

            .invitation-box i {
                font-size: 1.5rem !important;
            }

            .invitation-box .text-gold {
                font-size: 0.8rem !important;
            }

            .invitation-box small {
                font-size: 0.65rem !important;
            }
        }
    </style>

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
