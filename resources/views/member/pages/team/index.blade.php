@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center mb-3" style="padding: 0 20px 10px 20px;">
        <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h5 class="text-white mb-0">Tim Saya</h5>
    </div>

    <!-- Info Referral Code -->
    <div class="content-section">
        <!-- Team Statistics -->
        <h6 class="text-white mb-3">Statistik Tim</h6>
        <div class="row text-center mb-4">
            <div class="col">
                <div class="circle-icon bg-gold text-dark">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h5 class="text-gold fw-bold mb-0">{{ $referralUsages->count() }}</h5>
                <small class="text-muted">Total Anggota</small>
            </div>
        </div>
        <div class="row text-center mb-4">
            <div class="col">
                <div class="circle-icon bg-gold text-dark">
                    <i class="bi bi-coin"></i>
                </div>
                <h5 class="text-gold fw-bold mb-0">{{ $depositedMembersCount }}</h5>
                <small class="text-muted">Point Diperoleh</small>
            </div>
        </div>

        <!-- Team Members List -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-white mb-0">Anggota Tim</h6>
            @if ($referralUsages->count() > 0)
                <small class="text-muted">{{ $referralUsages->count() }} orang</small>
            @endif
        </div>

        @if ($referralUsages->count() > 0)
            @foreach ($referralUsages as $usage)
                <div class="order-card mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="text-white mb-1">{{ $usage->referee->phone ?? 'Phone not available' }}</h6>
                            </div>
                        </div>
                        <div class="text-end">
                            <small class="text-gold fw-bold">{{ $usage->created_at->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $usage->created_at->format('H:i') }}</small>
                        </div>
                        @if ($usage->is_deposit)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle-fill"></i> Deposit
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-clock-fill"></i> Belum Deposit
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="card-dark shadow-sm p-4">
                <div class="text-center">
                    <i class="bi bi-people fs-1 text-muted mb-3 d-block"></i>
                    <h6 class="text-white mb-2">Belum Ada Anggota Tim</h6>
                    <p class="text-muted mb-3 small">Bagikan kode referral Anda untuk mulai membangun tim dan mendapatkan
                        komisi <span class="text-gold fw-bold">{{ $commissionRate }}%</span> dari setiap investasi mereka.
                    </p>

                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="invitation-box" onclick="generateQR('{{ $currentUser->refferal_code }}')">
                                <i class="bi bi-qr-code fs-3 text-gold"></i>
                                <small class="text-muted d-block mt-2">QR Code</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="invitation-box" onclick="shareReferral()">
                                <i class="bi bi-share fs-3 text-gold"></i>
                                <small class="text-muted d-block mt-2">Bagikan</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="invitation-box" onclick="copyReferralCode()">
                                <i class="bi bi-copy fs-3 text-gold"></i>
                                <small class="text-muted d-block mt-2">Salin Kode</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyReferralCode() {
            const referralCode = '{{ $currentUser->refferal_code }}';
            navigator.clipboard.writeText(referralCode).then(function() {
                alert('Kode referral disalin ke clipboard!');
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Gagal menyalin kode referral');
            });
        }

        function generateQR(code) {
            window.open(`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(code)}`,
                '_blank');
        }

        function shareReferral() {
            const text =
                `Bergabunglah dengan saya di platform investasi terpercaya! Gunakan kode referral: {{ $currentUser->refferal_code }}`;

            if (navigator.share) {
                navigator.share({
                    title: 'Kode Referral',
                    text: text
                });
            } else {
                copyToClipboard(text);
                alert('Teks referral disalin ke clipboard!');
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
        }
    </script>
@endsection
