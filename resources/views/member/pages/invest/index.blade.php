@extends('member.layouts.app')
@section('content')
    <!-- Header dengan Back Button -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <a href="{{ route('member.dashboard.index') }}" class="text-gold me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h5 class="text-white mb-0">Investasi VIP</h5>
        </div>
    </div>

    {{-- <div class="card-dark p-3 mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Saldo Deposit (Tersedia untuk Investasi)</small>
                <h5 class="text-gold mb-0">IDR {{ number_format($purchasableBalance, 0, ',', '.') }}</h5>
            </div>
            <i class="bi bi-wallet2 text-gold fs-3"></i>
        </div>
    </div> --}}

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success mb-3" style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger mb-3" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3" style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Dynamic Tab Navigation -->
    <div class="mb-4">
        <div class="row g-1">
            @foreach ($products as $type => $typeProducts)
                <div class="col-4">
                    <button class="btn {{ $loop->first ? 'btn-gold active' : 'btn-outline-gold' }} w-100 tab-btn"
                        data-tab="{{ Str::slug($type) }}">
                        <small>{{ $type }}</small>
                        @if ($loop->first)
                            <span class="badge bg-danger position-absolute top-0 end-0 translate-middle-y"
                                style="font-size: 8px;">HOT</span>
                        @endif
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    <!-- VIP Plans Container -->
    <div id="vip-plans-container">
        @foreach ($products as $type => $typeProducts)
            <div class="tab-content {{ $loop->first ? 'active' : '' }}" id="{{ Str::slug($type) }}-tab">
                @if ($typeProducts->count() > 0)
                    @foreach ($typeProducts as $product)
                        <div class="card-dark p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h6 class="text-white mb-0">{{ $product->name }}</h6>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            <!-- Informasi Utama -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Modal Investasi</small>
                                        <h6 class="text-gold mb-0">IDR {{ number_format($product->price, 0, ',', '.') }}
                                        </h6>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Durasi</small>
                                        <h6 class="text-white mb-0">{{ $product->duration }} Hari</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <small class="text-muted">Total Profit</small>
                                        <h6 class="text-white mb-0">IDR
                                            {{ number_format($product->total_profit, 0, ',', '.') }}</h6>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Profit Harian</small>
                                        <h6 class="text-warning mb-0">IDR
                                            {{ number_format($product->profit, 0, ',', '.') }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Deskripsi</small>
                                    <p class="text-white mb-0">{{ $product->description }}</p>
                                </div>
                            </div>

                            <hr style="border-color: var(--border-color);">

                            <!-- Action Button -->
                            <div class="d-flex justify-content-between align-items-center">
                                @if ($product->is_active)
                                    <!-- ✅ UPDATED: Check if user has sufficient balance -->
                                    @if ($purchasableBalance >= $product->price)
                                        <button class="btn btn-gold btn-sm px-3" onclick="investNow({{ $product->id }})">
                                            <small>Investasi Sekarang</small>
                                        </button>
                                    @else
                                        <button class="btn btn-secondary btn-sm px-3" disabled
                                            title="Saldo deposit tidak mencukupi">
                                            <small>Saldo Tidak Cukup</small>
                                        </button>
                                    @endif
                                @else
                                    <button class="btn btn-secondary btn-sm px-3" disabled>
                                        <small>Tidak Tersedia</small>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card-dark p-3 mb-3">
                        <p class="text-muted text-center mb-0">Tidak ada produk tersedia untuk kategori ini.</p>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Fallback jika tidak ada produk sama sekali -->
        @if ($products->isEmpty())
            <div class="card-dark p-3 mb-3">
                <p class="text-muted text-center mb-0">Belum ada produk investasi yang tersedia.</p>
            </div>
        @endif
    </div>

    <!-- Quick Navigation -->
    <div class="row justify-content-center mt-4">
        <div class="col-6">
            <a href="{{ route('member.invest.log') }}" class="btn btn-outline-gold w-100">
                <i class="bi bi-clock-history me-2"></i>
                <small>Riwayat Investasi</small>
            </a>
        </div>
    </div>

    <!-- Hidden Form for Investment -->
    <form id="investmentForm" method="POST" action="{{ route('member.invest.store') }}" style="display: none;">
        @csrf
        <input type="hidden" id="productIdInput" name="product_id">
    </form>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-gold">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-gold mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h6 class="text-white mb-0">Memproses Investasi...</h6>
                    <small class="text-muted">Mohon tunggu sebentar</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            // Tab switching functionality
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('btn-gold', 'active');
                        btn.classList.add('btn-outline-gold');
                    });

                    // Add active class to clicked button
                    this.classList.remove('btn-outline-gold');
                    this.classList.add('btn-gold', 'active');

                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show target tab content
                    document.getElementById(targetTab + '-tab').classList.add('active');
                });
            });

            // Auto dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            });
        });

        // Function to handle investment
        function investNow(productId) {
            // Show confirmation modal
            if (confirm(
                    'Apakah Anda yakin ingin berinvestasi pada produk ini? Investasi akan menggunakan saldo deposit Anda.'
                )) {
                // Show loading modal
                const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();

                // Set product ID and submit form
                document.getElementById('productIdInput').value = productId;

                // Add a small delay to show loading modal
                setTimeout(() => {
                    document.getElementById('investmentForm').submit();
                }, 500);
            }
        }

        // Handle form submission errors
        document.getElementById('investmentForm').addEventListener('submit', function(e) {
            const submitBtn = document.querySelector('.btn-gold');
            if (submitBtn) {
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2" role="status"></span><small>Memproses...</small>';
                submitBtn.disabled = true;
            }
        });
    </script>

    <style>
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .circle-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .card-dark {
            background-color: var(--secondary-dark, #2c2c2c);
            border: 1px solid var(--border-color, #444);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .card-dark:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-gold {
            background-color: var(--gold-color, #ffd700);
            border-color: var(--gold-color, #ffd700);
            color: #000;
            font-weight: 500;
        }

        .btn-gold:hover {
            background-color: var(--gold-hover, #e6c200);
            border-color: var(--gold-hover, #e6c200);
        }

        .btn-outline-gold {
            border-color: var(--gold-color, #ffd700);
            color: var(--gold-color, #ffd700);
        }

        .btn-outline-gold:hover {
            background-color: var(--gold-color, #ffd700);
            color: #000;
        }

        .text-gold {
            color: var(--gold-color, #ffd700) !important;
        }

        .bg-gold {
            background-color: var(--gold-color, #ffd700) !important;
        }

        .border-gold {
            border-color: var(--gold-color, #ffd700) !important;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .badge {
            font-size: 0.75rem;
        }

        .tab-btn {
            position: relative;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .tab-btn .badge {
            font-size: 8px;
            padding: 2px 4px;
        }

        .fw-bold {
            font-weight: 600 !important;
        }

        @media (max-width: 768px) {
            .col-4 {
                margin-bottom: 0.25rem;
            }

            .tab-btn {
                font-size: 0.875rem;
                padding: 0.5rem;
            }

            .card-dark {
                margin-bottom: 1rem;
            }

            .row .col-6 {
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection
