@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Deposit</h5>
        </div>
        <a href="{{ route('member.deposit.log') }}" class="text-gold text-decoration-none">
            <small>Log</small>
        </a>
    </div>

    <!-- Main Content Card -->
    <form action="{{ route('member.deposit.store') }}" method="POST" id="depositForm">
        @csrf
        <input type="hidden" name="_token_deposit" value="{{ $token }}">

        <div class="card-dark p-3">
            <!-- Success Message -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Jumlah Deposit -->
            <div class="mb-4">
                <h6 class="text-white mb-3">Jumlah Deposit</h6>
                <div class="form-group">
                    <input type="text" class="form-control form-control-dark" placeholder="Jumlah Deposit"
                        id="depositAmount" required>
                    <input type="hidden" id="rawAmount" name="amount">
                </div>
            </div>

            <!-- Amount Selection Grid -->
            <div class="amount-grid mb-4">
                <div class="row g-2">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="50000">
                            Rp 50K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="100000">
                            Rp 100K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="200000">
                            Rp 200K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="480000">
                            Rp 480K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="775000">
                            Rp 775K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="1200000">
                            Rp 1200K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="2800000">
                            Rp 2800K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="5000000">
                            Rp 5000K
                        </button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-gold w-100 amount-btn" data-amount="50000000">
                            Rp 50000K
                        </button>
                    </div>
                </div>
            </div>

            <!-- Saluran Deposit -->
            <div class="mb-4">
                <h6 class="text-white mb-3">Saluran Deposit</h6>

                <!-- Payment Method 1 - Selected -->
                <div class="payment-method selected mb-2" data-method="wallet_qris">
                    <input type="radio" name="payment_method" value="wallet_qris" checked style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">QRIS</h6>
                            <small class="text-muted">
                                Rentang Jumlah: <span class="text-gold">IDR</span> 50 K - <span class="text-gold">IDR</span>
                                50,000 K
                            </small>
                        </div>
                        <div class="payment-check">
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Payment Method 2 -->
                <div class="payment-method mb-2" data-method="bank_transfer">
                    <input type="radio" name="payment_method" value="bank_transfer" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white mb-1">Bank Transfer</h6>
                            <small class="text-muted">
                                Rentang Jumlah: <span class="text-gold">IDR</span> 50 K - <span
                                    class="text-gold">IDR</span>
                                50,000 K
                            </small>
                        </div>
                        <div class="payment-radio">
                            <div class="radio-circle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konfirmasi Button -->
            <div class="mt-4" style="padding-bottom: 100px;">
                <button type="submit" class="btn btn-gold w-100 btn-lg" id="submitBtn">
                    Konfirmasi
                </button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountButtons = document.querySelectorAll('.amount-btn');
            const depositInput = document.getElementById('depositAmount');
            const rawAmountInput = document.getElementById('rawAmount');
            const paymentMethods = document.querySelectorAll('.payment-method');
            const form = document.getElementById('depositForm');
            const submitBtn = document.getElementById('submitBtn');

            let isSubmitting = false;

            // Handle amount button clicks
            amountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (isSubmitting) return;

                    const amount = this.dataset.amount;
                    depositInput.value = new Intl.NumberFormat('id-ID').format(amount);
                    rawAmountInput.value = amount;

                    // Remove active class from all buttons
                    amountButtons.forEach(btn => btn.classList.remove('btn-gold'));
                    amountButtons.forEach(btn => btn.classList.add('btn-outline-gold'));

                    // Add active class to clicked button
                    this.classList.remove('btn-outline-gold');
                    this.classList.add('btn-gold');
                });
            });

            // Handle payment method selection
            paymentMethods.forEach(method => {
                method.addEventListener('click', function() {
                    if (isSubmitting) return;

                    paymentMethods.forEach(m => {
                        m.classList.remove('selected');
                        m.querySelector('input[type="radio"]').checked = false;
                    });
                    this.classList.add('selected');
                    this.querySelector('input[type="radio"]').checked = true;
                });
            });

            // Format number input
            depositInput.addEventListener('input', function() {
                if (isSubmitting) return;

                let value = this.value.replace(/[^0-9]/g, '');
                if (value) {
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                    rawAmountInput.value = value;
                } else {
                    rawAmountInput.value = '';
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                // Cek apakah sedang submit
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                const rawValue = rawAmountInput.value;

                // Validasi amount
                if (!rawValue || rawValue < 50000 || rawValue > 50000000) {
                    e.preventDefault();
                    alert('Jumlah deposit harus antara Rp 50.000 - Rp 50.000.000');
                    return false;
                }

                // Set flag submitting
                isSubmitting = true;

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                // Disable semua input dan button
                const allInputs = form.querySelectorAll('input, button');
                allInputs.forEach(input => {
                    if (input !== submitBtn) {
                        input.disabled = true;
                    }
                });

                // Disable amount buttons
                amountButtons.forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.6';
                });

                // Disable payment methods
                paymentMethods.forEach(method => {
                    method.style.pointerEvents = 'none';
                    method.style.opacity = '0.6';
                });

                return true;
            });

            // Prevent back button resubmission
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            // Detect browser back/forward
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    // Reset form state jika user kembali dengan back button
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Konfirmasi';

                    const allInputs = form.querySelectorAll('input, button');
                    allInputs.forEach(input => input.disabled = false);

                    amountButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.style.opacity = '1';
                    });

                    paymentMethods.forEach(method => {
                        method.style.pointerEvents = 'auto';
                        method.style.opacity = '1';
                    });
                }
            });
        });
    </script>
@endsection
