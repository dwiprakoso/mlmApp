@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.deposit.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Log Deposit</h5>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Deposit List -->
    @forelse($deposits as $deposit)
        <div class="card-dark p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="text-white mb-1">{{ $deposit->code }}</h6>
                    <small class="text-muted">{{ $deposit->created_at->format('d M Y, H:i') }}</small>
                </div>
                <div class="text-end">
                    <div
                        class="badge 
                        @if ($deposit->status == 'pending') bg-warning text-dark
                        @elseif($deposit->status == 'waiting_approval') bg-info
                        @elseif($deposit->status == 'approved') bg-success
                        @elseif($deposit->status == 'rejected') bg-danger
                        @else bg-secondary @endif
                    ">
                        @if ($deposit->status == 'pending')
                            Menunggu Pembayaran
                        @elseif($deposit->status == 'waiting_approval')
                            Menunggu Verifikasi
                        @elseif($deposit->status == 'approved')
                            Berhasil
                        @elseif($deposit->status == 'rejected')
                            Ditolak
                        @else
                            {{ ucfirst($deposit->status) }}
                        @endif
                    </div>
                </div>
            </div>

            <div class="row align-items-center">
                <div class="col-8">
                    <p class="text-gold mb-1 fw-bold fs-5">Rp {{ number_format($deposit->amount, 0, ',', '.') }}</p>
                    <small class="text-muted">
                        <i class="bi bi-credit-card me-1"></i>
                        {{ ucfirst(str_replace('_', ' ', $deposit->method)) }}
                    </small>
                </div>
                <div class="col-4 text-end">
                    @if ($deposit->status == 'pending')
                        <a href="{{ route('member.deposit.payment', $deposit->id) }}" class="btn btn-gold btn-sm">
                            <i class="bi bi-arrow-right me-1"></i>
                            Bayar
                        </a>
                    @elseif($deposit->proof_url)
                        <button class="btn btn-outline-gold btn-sm" data-bs-toggle="modal"
                            data-bs-target="#proofModal{{ $deposit->id }}">
                            <i class="bi bi-image me-1"></i>
                            Lihat Bukti
                        </button>
                    @endif
                </div>
            </div>

            <!-- Progress Bar for Status -->
            <div class="mt-3">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar 
                        @if ($deposit->status == 'pending') bg-warning
                        @elseif($deposit->status == 'waiting_approval') bg-info
                        @elseif($deposit->status == 'approved') bg-success
                        @elseif($deposit->status == 'rejected') bg-danger @endif
                        "
                        style="width: 
                        @if ($deposit->status == 'pending') 25%
                        @elseif($deposit->status == 'waiting_approval') 75%
                        @elseif($deposit->status == 'approved') 100%
                        @elseif($deposit->status == 'rejected') 100% @endif
                    ">
                    </div>
                </div>
            </div>
        </div>

        <!-- Proof Modal -->
        @if ($deposit->proof_url)
            <div class="modal fade" id="proofModal{{ $deposit->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title text-white">Bukti Pembayaran - {{ $deposit->code }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $deposit->proof_url) }}" class="img-fluid rounded"
                                alt="Bukti Pembayaran">
                            <div class="mt-2">
                                <small class="text-muted">Upload pada:
                                    {{ $deposit->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @empty
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-3">
                <i class="bi bi-inbox display-1 text-muted"></i>
            </div>
            <h5 class="text-white mb-2">Belum ada deposit</h5>
            <p class="text-muted mb-3">Anda belum melakukan deposit apapun</p>
            <a href="{{ route('member.deposit.index') }}" class="btn btn-gold">
                <i class="bi bi-plus-circle me-2"></i>
                Deposit Sekarang
            </a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if ($deposits->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $deposits->links() }}
        </div>
    @endif

    <div class="mt-4" style="padding-bottom: 100px;"></div>

    <style>
        .progress {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .card-dark:hover {
            background: rgba(255, 255, 255, 0.08);
            transition: all 0.3s ease;
        }

        .badge {
            font-size: 11px;
            padding: 0.5em 0.75em;
        }

        .modal-content {
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .pagination .page-link {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #d4af37;
        }

        .pagination .page-link:hover {
            background-color: #d4af37;
            border-color: #d4af37;
            color: #000;
        }

        .pagination .page-item.active .page-link {
            background-color: #d4af37;
            border-color: #d4af37;
            color: #000;
        }
    </style>
@endsection
