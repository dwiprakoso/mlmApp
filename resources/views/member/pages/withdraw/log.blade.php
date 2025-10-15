@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-center mb-4 position-relative">
        <a href="{{ route('member.withdraw.index') }}" class="position-absolute start-0 text-gold">
            <i class="bi bi-arrow-left fs-4"></i>
        </a>
        <h5 class="text-white mb-0">Catatan Penarikan</h5>
    </div>

    <!-- Withdrawal Log List -->
    <div class="withdrawal-log-container" style="padding-bottom: 100px;">
        @forelse($withdrawals as $withdrawal)
            <div class="log-item mb-3" data-status="{{ $withdrawal->status }}">
                <div class="card-dark p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-white mb-1">Penarikan Uang</h6>
                            <small class="text-muted">{{ $withdrawal->transaction_id }}</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">IDR</small>
                            <span
                                class="text-white fw-bold fs-5">{{ number_format($withdrawal->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Amount Breakdown -->
                    {{-- @if ($withdrawal->withdrawal_fee > 0)
                        <div class="mb-2">
                            <div class="bg-secondary bg-opacity-25 p-2 rounded">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-white">Jumlah penarikan:</small>
                                    <small class="text-white">IDR
                                        {{ number_format($withdrawal->amount, 0, ',', '.') }}</small>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-white">Biaya admin:</small>
                                    <small class="text-danger">IDR
                                        {{ number_format($withdrawal->withdrawal_fee, 0, ',', '.') }}</small>
                                </div>
                                <hr class="border-secondary my-1">
                                <div class="d-flex justify-content-between">
                                    <small class="text-white fw-bold">Jumlah diterima:</small>
                                    <small class="text-white fw-bold">IDR
                                        {{ number_format($withdrawal->amount - $withdrawal->withdrawal_fee, 0, ',', '.') }}</small>
                                </div>
                            </div>
                        </div>
                    @endif --}}

                    <!-- Wallet Info -->
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Tujuan Penarikan</small>

                        @if ($withdrawal->wallet->wallet_type == 'ewallet')
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-white">{{ $withdrawal->wallet->ewallet_provider }}</span>
                                    <span class="text-muted ms-2">{{ $withdrawal->wallet->ewallet_number }}</span>
                                </div>
                                <div>
                                    <small class="text-muted">{{ $withdrawal->wallet->ewallet_name }}</small>
                                </div>
                            </div>
                        @elseif ($withdrawal->wallet->wallet_type == 'bank')
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="fw-bold text-white">{{ $withdrawal->wallet->bank_name }}</span>
                                    <span class="text-muted ms-2">{{ $withdrawal->wallet->bank_account }}</span>
                                </div>
                                <div>
                                    <small class="text-muted">{{ $withdrawal->wallet->account_name }}</small>
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr class="border-secondary my-2">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-{{ $withdrawal->status_badge_color }}">
                            {{ $withdrawal->status }}
                        </span>
                    </div>

                    <!-- Payment Proof Button -->
                    {{-- @if ($withdrawal->payment_proof && $withdrawal->status === 'success')
                        <div class="mb-2">
                            <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#proofModal{{ $withdrawal->id }}">
                                <i class="bi bi-image me-1"></i>
                                Lihat Bukti Transfer
                            </button>
                        </div>
                    @endif --}}

                    <!-- Notes -->
                    {{-- @if ($withdrawal->notes)
                        <div class="mb-2">
                            <small class="text-muted d-block">Catatan Anda</small>
                            <small class="text-white">{{ $withdrawal->notes }}</small>
                        </div>
                    @endif --}}

                    <!-- Admin Notes -->
                    {{-- @if ($withdrawal->admin_notes)
                        <div class="mb-2">
                            <small class="text-muted d-block">Catatan Admin</small>
                            <small class="text-info">{{ $withdrawal->admin_notes }}</small>
                        </div>
                    @endif --}}

                    <!-- Rejection Reason -->
                    {{-- @if ($withdrawal->status === 'failed' && $withdrawal->rejection_reason)
                        <div class="alert alert-danger alert-sm p-2 mb-2">
                            <small><strong>Alasan Penolakan:</strong><br>{{ $withdrawal->rejection_reason }}</small>
                        </div>
                    @endif --}}

                    <!-- Timestamp -->
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $withdrawal->created_at->format('d M Y, H:i') }}</small>
                        @if ($withdrawal->updated_at != $withdrawal->created_at)
                            <small class="text-muted">Update: {{ $withdrawal->updated_at->format('d M Y, H:i') }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Proof Modal - MOVED INSIDE THE LOOP -->
            {{-- @if ($withdrawal->payment_proof && $withdrawal->status === 'success')
                <div class="modal fade" id="proofModal{{ $withdrawal->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content bg-dark border-secondary">
                            <div class="modal-header border-secondary">
                                <h6 class="modal-title text-white">
                                    <i class="bi bi-image me-2"></i>
                                    Bukti Transfer
                                </h6>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <div class="mb-3">
                                    <small class="text-muted">{{ $withdrawal->transaction_id }}</small>
                                </div>
                                <img src="{{ asset('storage/' . $withdrawal->payment_proof) }}" class="img-fluid rounded"
                                    alt="Bukti Transfer" style="max-height: 50vh; width: auto; cursor: pointer;"
                                    onclick="openFullscreen('{{ asset('storage/' . $withdrawal->payment_proof) }}')">
                                <div class="mt-2">
                                    <small class="text-muted">Tap gambar untuk memperbesar</small>
                                </div>
                            </div>
                            <div class="modal-footer border-secondary justify-content-center">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Tutup</button>
                                <a href="{{ asset('storage/' . $withdrawal->payment_proof) }}" download
                                    class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-download me-1"></i>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif --}}
        @empty
            <div class="text-center py-5">
                <i class="bi bi-receipt text-muted display-1 mb-3"></i>
                <h6 class="text-white mb-2">Belum Ada Penarikan</h6>
                <p class="text-muted mb-3">Anda belum pernah melakukan penarikan</p>
                <a href="{{ route('member.withdraw.index') }}" class="btn btn-success">
                    <i class="bi bi-plus me-1"></i>
                    Buat Penarikan Pertama
                </a>
            </div>
        @endforelse
    </div>

    <!-- Fullscreen Image Modal -->
    <div class="modal fade" id="fullscreenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-black">
                <div class="modal-header border-0 position-absolute top-0 end-0 z-3">
                    <button type="button" class="btn-close btn-close-white me-3 mt-3" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex align-items-center justify-content-center p-0">
                    <img id="fullscreenImage" src="" alt="Bukti Transfer" class="img-fluid"
                        style="max-height: 100vh; max-width: 100vw;">
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($withdrawals->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-container">
                {{-- Previous Button --}}
                @if ($withdrawals->onFirstPage())
                    <span class="pagination-btn disabled">
                        <i class="bi bi-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $withdrawals->previousPageUrl() }}" class="pagination-btn">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                @endif

                {{-- Page Numbers --}}
                <div class="pagination-info">
                    <span class="text-white">{{ $withdrawals->currentPage() }}</span>
                    <span class="text-muted mx-2">dari</span>
                    <span class="text-white">{{ $withdrawals->lastPage() }}</span>
                </div>

                {{-- Next Button --}}
                @if ($withdrawals->hasMorePages())
                    <a href="{{ $withdrawals->nextPageUrl() }}" class="pagination-btn">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                @else
                    <span class="pagination-btn disabled">
                        <i class="bi bi-chevron-right"></i>
                    </span>
                @endif
            </div>

            {{-- Results Info --}}
            <div class="text-center mt-2">
                <small class="text-muted">
                    Menampilkan {{ $withdrawals->firstItem() ?? 0 }} - {{ $withdrawals->lastItem() ?? 0 }}
                    dari {{ $withdrawals->total() }} hasil
                </small>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const logItems = document.querySelectorAll('.log-item');

            // Status filter functionality (if filter exists)
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const selectedStatus = this.value;

                    logItems.forEach(item => {
                        if (selectedStatus === '' || item.dataset.status === selectedStatus) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
        });

        // Function to open fullscreen image
        function openFullscreen(imageSrc) {
            const fullscreenModal = new bootstrap.Modal(document.getElementById('fullscreenModal'));
            const fullscreenImage = document.getElementById('fullscreenImage');

            fullscreenImage.src = imageSrc;
            fullscreenModal.show();
        }

        // Auto refresh every 30 seconds for pending withdrawals
        const hasPendingWithdrawals = document.querySelectorAll('[data-status="pending"]').length > 0;
        if (hasPendingWithdrawals) {
            setTimeout(() => {
                location.reload();
            }, 30000); // Refresh after 30 seconds
        }
    </script>

    <style>
        .alert-sm {
            font-size: 0.875rem;
        }

        .log-item {
            transition: all 0.3s ease;
        }

        .log-item:hover {
            transform: translateY(-1px);
        }

        .badge {
            font-size: 0.75rem;
        }

        /* Pagination Wrapper */
        .pagination-wrapper {
            position: fixed;
            bottom: 80px;
            /* Sesuaikan dengan tinggi bottom navigation */
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(18, 18, 18, 1) 70%, rgba(18, 18, 18, 0));
            padding: 20px 15px 15px;
            z-index: 100;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .pagination-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
            background: #2d2d2d;
            border: 1px solid #444;
            border-radius: 10px;
            color: #d4af37;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #d4af37;
            color: #121212;
            transform: scale(1.05);
        }

        .pagination-btn.disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background: #2d2d2d;
            border: 1px solid #444;
            border-radius: 10px;
        }

        .pagination-info .text-white {
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Adjust container padding for pagination */
        .withdrawal-log-container {
            padding-bottom: 180px !important;
            /* Tambah space untuk pagination */
        }

        /* Modal customizations for mobile */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: 15px;
                max-width: calc(100% - 30px);
            }

            .modal-sm {
                max-width: calc(100% - 30px);
            }

            .modal-content {
                border-radius: 10px;
            }
        }

        /* Modal size control */
        .modal-sm {
            max-width: 400px;
        }

        /* Fullscreen modal styles */
        .modal-fullscreen .modal-content {
            background-color: rgba(0, 0, 0, 0.95) !important;
        }

        .modal-fullscreen .modal-header {
            background: transparent;
        }

        /* Button styling */
        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
@endsection
