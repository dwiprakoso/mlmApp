@extends('member.layouts.app')
@section('content')
    <!-- Header -->
    <div class="hero-card mb-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="text-white mb-1">Info Kartu</h4>
                <p class="text-muted mb-0">Kelola kartu bank dan e-wallet Anda</p>
            </div>
            <a href="{{ route('member.dompet.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus me-1"></i>
                Tambah
            </a>
        </div>
    </div>

    <!-- Wallet List -->
    <div class="content-section">
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

        @forelse($wallets as $wallet)
            <div class="card-dark p-3 mb-3 position-relative">
                <!-- Primary Badge -->
                @if ($wallet->is_primary)
                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                        <span class="badge bg-success">Utama</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px;">
                            @if ($wallet->wallet_type === 'bank')
                                <i class="bi bi-credit-card text-muted fs-4"></i>
                            @else
                                <i class="bi bi-phone text-muted fs-4"></i>
                            @endif
                        </div>
                        <div>
                            @if ($wallet->wallet_type === 'bank')
                                <h6 class="text-white mb-1">{{ $wallet->bank_name }}</h6>
                                <p class="text-muted mb-0">{{ $wallet->account_name }}</p>
                                <small
                                    class="text-gold">{{ $wallet->formatted_identifier ?? '****' . substr($wallet->bank_account, -4) }}</small>
                            @else
                                <h6 class="text-white mb-1">{{ $wallet->ewallet_provider }}</h6>
                                <p class="text-muted mb-0">{{ $wallet->ewallet_name }}</p>
                                <small
                                    class="text-gold">{{ substr($wallet->ewallet_number, 0, 4) }}***{{ substr($wallet->ewallet_number, -3) }}</small>
                            @endif
                        </div>
                    </div>

                    <!-- Action Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (!$wallet->is_primary)
                                <li>
                                    <form action="{{ route('member.dompet.setPrimary', $wallet) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-star me-2"></i>Jadikan Utama
                                        </button>
                                    </form>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('member.dompet.edit', $wallet) }}">
                                    <i class="bi bi-pencil me-2"></i>Edit
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('member.dompet.destroy', $wallet) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus wallet ini?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-trash me-2"></i>Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                @if ($wallet->notes)
                    <div class="border-top pt-2 mt-2">
                        <small class="text-muted">{{ $wallet->notes }}</small>
                    </div>
                @endif
            </div>
        @empty
            <div class="card-dark p-4 text-center">
                <i class="bi bi-credit-card-2-front text-muted display-1 mb-3"></i>
                <h6 class="text-white mb-2">Belum Ada Wallet</h6>
                <p class="text-muted mb-3">Tambahkan kartu bank atau e-wallet untuk memudahkan transaksi</p>
                <a href="{{ route('member.dompet.create') }}" class="btn btn-success">
                    <i class="bi bi-plus me-1"></i>
                    Tambah Wallet Pertama
                </a>
            </div>
        @endforelse
    </div>
@endsection
