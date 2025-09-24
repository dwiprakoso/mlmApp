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

    @if ($availableBalance <= 0)
        <div class="alert alert-warning" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Saldo tidak mencukupi untuk melakukan penarikan.
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
                <select name="wallet_id" class="form-select bg-dark text-white border-secondary" id="walletSelect"
                    {{ $availableBalance <= 0 ? 'disabled' : '' }} required>
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
                                {{ $wallet->bank_name }} - {{ $wallet->bank_account }}
                            @else
                                {{ $wallet->ewallet_provider }} - {{ $wallet->ewallet_number }}
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
                <div class="bank-info mt-2">
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
                @if ($availableBalance <= 0)
                    <small class="text-danger">Saldo tidak mencukupi untuk penarikan</small>
                @endif
            </div>

            <!-- Jumlah penarikan -->
            <div class="mb-3">
                <label class="form-label text-white">Jumlah penarikan</label>
                <div class="form-group mt-2">
                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary"
                        placeholder="Minimal IDR 50,000" id="withdrawAmount" value="{{ old('amount') }}" min="50000"
                        max="{{ $availableBalance > 0 ? $availableBalance : 0 }}" step="1000"
                        {{ $availableBalance <= 0 ? 'disabled' : '' }} required>
                    <small class="text-muted">
                        @if ($availableBalance > 0)
                            Maksimal: IDR {{ number_format($availableBalance, 0, ',', '.') }}
                        @else
                            Saldo tidak mencukupi untuk penarikan
                        @endif
                    </small>
                </div>
                @error('amount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Amount Preview -->
            <div class="mb-3" id="amountPreview" style="display: none;">
                <div class="bg-secondary bg-opacity-25 p-2 rounded">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Jumlah yang akan diterima:</small>
                        <small class="text-success fw-bold" id="netDisplay">IDR 0</small>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label class="form-label text-white">Catatan (Opsional)</label>
                <textarea name="notes" class="form-control bg-dark text-white border-secondary" rows="2"
                    placeholder="Catatan untuk penarikan..." {{ $availableBalance <= 0 ? 'disabled' : '' }}>{{ old('notes') }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100 btn-lg" id="withdrawBtn"
                {{ $availableBalance <= 0 ? 'disabled' : '' }}>
                <i class="bi bi-download me-2"></i>
                @if ($availableBalance <= 0)
                    Saldo Tidak Mencukupi
                @else
                    Ajukan Penarikan
                @endif
            </button>
        </div>
    </form>

    <!-- Instruksi -->
    <div class="instructions-section" style="padding-bottom: 100px;">
        <h6 class="text-white mb-3">Instruksi Penarikan</h6>
        <div class="instruction-list">
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">1.</span>
                <span class="text-white ms-2">Minimal penarikan IDR 50,000</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">2.</span>
                <span class="text-white ms-2">Pastikan informasi wallet sudah benar sebelum mengajukan</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">3.</span>
                <span class="text-white ms-2">Penarikan akan diproses dalam 1-3 hari kerja</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">4.</span>
                <span class="text-white ms-2">Pastikan saldo mencukupi sebelum melakukan penarikan</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const walletSelect = document.getElementById('walletSelect');
            const walletInfo = document.getElementById('walletInfo');
            const walletNumber = document.getElementById('walletNumber');
            const walletName = document.getElementById('walletName');
            const divider = document.getElementById('divider');
            const withdrawInput = document.getElementById('withdrawAmount');
            const withdrawBtn = document.getElementById('withdrawBtn');
            const amountPreview = document.getElementById('amountPreview');
            const netDisplay = document.getElementById('netDisplay');
            const availableBalance = {{ $availableBalance }};

            // Handle wallet selection
            walletSelect.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option.value) {
                    const type = option.dataset.type;

                    if (type === 'bank') {
                        walletNumber.textContent = option.dataset.bankAccount;
                        walletName.textContent = option.dataset.bankName + ' - ' + option.dataset
                            .accountName;
                    } else {
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

            // Handle amount input
            withdrawInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;

                if (amount > 0) {
                    netDisplay.textContent = 'IDR ' + amount.toLocaleString('id-ID');
                    amountPreview.style.display = 'block';
                } else {
                    amountPreview.style.display = 'none';
                }

                // Validation dengan check available balance
                if (amount > availableBalance) {
                    this.setCustomValidity('Jumlah melebihi saldo yang tersedia (IDR ' + availableBalance
                        .toLocaleString('id-ID') + ')');
                    withdrawBtn.disabled = true;
                } else if (amount < 50000 && amount > 0) {
                    this.setCustomValidity('Minimal penarikan IDR 50,000');
                    withdrawBtn.disabled = true;
                } else if (availableBalance <= 0) {
                    this.setCustomValidity('Saldo tidak mencukupi');
                    withdrawBtn.disabled = true;
                } else {
                    this.setCustomValidity('');
                    withdrawBtn.disabled = false;
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

            // Disable form if balance is 0 or less
            if (availableBalance <= 0) {
                withdrawInput.disabled = true;
                walletSelect.disabled = true;
                withdrawBtn.disabled = true;
                document.querySelector('textarea[name="notes"]').disabled = true;
            }

            // Form submission validation
            document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
                const amount = parseFloat(withdrawInput.value) || 0;

                if (amount <= 0) {
                    e.preventDefault();
                    alert('Masukkan jumlah penarikan yang valid');
                    return false;
                }

                if (amount > availableBalance) {
                    e.preventDefault();
                    alert('Jumlah penarikan melebihi saldo yang tersedia');
                    return false;
                }

                if (amount < 50000) {
                    e.preventDefault();
                    alert('Minimal penarikan IDR 50,000');
                    return false;
                }

                if (!walletSelect.value) {
                    e.preventDefault();
                    alert('Pilih wallet untuk penarikan');
                    return false;
                }
            });
        });
    </script>
@endsection
