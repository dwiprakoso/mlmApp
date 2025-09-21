@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Penarikan</h5>
        </div>
        <div>
            <a href="{{ route('member.withdraw.log') }}" class="text-gold text-decoration-none">
                <small>Log</small>
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Withdrawal -->
    <form action="{{ route('member.withdraw.store') }}" method="POST" id="withdrawalForm">
        @csrf

        <!-- Main Content Card -->
        <div class="card-dark p-3 mb-4">
            <!-- Wallet Selection -->
            <div class="mb-3">
                <label class="form-label text-white">Pilih Wallet untuk Penarikan</label>
                <select name="wallet_id" class="form-select bg-dark text-white border-secondary" id="walletSelect" required>
                    <option value="">-- Pilih Wallet --</option>
                    @foreach ($wallets as $wallet)
                        <option value="{{ $wallet->id }}" data-type="{{ $wallet->wallet_type }}"
                            data-bank-name="{{ $wallet->bank_name }}" data-bank-account="{{ $wallet->bank_account }}"
                            data-account-name="{{ $wallet->account_name }}"
                            data-ewallet-provider="{{ $wallet->ewallet_provider }}"
                            data-ewallet-number="{{ $wallet->ewallet_number }}"
                            data-ewallet-name="{{ $wallet->ewallet_name }}"
                            {{ old('wallet_id') == $wallet->id ? 'selected' : '' }}>
                            @if ($wallet->wallet_type === 'bank')
                                {{ $wallet->bank_name }} -
                                {{ substr($wallet->bank_account, 0, 4) }}***{{ substr($wallet->bank_account, -3) }}
                            @else
                                {{ $wallet->ewallet_provider }} -
                                {{ substr($wallet->ewallet_number, 0, 4) }}***{{ substr($wallet->ewallet_number, -3) }}
                            @endif
                            @if ($wallet->is_primary)
                                (Utama)
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('wallet_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Selected Wallet Info -->
            <div class="mb-3" id="walletInfo" style="display: none;">
                <small class="text-muted">Detail wallet terpilih</small>
                <div class="bank-info d-flex align-items-center mt-2">
                    <div class="bank-icon me-3">
                        <i class="bi bi-bank2 text-success bg-success bg-opacity-10 p-2 rounded-circle" id="walletIcon"></i>
                    </div>
                    <div>
                        <h6 class="text-white mb-0" id="walletNumber"></h6>
                        <small class="text-muted" id="walletName"></small>
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-3" id="divider" style="display: none;">

            <!-- Jumlah yang dapat ditarik -->
            <div class="mb-3">
                <small class="text-muted">Jumlah yang dapat ditarik</small>
                <div class="mt-1">
                    <small class="text-muted">IDR</small>
                    <span class="text-gold fs-4 fw-bold">{{ number_format($availableBalance, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Jumlah penarikan -->
            <div class="mb-3">
                <label class="form-label text-white">Jumlah penarikan</label>
                <div class="form-group mt-2">
                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary"
                        placeholder="Minimal IDR 50,000" id="withdrawAmount" value="{{ old('amount') }}" min="50000"
                        max="50000000" step="1000" required>
                    <small class="text-muted">Pajak 10% akan dipotong dari jumlah ini</small>
                </div>
                @error('amount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Fee Info -->
            <div class="mb-3" id="feeInfo" style="display: none;">
                <div class="bg-secondary bg-opacity-25 p-2 rounded">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Jumlah penarikan:</small>
                        <small class="text-white" id="amountDisplay">IDR 0</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Pajak (10%):</small>
                        <small class="text-warning" id="feeDisplay">IDR 0</small>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                        <small class="text-white"><strong>Yang diterima:</strong></small>
                        <small class="text-success" id="netDisplay"><strong>IDR 0</strong></small>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="form-label text-white">Catatan (Opsional)</label>
                <textarea name="notes" class="form-control bg-dark text-white border-secondary" rows="2"
                    placeholder="Catatan untuk penarikan...">{{ old('notes') }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100 btn-lg" id="withdrawBtn"
                {{ $availableBalance == 0 ? 'disabled' : '' }}>
                <i class="bi bi-download me-2"></i>
                Ajukan Penarikan
            </button>
        </div>
    </form>

    <!-- Instruksi -->
    <div class="instructions-section" style="padding-bottom: 100px;">
        <h6 class="text-white mb-3">Instruksi Penarikan</h6>
        <div class="instruction-list">
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">1.</span>
                <span class="text-white ms-2">Waktu penarikan harian: 09:00 - 18:00</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">2.</span>
                <span class="text-white ms-2">Batas penarikan harian: 1 kali, jumlah IDR 50,000 - IDR 50,000,000</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">3.</span>
                <span class="text-white ms-2">Pajak penarikan: 10% dari jumlah yang ditarik</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">4.</span>
                <span class="text-white ms-2">Pastikan informasi wallet sudah benar sebelum mengajukan</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">5.</span>
                <span class="text-white ms-2">Penarikan akan diproses dalam 1-3 hari kerja</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const walletSelect = document.getElementById('walletSelect');
            const walletInfo = document.getElementById('walletInfo');
            const walletIcon = document.getElementById('walletIcon');
            const walletNumber = document.getElementById('walletNumber');
            const walletName = document.getElementById('walletName');
            const divider = document.getElementById('divider');
            const withdrawInput = document.getElementById('withdrawAmount');
            const withdrawBtn = document.getElementById('withdrawBtn');
            const feeInfo = document.getElementById('feeInfo');
            const amountDisplay = document.getElementById('amountDisplay');
            const feeDisplay = document.getElementById('feeDisplay');
            const netDisplay = document.getElementById('netDisplay');
            const availableBalance = {{ $availableBalance }};

            // Handle wallet selection
            walletSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option.value) {
                    const type = option.dataset.type;

                    if (type === 'bank') {
                        walletIcon.className =
                            'bi bi-credit-card text-success bg-success bg-opacity-10 p-2 rounded-circle';
                        walletNumber.textContent = option.dataset.bankAccount;
                        walletName.textContent = option.dataset.bankName + ' - ' + option.dataset
                            .accountName;
                    } else {
                        walletIcon.className =
                            'bi bi-phone text-success bg-success bg-opacity-10 p-2 rounded-circle';
                        walletNumber.textContent = option.dataset.ewalletNumber;
                        walletName.textContent = option.dataset.ewalletProvider + ' - ' + option.dataset
                            .ewalletName;
                    }

                    walletInfo.style.display = 'block';
                    divider.style.display = 'block';
                } else {
                    walletInfo.style.display = 'none';
                    divider.style.display = 'none';
                }
            });

            // Handle amount input and fee calculation
            withdrawInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;

                if (amount > 0) {
                    const fee = amount * 0.10; // 10% fee
                    const net = amount - fee;

                    amountDisplay.textContent = 'IDR ' + amount.toLocaleString('id-ID');
                    feeDisplay.textContent = 'IDR ' + fee.toLocaleString('id-ID');
                    netDisplay.textContent = 'IDR ' + net.toLocaleString('id-ID');

                    feeInfo.style.display = 'block';
                } else {
                    feeInfo.style.display = 'none';
                }

                // Validation
                if (amount > availableBalance) {
                    this.setCustomValidity('Jumlah melebihi saldo yang tersedia');
                } else if (amount < 50000 && amount > 0) {
                    this.setCustomValidity('Minimal penarikan IDR 50,000');
                } else if (amount > 50000000) {
                    this.setCustomValidity('Maksimal penarikan IDR 50,000,000');
                } else {
                    this.setCustomValidity('');
                }
            });

            // Initialize wallet info if there's old input
            if (walletSelect.value) {
                walletSelect.dispatchEvent(new Event('change'));
            }

            // Initialize amount calculation if there's old input
            if (withdrawInput.value) {
                withdrawInput.dispatchEvent(new Event('input'));
            }

            // Disable form if balance is 0
            if (availableBalance === 0) {
                withdrawInput.disabled = true;
                walletSelect.disabled = true;
                withdrawBtn.disabled = true;
                withdrawBtn.textContent = 'Saldo Tidak Mencukupi';
            }
        });
    </script>
@endsection
