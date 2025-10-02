@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <!--begin::Toolbar wrapper-->
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        Deposits</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Deposits</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar wrapper-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
            <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-success">Success</h4>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
            <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
            <div class="d-flex flex-column">
                <h4 class="mb-1 text-danger">Error</h4>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-customer-table-filter="search"
                                class="form-control form-control-solid w-250px ps-12" placeholder="Search Deposits" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Add deposit-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_deposit">
                                <i class="ki-outline ki-plus fs-2 d-lg-none"></i>
                                <span class="d-none d-lg-inline">Tambah Deposit</span>
                            </button>
                            <!--end::Add deposit-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table (Desktop)-->
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Reference</th>
                                    <th class="min-w-125px">No Hp</th>
                                    <th class="min-w-125px">Jumlah</th>
                                    <th class="min-w-125px">Metode Pembayaran</th>
                                    <th class="min-w-125px">Status</th>
                                    <th class="min-w-125px">Waktu</th>
                                    <th class="text-end min-w-70px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse ($deposits as $deposit)
                                    <tr>
                                        <td>
                                            <div class="text-gray-800 text-hover-primary fw-bold">
                                                {{ $deposit->reference }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800 text-hover-primary">
                                                {{ $deposit->user->phone }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800 fw-bold">
                                                Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800">{{ $deposit->payment_method }}</div>
                                        </td>
                                        <td>
                                            @if ($deposit->status == 'success')
                                                <span class="badge badge-light-success">Confirmed</span>
                                            @elseif($deposit->status == 'waiting_confirmation' || $deposit->status == 'pending')
                                                <span class="badge badge-light-warning">Waiting</span>
                                            @elseif($deposit->status == 'failed')
                                                <span class="badge badge-light-danger">Rejected</span>
                                            @else
                                                <span class="badge badge-light-secondary">{{ $deposit->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-gray-800">
                                                {{ $deposit->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="#"
                                                class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Actions
                                                <i class="ki-outline ki-down fs-5 ms-1"></i>
                                            </a>
                                            <!--begin::Menu-->
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.deposit.edit', $deposit->id) }}"
                                                        class="menu-link px-3">Detail</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 text-danger"
                                                        onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus deposit ini?')) { document.getElementById('delete-form-{{ $deposit->id }}').submit(); }">
                                                        Delete
                                                    </a>
                                                    <form id="delete-form-{{ $deposit->id }}"
                                                        action="{{ route('admin.deposit.destroy', $deposit->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                                <div class="text-gray-800 fw-bold mb-1">No deposits found</div>
                                                <div class="text-muted">Start by adding a new deposit</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table (Desktop)-->

                    <!--begin::Mobile Cards-->
                    <div class="d-lg-none mobile-deposit-list">
                        @forelse ($deposits as $deposit)
                            <div class="card mobile-deposit-card mb-4 shadow-sm" data-deposit-id="{{ $deposit->id }}">
                                <div class="card-body p-4">
                                    <!--begin::Header-->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <div class="text-gray-500 fs-8 mb-1">Reference</div>
                                            <div class="text-gray-800 fw-bold fs-6">{{ $deposit->reference }}</div>
                                        </div>
                                        @if ($deposit->status == 'success')
                                            <span class="badge badge-success fs-7">Confirmed</span>
                                        @elseif($deposit->status == 'waiting_confirmation' || $deposit->status == 'pending')
                                            <span class="badge badge-warning fs-7">Waiting</span>
                                        @elseif($deposit->status == 'failed')
                                            <span class="badge badge-danger fs-7">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary fs-7">{{ $deposit->status }}</span>
                                        @endif
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Amount-->
                                    <div class="mb-3 pb-3 border-bottom border-gray-300">
                                        <div class="text-gray-500 fs-8 mb-1">Jumlah</div>
                                        <div class="text-gray-900 fw-bolder fs-4">
                                            Rp {{ number_format($deposit->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <!--end::Amount-->

                                    <!--begin::Details Grid-->
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="text-gray-500 fs-8 mb-1">User</div>
                                            <div class="text-gray-800 fw-semibold fs-7">{{ $deposit->user->phone }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-gray-500 fs-8 mb-1">Date</div>
                                            <div class="text-gray-800 fw-semibold fs-7">
                                                {{ $deposit->created_at->format('d M Y') }}<br>
                                                <span class="text-muted">{{ $deposit->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Details Grid-->

                                    <!--begin::Payment Method-->
                                    <div class="bg-light rounded p-3 mb-3">
                                        <div class="text-gray-500 fs-8 mb-2">Payment Method</div>
                                        <div class="d-flex align-items-center">
                                            <i class="ki-outline ki-credit-cart fs-2 text-primary me-2"></i>
                                            <div>
                                                <div class="text-gray-800 fw-bold fs-7">{{ $deposit->payment_method }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Payment Method-->

                                    <!--begin::Actions-->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.deposit.edit', $deposit->id) }}"
                                            class="btn btn-sm btn-light-primary flex-grow-1">
                                            <i class="ki-outline ki-eye fs-5 me-1"></i>
                                            View Detail
                                        </a>
                                        <button class="btn btn-sm btn-light-danger"
                                            onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus deposit ini?')) { document.getElementById('delete-form-mobile-{{ $deposit->id }}').submit(); }">
                                            <i class="ki-outline ki-trash fs-5"></i>
                                        </button>
                                        <form id="delete-form-mobile-{{ $deposit->id }}"
                                            action="{{ route('admin.deposit.destroy', $deposit->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                <div class="text-gray-800 fw-bold mb-1">No deposits found</div>
                                <div class="text-muted">Start by adding a new deposit</div>
                            </div>
                        @endforelse
                    </div>
                    <!--end::Mobile Cards-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->

    <!--begin::Modal - Add Deposit-->
    <div class="modal fade" id="kt_modal_add_deposit" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px mx-3 mx-sm-auto">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="{{ route('admin.deposit.store') }}" method="POST"
                    id="kt_modal_add_deposit_form">
                    @csrf
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_deposit_header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Tambah Deposit</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-deposits-modal-action="close"
                            data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-5 px-3 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y" id="kt_modal_add_deposit_scroll">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">No HP User</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" name="phone" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Masukkan nomor HP user" value="{{ old('phone') }}" required />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Jumlah</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="number" name="amount" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Masukkan jumlah deposit" value="{{ old('amount') }}" min="0"
                                    required />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">Metode Pembayaran</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select name="method" class="form-select form-select-solid" required>
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="bank_transfer"
                                        {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="wallet_qris" {{ old('method') == 'wallet_qris' ? 'selected' : '' }}>
                                        Wallet QRIS</option>
                                    <option value="cash" {{ old('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fw-semibold fs-6 mb-2">Catatan (Opsional)</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea name="notes" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Tambahkan catatan jika diperlukan" rows="3">{{ old('notes') }}</textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--begin::Button-->
                        <button type="reset" id="kt_modal_add_deposit_cancel" class="btn btn-light me-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_deposit_submit" class="btn btn-primary">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>
    <!--end::Modal - Add Deposit-->
@endsection

@push('styles')
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 991px) {
            .app-toolbar-wrapper {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .page-title {
                margin-bottom: 0.5rem;
            }

            .card-header {
                padding: 1rem;
                flex-wrap: wrap;
                gap: 1rem;
            }

            .card-title {
                flex: 1 1 auto;
            }

            .card-title .form-control {
                width: 100% !important;
            }

            .card-toolbar {
                flex-shrink: 0;
            }

            .card-body {
                padding: 1rem;
            }

            /* Mobile Card Styles */
            .mobile-deposit-card {
                border: 1px solid #e4e6ef;
                border-radius: 0.625rem;
                transition: all 0.3s ease;
            }

            .mobile-deposit-card:hover {
                box-shadow: 0 0.5rem 1.5rem 0.5rem rgba(0, 0, 0, 0.075);
                transform: translateY(-2px);
            }

            /* Badge Adjustments */
            .badge {
                padding: 0.35rem 0.65rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            /* Search Input */
            .form-control-solid {
                background-color: #f5f8fa;
                border: 1px solid #e4e6ef;
            }

            .form-control-solid:focus {
                background-color: #ffffff;
                border-color: #009ef7;
            }

            /* Button in toolbar */
            .btn-primary {
                min-width: 44px;
                min-height: 44px;
            }
        }

        @media (max-width: 576px) {
            .app-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card {
                margin: 0;
                border-radius: 0.5rem;
            }

            .page-heading {
                font-size: 1.5rem !important;
            }

            .breadcrumb {
                font-size: 0.75rem !important;
            }

            /* Mobile Card Compact */
            .mobile-deposit-card {
                margin-bottom: 0.75rem !important;
            }

            .mobile-deposit-card .card-body {
                padding: 1rem !important;
            }

            /* Button Adjustments */
            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            /* Modal Adjustments */
            .modal-body {
                padding: 1.5rem 1rem !important;
            }

            .modal-header {
                padding: 1rem;
            }
        }

        /* Animation for mobile cards */
        @media (max-width: 991px) {
            .mobile-deposit-card {
                animation: slideIn 0.3s ease-out;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        }

        /* Status badge colors */
        .badge-success {
            background-color: #e8fff3;
            color: #50cd89;
        }

        .badge-warning {
            background-color: #fff8dd;
            color: #ffc700;
        }

        .badge-danger {
            background-color: #fff5f8;
            color: #f1416c;
        }

        .badge-secondary {
            background-color: #f5f8fa;
            color: #a1a5b7;
        }

        /* Alert responsive */
        @media (max-width: 576px) {
            .alert {
                padding: 1rem !important;
                margin-bottom: 1rem !important;
            }

            .alert i {
                font-size: 2rem !important;
            }

            .alert h4 {
                font-size: 1rem !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal close
            document.querySelectorAll('[data-kt-deposits-modal-action="close"]').forEach(function(element) {
                element.addEventListener('click', function() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById(
                        'kt_modal_add_deposit'));
                    if (modal) modal.hide();
                });
            });

            // Search functionality
            const searchInput = document.querySelector('[data-kt-customer-table-filter="search"]');

            // Desktop table
            const desktopTable = document.querySelector('#kt_customers_table tbody');
            const desktopRows = desktopTable ? desktopTable.querySelectorAll('tr:not([class*="no-data"])') : [];

            // Mobile cards
            const mobileList = document.querySelector('.mobile-deposit-list');
            const mobileCards = mobileList ? mobileList.querySelectorAll('.mobile-deposit-card') : [];

            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();

                    // Search desktop table
                    if (desktopRows.length > 0) {
                        desktopRows.forEach(row => {
                            if (row.querySelector('td[colspan]')) {
                                return;
                            }

                            const reference = row.querySelector('td:first-child .text-gray-800')
                                ?.textContent.toLowerCase().trim() || '';
                            const userPhone = row.querySelector('td:nth-child(2) .text-gray-800')
                                ?.textContent.toLowerCase().trim() || '';

                            if (reference.includes(searchTerm) || userPhone.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Show "no data" message for desktop
                        const visibleDesktopRows = Array.from(desktopRows).filter(row =>
                            row.style.display !== 'none' && !row.querySelector('td[colspan]')
                        );

                        const noDataRow = desktopTable.querySelector('.no-data-row');
                        if (visibleDesktopRows.length === 0 && !noDataRow) {
                            const colspan = desktopTable.closest('table').querySelectorAll('thead th')
                                .length;
                            const emptyRow = document.createElement('tr');
                            emptyRow.className = 'no-data-row';
                            emptyRow.innerHTML = `
                                <td colspan="${colspan}" class="text-center py-10">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                        <div class="text-gray-800 fw-bold mb-1">No matching records found</div>
                                        <div class="text-muted">Try adjusting your search criteria</div>
                                    </div>
                                </td>
                            `;
                            desktopTable.appendChild(emptyRow);
                        } else if (visibleDesktopRows.length > 0 && noDataRow) {
                            noDataRow.remove();
                        }
                    }

                    // Search mobile cards
                    if (mobileCards.length > 0) {
                        mobileCards.forEach(card => {
                            const reference = card.querySelector('.text-gray-800.fw-bold.fs-6')
                                ?.textContent.toLowerCase().trim() || '';
                            const userPhone = card.querySelectorAll(
                                    '.text-gray-800.fw-semibold.fs-7')[0]?.textContent.toLowerCase()
                                .trim() || '';

                            if (reference.includes(searchTerm) || userPhone.includes(searchTerm)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        // Show "no data" message for mobile
                        const visibleMobileCards = Array.from(mobileCards).filter(card => card.style
                            .display !== 'none');
                        const noDataMobile = mobileList.querySelector('.no-data-mobile');

                        if (visibleMobileCards.length === 0 && !noDataMobile) {
                            const emptyDiv = document.createElement('div');
                            emptyDiv.className = 'text-center py-10 no-data-mobile';
                            emptyDiv.innerHTML = `
                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                <div class="text-gray-800 fw-bold mb-1">No matching records found</div>
                                <div class="text-muted">Try adjusting your search criteria</div>
                            `;
                            mobileList.appendChild(emptyDiv);
                        } else if (visibleMobileCards.length > 0 && noDataMobile) {
                            noDataMobile.remove();
                        }
                    }
                });
            }

            // Handle form validation
            const form = document.getElementById('kt_modal_add_deposit_form');
            const submitButton = document.getElementById('kt_modal_add_deposit_submit');

            if (form && submitButton) {
                submitButton.addEventListener('click', function(e) {
                    const phone = form.querySelector('input[name="phone"]').value;
                    const amount = form.querySelector('input[name="amount"]').value;
                    const method = form.querySelector('select[name="method"]').value;

                    if (!phone || !amount || !method) {
                        e.preventDefault();
                        alert('Mohon isi semua field yang wajib diisi');
                        return false;
                    }
                });
            }
        });
    </script>
@endpush
