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

    @if ($withdrawableBalance <= 0)
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
                    {{ $withdrawableBalance <= 0 ? 'disabled' : '' }} required>
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

            <!-- Jumlah yang dapat ditarik dengan breakdown -->
            <div class="mb-3">
                <small class="text-muted">Jumlah yang dapat ditarik</small>
                <div class="mt-1">
                    <small class="text-muted">IDR</small>
                    <span class="text-gold fs-4 fw-bold">{{ number_format($withdrawableBalance, 0, ',', '.') }}</span>
                </div>

                <!-- Balance Breakdown -->
                {{-- @if ($withdrawableBalance > 0)
                    <div class="mt-2">
                        <small class="text-muted d-block mb-1">Detail Saldo:</small>
                        <div class="d-flex flex-wrap gap-2">
                            @if ($balanceBreakdown['revenue'] > 0)
                                <span class="badge bg-success bg-opacity-75">
                                    Revenue: IDR {{ number_format($balanceBreakdown['revenue'], 0, ',', '.') }}
                                </span>
                            @endif
                            @if ($balanceBreakdown['commission'] > 0)
                                <span class="badge bg-info bg-opacity-75">
                                    Commission: IDR {{ number_format($balanceBreakdown['commission'], 0, ',', '.') }}
                                </span>
                            @endif
                            @if ($balanceBreakdown['deposit'] > 0)
                                <span class="badge bg-primary bg-opacity-75">
                                    Deposit: IDR {{ number_format($balanceBreakdown['deposit'], 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif --}}

                @if ($withdrawableBalance <= 0)
                    <small class="text-danger">Saldo tidak mencukupi untuk penarikan</small>
                @endif
            </div>

            <!-- Fee Information -->
            @if ($withdrawalFeePercent > 0)
                <div class="mb-3">
                    <div class="bg-warning bg-opacity-20 p-2 rounded border border-warning border-opacity-50">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle text-dark me-2"></i>
                            <small class="text-dark fw-bold">
                                Biaya admin penarikan: {{ $withdrawalFeePercent }}%
                            </small>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Jumlah penarikan -->
            <div class="mb-3">
                <label class="form-label text-white">Jumlah penarikan</label>
                <div class="form-group mt-2">
                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary"
                        placeholder="Minimal IDR 50,000" id="withdrawAmount" value="{{ old('amount') }}" min="50000"
                        max="{{ $withdrawableBalance > 0 ? $withdrawableBalance : 0 }}" step="1000"
                        {{ $withdrawableBalance <= 0 ? 'disabled' : '' }} required>
                    <small class="text-muted">
                        @if ($withdrawableBalance > 0)
                            Maksimal: IDR {{ number_format($withdrawableBalance, 0, ',', '.') }}
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
                <div class="bg-secondary bg-opacity-25 p-3 rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-white">Jumlah penarikan:</small>
                        <small class="text-white fw-bold" id="withdrawalDisplay">IDR 0</small>
                    </div>
                    @if ($withdrawalFeePercent > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-white">Biaya admin ({{ $withdrawalFeePercent }}%):</small>
                            <small class="text-danger fw-bold" id="feeDisplay">IDR 0</small>
                        </div>
                        <hr class="border-secondary my-2">
                    @endif
                    <div class="d-flex justify-content-between">
                        <small class="text-white">Jumlah yang akan diterima:</small>
                        <small class="text-white fw-bold" id="netDisplay">IDR 0</small>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success w-100 btn-lg" id="withdrawBtn"
                {{ $withdrawableBalance <= 0 ? 'disabled' : '' }}>
                <i class="bi bi-download me-2"></i>
                @if ($withdrawableBalance <= 0)
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
            @if ($withdrawalFeePercent > 0)
                <div class="instruction-item mb-2">
                    <span class="text-gold fw-bold">2.</span>
                    <span class="text-white ms-2">Biaya admin {{ $withdrawalFeePercent }}% akan dipotong dari jumlah
                        penarikan</span>
                </div>
            @endif
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">{{ $withdrawalFeePercent > 0 ? '3' : '2' }}.</span>
                <span class="text-white ms-2">Penarikan akan menggunakan prioritas: Revenue → Commission → Deposit</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">{{ $withdrawalFeePercent > 0 ? '4' : '3' }}.</span>
                <span class="text-white ms-2">Pastikan informasi wallet sudah benar sebelum mengajukan</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">{{ $withdrawalFeePercent > 0 ? '5' : '4' }}.</span>
                <span class="text-white ms-2">Penarikan akan diproses dalam 12 / 48 jam</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">{{ $withdrawalFeePercent > 0 ? '6' : '5' }}.</span>
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
            const withdrawalDisplay = document.getElementById('withdrawalDisplay');
            const feeDisplay = document.getElementById('feeDisplay');
            const netDisplay = document.getElementById('netDisplay');
            const withdrawableBalance = {{ $withdrawableBalance }};
            const withdrawalFeePercent = {{ $withdrawalFeePercent }};
            let isSubmitting = false;

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
                    // Calculate withdrawal fee
                    const withdrawalFee = Math.round((amount * withdrawalFeePercent) / 100);
                    const netAmount = amount - withdrawalFee;

                    withdrawalDisplay.textContent = 'IDR ' + amount.toLocaleString('id-ID');
                    if (feeDisplay) {
                        feeDisplay.textContent = 'IDR ' + withdrawalFee.toLocaleString('id-ID');
                    }
                    netDisplay.textContent = 'IDR ' + netAmount.toLocaleString('id-ID');
                    amountPreview.style.display = 'block';
                } else {
                    amountPreview.style.display = 'none';
                }

                // Validation dengan check withdrawable balance
                if (amount > withdrawableBalance) {
                    this.setCustomValidity('Jumlah melebihi saldo yang tersedia (IDR ' + withdrawableBalance
                        .toLocaleString('id-ID') + ')');
                    withdrawBtn.disabled = true;
                } else if (amount < 50000 && amount > 0) {
                    this.setCustomValidity('Minimal penarikan IDR 50,000');
                    withdrawBtn.disabled = true;
                } else if (withdrawableBalance <= 0) {
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
            if (withdrawableBalance <= 0) {
                withdrawInput.disabled = true;
                walletSelect.disabled = true;
                withdrawBtn.disabled = true;
            }

            // Form submission validation
            document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                const amount = parseFloat(withdrawInput.value) || 0;

                if (amount <= 0) {
                    e.preventDefault();
                    alert('Masukkan jumlah penarikan yang valid');
                    return false;
                }

                if (amount > withdrawableBalance) {
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

                // Show confirmation with fee information
                const withdrawalFee = Math.round((amount * withdrawalFeePercent) / 100);
                const netAmount = amount - withdrawalFee;

                let confirmMessage =
                    `Konfirmasi penarikan:\n\nJumlah penarikan: IDR ${amount.toLocaleString('id-ID')}`;

                if (withdrawalFeePercent > 0) {
                    confirmMessage +=
                        `\nBiaya admin (${withdrawalFeePercent}%): IDR ${withdrawalFee.toLocaleString('id-ID')}`;
                }

                confirmMessage +=
                    `\nJumlah yang akan diterima: IDR ${netAmount.toLocaleString('id-ID')}\n\nLanjutkan?`;

                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return false;
                }

                isSubmitting = true;
                withdrawBtn.disabled = true;
                withdrawBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
            });
        });
    </script>
@endsection
