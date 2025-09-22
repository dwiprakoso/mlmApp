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

    <!-- Info Banner -->
    <div class="alert alert-info mb-3"
        style="background-color: var(--secondary-dark); border: 1px solid var(--border-color); color: var(--text-muted);">
        <small>Jagakeuangan - Indonesia Investments terlibat dengan regulator untuk membawa aset crypto ke arus
            utama.</small>
    </div>

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
                            <h6 class="text-white mb-3">{{ $product->name }}</h6>
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Harga</small>
                                        <h6 class="text-gold mb-0">IDR {{ number_format($product->price, 0, ',', '.') }}
                                        </h6>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Tipe</small>
                                        <h6 class="text-gold mb-0">{{ $product->type }}</h6>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <small class="text-muted">Durasi</small>
                                        <h6 class="text-gold mb-0">{{ $product->duration }} Hari</h6>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Status</small>
                                        <h6 class="text-gold mb-0">
                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $product->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            @if ($product->description)
                                <div class="mb-2">
                                    <small class="text-muted">Deskripsi</small>
                                    <p class="text-white mb-0">{{ $product->description }}</p>
                                </div>
                            @endif
                            <hr style="border-color: var(--border-color);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-gold circle-icon me-2"
                                        style="width: 30px; height: 30px; font-size: 14px;">
                                        <i class="bi bi-gem text-dark"></i>
                                    </div>
                                    <small class="text-white">ID: {{ $product->id }}</small>
                                </div>
                                <button class="btn btn-gold btn-sm px-3" onclick="investNow({{ $product->id }})">
                                    <small>Investasi Sekarang</small>
                                </button>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

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
        });

        // Function to handle investment
        function investNow(productId) {
            // Add your investment logic here
            alert('Investing in product ID: ' + productId);
            // You can redirect to investment form or open a modal
            // window.location.href = `/member/invest/${productId}`;
        }
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
    </style>
@endsection
