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
    <div class="card-dark p-3">
        <!-- Jumlah Deposit -->
        <div class="mb-4">
            <h6 class="text-white mb-3">Jumlah Deposit</h6>
            <div class="form-group">
                <input type="text" class="form-control form-control-dark" placeholder="Jumlah Deposit"
                    id="depositAmount">
            </div>
        </div>

        <!-- Amount Selection Grid -->
        <div class="amount-grid mb-4">
            <div class="row g-2">
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="220000">
                        Rp 220K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="480000">
                        Rp 480K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="100000">
                        Rp 100K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="50000">
                        Rp 50K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="200000">
                        Rp 200K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="775000">
                        Rp 775K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="1200000">
                        Rp 1200K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="2800000">
                        Rp 2800K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="5000000">
                        Rp 5000K
                    </button>
                </div>
                <div class="col-4">
                    <button class="btn btn-outline-gold w-100 amount-btn" data-amount="50000000">
                        Rp 50000K
                    </button>
                </div>
            </div>
        </div>

        <!-- Saluran Deposit -->
        <div class="mb-4">
            <h6 class="text-white mb-3">Saluran Deposit</h6>

            <!-- Payment Method 1 - Selected -->
            <div class="payment-method selected mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1">Wallet & QRIS & E-Bank</h6>
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
            <div class="payment-method mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white mb-1">Wallet & QRIS & E-Bank</h6>
                        <small class="text-muted">
                            Rentang Jumlah: <span class="text-gold">IDR</span> 50 K - <span class="text-gold">IDR</span>
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
            <button class="btn btn-gold w-100 btn-lg">
                Konfirmasi
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountButtons = document.querySelectorAll('.amount-btn');
            const depositInput = document.getElementById('depositAmount');
            const paymentMethods = document.querySelectorAll('.payment-method');

            // Handle amount button clicks
            amountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const amount = this.dataset.amount;
                    depositInput.value = new Intl.NumberFormat('id-ID').format(amount);

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
                    // Remove selected class from all methods
                    paymentMethods.forEach(m => m.classList.remove('selected'));

                    // Add selected class to clicked method
                    this.classList.add('selected');
                });
            });

            // Format number input
            depositInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value) {
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });
        });
    </script>
@endsection
