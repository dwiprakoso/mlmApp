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

                    <!-- Wallet Info -->
                    <div class="mb-2">
                        <small class="text-muted d-block">Tujuan Penarikan</small>
                        @if ($withdrawal->payment_method === 'bank')
                            <div class="d-flex align-items-center mt-1">
                                <i class="bi bi-credit-card text-muted me-2"></i>
                                <span class="text-white">{{ $withdrawal->bank_name }}</span>
                                <span
                                    class="text-muted ms-2">{{ substr($withdrawal->bank_account, 0, 4) }}***{{ substr($withdrawal->bank_account, -3) }}</span>
                            </div>
                            <small class="text-muted ms-4">{{ $withdrawal->account_name }}</small>
                        @else
                            <div class="d-flex align-items-center mt-1">
                                <i class="bi bi-phone text-muted me-2"></i>
                                <span class="text-white">{{ $withdrawal->ewallet_provider }}</span>
                                <span
                                    class="text-muted ms-2">{{ substr($withdrawal->ewallet_number, 0, 4) }}***{{ substr($withdrawal->ewallet_number, -3) }}</span>
                            </div>
                            <small class="text-muted ms-4">{{ $withdrawal->ewallet_name }}</small>
                        @endif
                    </div>

                    <!-- Fee Info -->
                    <div class="mb-2">
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted d-block">Jumlah</small>
                                <small class="text-white">IDR {{ number_format($withdrawal->amount, 0, ',', '.') }}</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Pajak (10%)</small>
                                <small class="text-warning">IDR {{ number_format($withdrawal->fee, 0, ',', '.') }}</small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block">Diterima</small>
                                <small class="text-success">IDR
                                    {{ number_format($withdrawal->net_amount, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary my-2">

                    <!-- Status and Time Info -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Waktu Pengajuan</span>
                        <span class="text-white">{{ $withdrawal->requested_at->format('H:i, d M Y') }}</span>
                    </div>

                    @if ($withdrawal->processed_at)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Waktu Diproses</span>
                            <span class="text-white">{{ $withdrawal->processed_at->format('H:i, d M Y') }}</span>
                        </div>
                    @endif

                    @if ($withdrawal->completed_at)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Waktu Selesai</span>
                            <span class="text-white">{{ $withdrawal->completed_at->format('H:i, d M Y') }}</span>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Status</span>
                        <span class="badge bg-{{ $withdrawal->status_badge_color }}">
                            {{ \App\Models\Withdrawal::STATUSES[$withdrawal->status] ?? ucfirst($withdrawal->status) }}
                        </span>
                    </div>

                    <!-- Notes -->
                    @if ($withdrawal->notes)
                        <div class="mb-2">
                            <small class="text-muted d-block">Catatan Anda</small>
                            <small class="text-white">{{ $withdrawal->notes }}</small>
                        </div>
                    @endif

                    <!-- Admin Notes -->
                    @if ($withdrawal->admin_notes)
                        <div class="mb-2">
                            <small class="text-muted d-block">Catatan Admin</small>
                            <small class="text-info">{{ $withdrawal->admin_notes }}</small>
                        </div>
                    @endif

                    <!-- Rejection Reason -->
                    @if ($withdrawal->status === 'failed' && $withdrawal->rejection_reason)
                        <div class="alert alert-danger alert-sm p-2 mb-2">
                            <small><strong>Alasan Penolakan:</strong><br>{{ $withdrawal->rejection_reason }}</small>
                        </div>
                    @endif
                </div>
            </div>
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

    <!-- Pagination -->
    @if ($withdrawals->hasPages())
        <div class="d-flex justify-content-center mb-4">
            {{ $withdrawals->links() }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const logItems = document.querySelectorAll('.log-item');

            // Status filter functionality
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
        });

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
    </style>
@endsection
