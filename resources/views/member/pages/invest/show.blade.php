@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.invest.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Detail Investasi</h5>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success mb-3" style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-3" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status Banner -->
    <div class="alert mb-3 
        @if ($transaction->status === 'success') alert-success
        @elseif($transaction->status === 'failed') alert-danger
        @elseif($transaction->status === 'waiting_confirmation') alert-warning
        @else alert-info @endif"
        style="border-radius: 10px;">
        <div class="d-flex align-items-center">
            <i
                class="bi 
                @if ($transaction->status === 'success') bi-check-circle-fill
                @elseif($transaction->status === 'failed') bi-x-circle-fill
                @elseif($transaction->status === 'waiting_confirmation') bi-clock-fill
                @else bi-info-circle-fill @endif me-2"></i>
            <div>
                <strong>Status:
                    @if ($transaction->status === 'pending')
                        Menunggu Pembayaran
                    @elseif($transaction->status === 'waiting_confirmation')
                        Menunggu Konfirmasi
                    @elseif($transaction->status === 'success')
                        Berhasil
                    @elseif($transaction->status === 'failed')
                        Gagal
                    @endif
                </strong>
                <br>
                <small>
                    @if ($transaction->status === 'pending')
                        Silakan lakukan pembayaran sesuai dengan nominal yang tertera
                    @elseif($transaction->status === 'waiting_confirmation')
                        Pembayaran Anda sedang dalam proses verifikasi
                    @elseif($transaction->status === 'success')
                        Investasi Anda telah berhasil diproses
                    @elseif($transaction->status === 'failed')
                        Terjadi kesalahan dalam proses pembayaran
                    @endif
                </small>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="card-dark p-4 mb-3">
        <h6 class="text-white mb-3">
            <i class="bi bi-receipt me-2"></i>
            Detail Transaksi
        </h6>

        <div class="row mb-3">
            <div class="col-4">
                <small class="text-muted">Reference</small>
                <h6 class="text-gold mb-0">{{ $transaction->reference }}</h6>
            </div>
            <div class="col-4">
                <small class="text-muted">Jumlah</small>
                <h6 class="text-gold mb-0">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</h6>
            </div>
            <div class="col-4">
                <small class="text-muted">Tanggal</small>
                <h6 class="text-gold mb-0">{{ $transaction->created_at->format('d/m/Y') }}</h6>
            </div>
        </div>

        @if ($transaction->product)
            <hr style="border-color: var(--border-color);">
            <h6 class="text-white mb-3">
                <i class="bi bi-gem me-2"></i>
                Detail Produk
            </h6>

            <div class="row">
                <div class="col-6">
                    <div class="mb-2">
                        <small class="text-muted">Nama Produk</small>
                        <p class="text-white mb-0">{{ $transaction->product->name }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Tipe</small>
                        <p class="text-white mb-0">{{ $transaction->product->type }}</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-2">
                        <small class="text-muted">Durasi</small>
                        <p class="text-white mb-0">{{ $transaction->product->duration }} Hari</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Harga</small>
                        <p class="text-gold mb-0">IDR {{ number_format($transaction->product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            @if ($transaction->product->description)
                <div class="mb-0">
                    <small class="text-muted">Deskripsi</small>
                    <p class="text-white mb-0">{{ $transaction->product->description }}</p>
                </div>
            @endif
        @endif
    </div>

    <!-- Payment Information -->
    @if ($transaction->status === 'pending')
        <div class="card-dark p-4 mb-3">
            <h6 class="text-white mb-3">
                <i class="bi bi-credit-card me-2"></i>
                Informasi Pembayaran
            </h6>

            <div class="alert alert-info mb-3"
                style="background-color: var(--info-bg, #cce7ff); color: var(--info-text, #0066cc);">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    Silakan transfer sesuai nominal yang tertera ke rekening berikut:
                </small>
            </div>

            <!-- Bank Information -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="p-3 rounded" style="background-color: var(--tertiary-dark, #1a1a1a);">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Bank</small>
                                <h6 class="text-white mb-0">BCA</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">No. Rekening</small>
                                <h6 class="text-gold mb-0" id="bankAccount">1234567890</h6>
                                <button class="btn btn-sm btn-outline-gold" onclick="copyBankAccount()">
                                    <i class="bi bi-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                        <hr style="border-color: var(--border-color); margin: 1rem 0;">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Atas Nama</small>
                                <h6 class="text-white mb-0">JAGAKEUANGAN</h6>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Nominal Transfer</small>
                                <h6 class="text-gold mb-0" id="transferAmount">IDR
                                    {{ number_format($transaction->amount, 0, ',', '.') }}</h6>
                                <button class="btn btn-sm btn-outline-gold" onclick="copyAmount()">
                                    <i class="bi bi-copy"></i> Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Payment Proof -->
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('member.invest.upload_payment_proof') }}" method="POST"
                        enctype="multipart/form-data" id="paymentForm">
                        @csrf
                        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                        <div class="mb-3">
                            <label for="payment_proof" class="form-label text-white">
                                <i class="bi bi-upload me-1"></i>
                                Upload Bukti Transfer
                            </label>
                            <input type="file" class="form-control" id="payment_proof" name="payment_proof"
                                accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, JPEG. Maksimal 5MB</small>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label text-white">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="bank_transfer">Transfer Bank</option>
                                <option value="mobile_banking">Mobile Banking</option>
                                <option value="atm">ATM</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-gold w-100" id="submitBtn">
                            <i class="bi bi-check-circle me-2"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Proof Display -->
    @if ($transaction->payment_proof)
        <div class="card-dark p-4 mb-3">
            <h6 class="text-white mb-3">
                <i class="bi bi-image me-2"></i>
                Bukti Pembayaran
            </h6>

            <div class="text-center">
                @php
                    $imagePath = $transaction->payment_proof;
                    // Debug path
                    $fullPath = storage_path('app/public/' . $imagePath);
                    $publicUrl = asset('storage/' . $imagePath);
                @endphp

                @if (file_exists($fullPath))
                    <img src="{{ $publicUrl }}" alt="Bukti Pembayaran" class="img-fluid rounded payment-proof-image"
                        style="max-height: 300px; cursor: pointer;" onclick="showImageModal(this.src)"
                        onError="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.style.filter='grayscale(100%)'; this.nextElementSibling.style.display='block';">
                    <div style="display: none;" class="mt-2">
                        <small class="text-danger">⚠️ Gambar tidak dapat dimuat</small>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <small>File bukti pembayaran tidak ditemukan</small>
                        <br>
                        <small class="text-muted">Path: {{ $imagePath }}</small>
                    </div>
                @endif

                <br>
                <small class="text-muted mt-2 d-block">
                    Metode: {{ ucwords(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}
                </small>
                <small class="text-muted">
                    Upload: {{ $transaction->updated_at->format('d M Y, H:i') }}
                </small>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="row g-2">
        <div class="col-6">
            <a href="{{ route('member.invest.index') }}" class="btn btn-outline-gold w-100">
                <i class="bi bi-arrow-left me-2"></i>
                <small>Kembali</small>
            </a>
        </div>
        <div class="col-6">
            <a href="{{ route('member.invest.log') }}" class="btn btn-gold w-100">
                <i class="bi bi-clock-history me-2"></i>
                <small>Riwayat</small>
            </a>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark">
                <div class="modal-header border-gold">
                    <h6 class="modal-title text-white">Bukti Pembayaran</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <script>
        // Copy bank account number
        function copyBankAccount() {
            const accountNumber = '1234567890';
            navigator.clipboard.writeText(accountNumber).then(function() {
                showToast('Nomor rekening berhasil disalin!');
            });
        }

        // Copy transfer amount
        function copyAmount() {
            const amount = '{{ $transaction->amount }}';
            navigator.clipboard.writeText(amount).then(function() {
                showToast('Nominal transfer berhasil disalin!');
            });
        }

        // Show image in modal
        function showImageModal(src) {
            document.getElementById('modalImage').src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }

        // Show toast notification
        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => document.body.removeChild(toast), 300);
            }, 3000);
        }

        // File upload preview and validation
        document.getElementById('payment_proof')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    e.target.value = '';
                    return;
                }

                // Show file name and preview
                const fileName = file.name;

                // Remove existing file info
                const existingInfo = e.target.parentNode.querySelector('.file-info');
                if (existingInfo) existingInfo.remove();

                // Add new file info
                const fileInfo = document.createElement('small');
                fileInfo.className = 'text-muted mt-1 file-info d-block';
                fileInfo.innerHTML = `<i class="bi bi-file-earmark-image me-1"></i>File terpilih: ${fileName}`;
                e.target.parentNode.appendChild(fileInfo);
            }
        });

        // Form submission handling
        document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2" role="status"></span><small>Mengupload...</small>';
            submitBtn.disabled = true;
        });

        // Auto dismiss alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>

    <style>
        .card-dark {
            background-color: var(--secondary-dark, #2c2c2c);
            border: 1px solid var(--border-color, #444);
            border-radius: 10px;
        }

        .btn-gold {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
            font-weight: 500;
        }

        .btn-outline-gold {
            border-color: var(--gold-color, #ffd700);
            color: var(--gold-color, #ffd700);
        }

        .text-gold {
            color: var(--gold-color, #ffd700) !important;
        }

        .border-gold {
            border-color: var(--gold-color, #ffd700) !important;
        }

        .form-control,
        .form-select {
            background-color: var(--tertiary-dark, #1a1a1a);
            border-color: var(--border-color, #444);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--tertiary-dark, #1a1a1a);
            border-color: var(--gold-color, #ffd700);
            color: #fff;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }

        .payment-proof-image {
            border: 2px solid var(--border-color, #444);
            transition: all 0.3s ease;
        }

        .payment-proof-image:hover {
            border-color: var(--gold-color, #ffd700);
            transform: scale(1.05);
        }

        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            background-color: var(--secondary-dark, #2c2c2c);
            border: 1px solid var(--gold-color, #ffd700);
            border-radius: 8px;
            padding: 12px 16px;
            color: #fff;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffeaa7;
            color: #856404;
        }

        .alert-info {
            background-color: #cce7ff;
            border-color: #74c0fc;
            color: #0c5460;
        }

        @media (max-width: 768px) {
            .col-4 {
                margin-bottom: 0.5rem;
            }

            .toast-notification {
                right: 10px;
                left: 10px;
                top: 10px;
            }
        }
    </style>
@endsection
