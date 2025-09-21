@extends('member.layouts.app')
@section('content')
    <!-- Header -->
    <div class="hero-card mb-0">
        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('member.dompet.index') }}" class="btn btn-outline-light btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="text-white mb-1">Tambah Wallet</h4>
                <p class="text-muted mb-0">Tambahkan kartu bank atau e-wallet baru</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="content-section">
        <form action="{{ route('member.dompet.store') }}" method="POST">
            @csrf

            <!-- Wallet Type Selection -->
            <div class="card-dark p-4 mb-3">
                <h6 class="text-white mb-3">Pilih Tipe Wallet</h6>

                <div class="row g-2">
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="wallet_type" id="type_bank" value="bank"
                            {{ old('wallet_type') == 'bank' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light w-100 p-3" for="type_bank">
                            <i class="bi bi-credit-card d-block mb-2 fs-4"></i>
                            <small>Bank Account</small>
                        </label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="wallet_type" id="type_ewallet" value="ewallet"
                            {{ old('wallet_type') == 'ewallet' ? 'checked' : '' }}>
                        <label class="btn btn-outline-light w-100 p-3" for="type_ewallet">
                            <i class="bi bi-phone d-block mb-2 fs-4"></i>
                            <small>E-Wallet</small>
                        </label>
                    </div>
                </div>

                @error('wallet_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Bank Form -->
            <div class="card-dark p-4 mb-3" id="bank-form" style="display: none;">
                <h6 class="text-white mb-3">Informasi Bank</h6>

                <div class="mb-3">
                    <label class="form-label text-white">Nama Bank</label>
                    <select name="bank_name" class="form-select bg-dark text-white border-secondary">
                        <option value="">Pilih Bank</option>
                        @foreach (\App\Models\Wallet::BANK_PROVIDERS as $key => $value)
                            <option value="{{ $key }}" {{ old('bank_name') == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    @error('bank_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Nomor Rekening</label>
                    <input type="text" name="bank_account" class="form-control bg-dark text-white border-secondary"
                        placeholder="Masukkan nomor rekening" value="{{ old('bank_account') }}">
                    @error('bank_account')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Nama Pemegang Rekening</label>
                    <input type="text" name="account_name" class="form-control bg-dark text-white border-secondary"
                        placeholder="Nama sesuai rekening" value="{{ old('account_name') }}">
                    @error('account_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- E-wallet Form -->
            <div class="card-dark p-4 mb-3" id="ewallet-form" style="display: none;">
                <h6 class="text-white mb-3">Informasi E-Wallet</h6>

                <div class="mb-3">
                    <label class="form-label text-white">Provider E-Wallet</label>
                    <select name="ewallet_provider" class="form-select bg-dark text-white border-secondary">
                        <option value="">Pilih E-Wallet</option>
                        @foreach (\App\Models\Wallet::EWALLET_PROVIDERS as $key => $value)
                            <option value="{{ $key }}" {{ old('ewallet_provider') == $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                    @error('ewallet_provider')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Nomor HP/ID E-Wallet</label>
                    <input type="text" name="ewallet_number" class="form-control bg-dark text-white border-secondary"
                        placeholder="08xxxxxxxxxx" value="{{ old('ewallet_number') }}">
                    @error('ewallet_number')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Nama Akun E-Wallet</label>
                    <input type="text" name="ewallet_name" class="form-control bg-dark text-white border-secondary"
                        placeholder="Nama sesuai akun e-wallet" value="{{ old('ewallet_name') }}">
                    @error('ewallet_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Additional Options -->
            <div class="card-dark p-4 mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_primary" id="is_primary" value="1"
                        {{ old('is_primary') ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="is_primary">
                        Jadikan sebagai wallet utama
                    </label>
                </div>

                <div class="mt-3">
                    <label class="form-label text-white">Catatan (Opsional)</label>
                    <textarea name="notes" class="form-control bg-dark text-white border-secondary" rows="2"
                        placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Wallet
                </button>
                <a href="{{ route('member.dompet.index') }}" class="btn btn-outline-light">
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bankRadio = document.getElementById('type_bank');
            const ewalletRadio = document.getElementById('type_ewallet');
            const bankForm = document.getElementById('bank-form');
            const ewalletForm = document.getElementById('ewallet-form');

            function toggleForms() {
                if (bankRadio.checked) {
                    bankForm.style.display = 'block';
                    ewalletForm.style.display = 'none';
                } else if (ewalletRadio.checked) {
                    bankForm.style.display = 'none';
                    ewalletForm.style.display = 'block';
                } else {
                    bankForm.style.display = 'none';
                    ewalletForm.style.display = 'none';
                }
            }

            bankRadio.addEventListener('change', toggleForms);
            ewalletRadio.addEventListener('change', toggleForms);

            // Initialize on load
            toggleForms();
        });
    </script>
@endsection
