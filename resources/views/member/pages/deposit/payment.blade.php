@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.deposit.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Detail Pembayaran</h5>
        </div>
        <div class="badge bg-warning text-dark">{{ $deposit->status }}</div>
    </div>

    <!-- Deposit Info Card -->
    <div class="card-dark p-3 mb-3">
        <div class="row">
            <div class="col-6">
                <small class="text-muted">Kode Deposit</small>
                <p class="text-white mb-0">{{ $deposit->code }}</p>
            </div>
            <div class="col-6">
                <small class="text-muted">Jumlah</small>
                <p class="text-gold mb-0 fw-bold">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="card-dark p-3 mb-3">
        <h6 class="text-white mb-3">
            <i class="bi bi-info-circle text-gold me-2"></i>
            Instruksi Pembayaran
        </h6>
        <ol class="text-muted mb-0" style="font-size: 14px;">
            <li>Transfer sesuai nominal yang tertera</li>
            <li>Upload bukti transfer di bawah ini</li>
            <li>Tunggu konfirmasi dari admin (maks 1x24 jam)</li>
            <li>Saldo akan otomatis masuk setelah dikonfirmasi</li>
        </ol>
    </div>

    <!-- Payment Methods -->
    @foreach ($paymentMethods as $methodKey => $method)
        @if ($deposit->method == $methodKey)
            <div class="card-dark p-3 mb-3">
                <h6 class="text-white mb-3">{{ $method['name'] }}</h6>

                @foreach ($method['accounts'] as $account)
                    <div class="payment-account mb-3 p-3"
                        style="background: rgba(255,255,255,0.05); border-radius: 8px; border-left: 3px solid #d4af37;">
                        @if ($account['type'] == 'QRIS')
                            <div class="text-center">
                                <h6 class="text-white mb-2">{{ $account['name'] }}</h6>
                                <div class="qr-code-placeholder bg-white p-3 rounded mx-auto"
                                    style="width: 200px; height: 200px; display: flex; align-items: center; justify-content: center;">
                                    <span class="text-dark">QR Code Here</span>
                                </div>
                                <small class="text-muted d-block mt-2">Scan QR Code untuk pembayaran</small>
                            </div>
                        @else
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-primary me-2">{{ $account['type'] }}</span>
                                        <h6 class="text-white mb-0">{{ $account['name'] }}</h6>
                                    </div>
                                    <p class="text-gold mb-0 fs-5 fw-bold">{{ $account['number'] }}</p>
                                    <small class="text-muted">a.n {{ $account['name'] }}</small>
                                </div>
                                <button class="btn btn-outline-gold btn-sm copy-btn" data-copy="{{ $account['number'] }}">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- Upload Proof Form -->
    <div class="card-dark p-3">
        <h6 class="text-white mb-3">Upload Bukti Pembayaran</h6>

        @if ($deposit->proof_url)
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                Bukti pembayaran sudah diupload. Menunggu verifikasi admin.
            </div>
            <div class="text-center mb-3">
                <img src="{{ asset('storage/' . $deposit->proof_url) }}" class="img-fluid rounded"
                    style="max-height: 300px;" alt="Bukti Pembayaran">
            </div>
        @else
            <form action="{{ route('member.deposit.upload-proof', $deposit->id) }}" method="POST"
                enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label text-white">Pilih File Bukti Transfer</label>
                    <input type="file" class="form-control form-control-dark" name="payment_proof" accept="image/*"
                        required>
                    <small class="text-muted">Format: JPG, PNG, JPEG (Max: 2MB)</small>
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="mb-3" style="display: none;">
                    <img id="preview" src="" class="img-fluid rounded" style="max-height: 300px;" alt="Preview">
                </div>

                <button type="submit" class="btn btn-gold w-100" id="uploadBtn">
                    <i class="bi bi-upload me-2"></i>
                    Upload Bukti Pembayaran
                </button>
            </form>
        @endif
    </div>

    <div class="mt-4" style="padding-bottom: 100px;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Copy account number functionality
            document.querySelectorAll('.copy-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const textToCopy = this.dataset.copy;
                    navigator.clipboard.writeText(textToCopy).then(() => {
                        const originalHtml = this.innerHTML;
                        this.innerHTML = '<i class="bi bi-check"></i>';
                        this.classList.remove('btn-outline-gold');
                        this.classList.add('btn-success');

                        setTimeout(() => {
                            this.innerHTML = originalHtml;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-gold');
                        }, 2000);
                    });
                });
            });

            // File input change handler for preview
            const fileInput = document.querySelector('input[name="payment_proof"]');
            const imagePreview = document.getElementById('imagePreview');
            const preview = document.getElementById('preview');

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }

            // Form submission with loading state
            const uploadForm = document.getElementById('uploadForm');
            const uploadBtn = document.getElementById('uploadBtn');

            if (uploadForm) {
                uploadForm.addEventListener('submit', function() {
                    uploadBtn.disabled = true;
                    uploadBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';
                });
            }
        });
    </script>

    <style>
        .payment-account:hover {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .copy-btn:hover {
            transform: scale(1.05);
        }

        .form-control-dark {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .form-control-dark:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #d4af37;
            box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25);
            color: white;
        }
    </style>
@endsection
