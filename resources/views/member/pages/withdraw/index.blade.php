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

    <!-- Main Content Card -->
    <div class="card-dark p-3 mb-4">
        <!-- Rekening Bank -->
        <div class="mb-3">
            <small class="text-muted">Rekening bank</small>
            <div class="bank-info d-flex align-items-center mt-2">
                <div class="bank-icon me-3">
                    <i class="bi bi-bank2 text-success bg-success bg-opacity-10 p-2 rounded-circle"></i>
                </div>
                <div>
                    <h6 class="text-white mb-0">0821 7773 7778</h6>
                    <small class="text-muted">Irvans</small>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-3">

        <!-- Jumlah yang dapat ditarik -->
        <div class="mb-3">
            <small class="text-muted">Jumlah yang dapat ditarik</small>
            <div class="mt-1">
                <small class="text-muted">IDR</small>
                <span class="text-gold fs-4 fw-bold">0</span>
            </div>
        </div>

        <!-- Jumlah penarikan -->
        <div class="mb-4">
            <small class="text-muted">Jumlah penarikan</small>
            <div class="form-group mt-2">
                <input type="text" class="form-control form-control-dark" placeholder="Jumlah penarikan"
                    id="withdrawAmount">
            </div>
        </div>

        <!-- Penarikan Button -->
        <button class="btn btn-success w-100 btn-lg" id="withdrawBtn">
            Penarikan
        </button>
    </div>

    <!-- Instruksi -->
    <div class="instructions-section" style="padding-bottom: 100px;">
        <h6 class="text-white mb-3">Instruksi</h6>
        <div class="instruction-list">
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">1.</span>
                <span class="text-white ms-2">Waktu penarikan harian: 09:00 to 18:00.</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">2.</span>
                <span class="text-white ms-2">Batas penarikan harian: 1 kali, jumlah IDR50,000 - IDR50,000,000</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">3.</span>
                <span class="text-white ms-2">Penarikan ke alamat USDT: IDR1,000,000 - IDR50,000,000</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">4.</span>
                <span class="text-white ms-2">Pajak penarikan: 10% dari jumlah</span>
            </div>
            <div class="instruction-item mb-2">
                <span class="text-gold fw-bold">5.</span>
                <span class="text-white ms-2">Penarikan gagal Silakan periksa informasi kartu banknya benar</span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const withdrawInput = document.getElementById('withdrawAmount');
            const withdrawBtn = document.getElementById('withdrawBtn');

            // Format number input
            withdrawInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value) {
                    this.value = new Intl.NumberFormat('id-ID').format(value);
                }
            });

            // Handle withdraw button
            withdrawBtn.addEventListener('click', function() {
                const amount = withdrawInput.value;
                if (!amount || amount.trim() === '') {
                    alert('Masukkan jumlah penarikan terlebih dahulu');
                    return;
                }

                // Add your withdrawal logic here
                console.log('Withdraw amount:', amount);
                alert('Permintaan penarikan sedang diproses');
            });

            // Disable input if balance is 0
            const balance = 0; // This should come from your backend
            if (balance === 0) {
                withdrawInput.disabled = true;
                withdrawBtn.disabled = true;
                withdrawBtn.classList.add('disabled');
            }
        });
    </script>
@endsection
