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
                        Withdraw</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Withdraw</li>
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
                            <input type="text" data-kt-withdraw-table-filter="search"
                                class="form-control form-control-solid w-100 w-md-250px ps-12"
                                placeholder="Search Withdrawals" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table (Desktop)-->
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Reference</th>
                                    <th class="min-w-125px">User</th>
                                    <th class="min-w-125px">Amount</th>
                                    <th class="min-w-125px">Wallet</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="text-end min-w-70px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($withdrawTransactions as $transaction)
                                    <tr data-transaction-id="{{ $transaction->id }}">
                                        <td>
                                            <div class="text-gray-800 text-hover-primary fw-bold">
                                                {{ $transaction->reference }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800 text-hover-primary fw-bold">
                                                {{ $transaction->user->phone }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-gray-800 fw-bold">
                                                Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($transaction->wallet)
                                                @if ($transaction->wallet->wallet_type == 'ewallet')
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ $transaction->wallet->ewallet_provider }}</span>
                                                        <small
                                                            class="text-muted">{{ $transaction->wallet->ewallet_number }}</small>
                                                        <small
                                                            class="text-muted">{{ $transaction->wallet->ewallet_name }}</small>
                                                    </div>
                                                @elseif ($transaction->wallet->wallet_type == 'bank')
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="text-gray-800 fw-bold">{{ $transaction->wallet->bank_name }}</span>
                                                        <small
                                                            class="text-muted">{{ $transaction->wallet->bank_account }}</small>
                                                        <small
                                                            class="text-muted">{{ $transaction->wallet->account_name }}</small>
                                                    </div>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-light-{{ $transaction->status == 'success' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }} fw-bold">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
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
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('admin.withdraw.show', $transaction->id) }}"
                                                        class="menu-link px-3">Detail</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 text-danger"
                                                        data-kt-customer-table-filter="delete_row">Delete</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                                <div class="text-gray-800 fw-bold mb-1">No withdraw transactions found</div>
                                                <div class="text-muted">Try adjusting your search or filter criteria</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table (Desktop)-->

                    <!--begin::Mobile Cards-->
                    <div class="d-lg-none mobile-withdraw-list">
                        @forelse($withdrawTransactions as $transaction)
                            <div class="card mobile-withdraw-card mb-4 shadow-sm"
                                data-transaction-id="{{ $transaction->id }}">
                                <div class="card-body p-4">
                                    <!--begin::Header-->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <div class="text-gray-500 fs-8 mb-1">Reference</div>
                                            <div class="text-gray-800 fw-bold fs-6">{{ $transaction->reference }}</div>
                                        </div>
                                        <span
                                            class="badge badge-{{ $transaction->status == 'success' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }} fs-7">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Amount-->
                                    <div class="mb-3 pb-3 border-bottom border-gray-300">
                                        <div class="text-gray-500 fs-8 mb-1">Amount</div>
                                        <div class="text-gray-900 fw-bolder fs-4">
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <!--end::Amount-->

                                    <!--begin::Details Grid-->
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="text-gray-500 fs-8 mb-1">User</div>
                                            <div class="text-gray-800 fw-semibold fs-7">{{ $transaction->user->phone }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-gray-500 fs-8 mb-1">Date</div>
                                            <div class="text-gray-800 fw-semibold fs-7">
                                                {{ $transaction->created_at->format('d M Y') }}<br>
                                                <span
                                                    class="text-muted">{{ $transaction->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Details Grid-->

                                    <!--begin::Wallet Info-->
                                    @if ($transaction->wallet)
                                        <div class="bg-light rounded p-3 mb-3">
                                            <div class="text-gray-500 fs-8 mb-2">Wallet Details</div>
                                            @if ($transaction->wallet->wallet_type == 'ewallet')
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">
                                                            {{ $transaction->wallet->ewallet_provider }}</div>
                                                        <div class="text-muted fs-8">
                                                            {{ $transaction->wallet->ewallet_number }}</div>
                                                        <div class="text-muted fs-8">
                                                            {{ $transaction->wallet->ewallet_name }}</div>
                                                    </div>
                                                </div>
                                            @elseif ($transaction->wallet->wallet_type == 'bank')
                                                <div class="d-flex align-items-center">
                                                    <i class="ki-outline ki-bank fs-2 text-primary me-2"></i>
                                                    <div>
                                                        <div class="text-gray-800 fw-bold fs-7">
                                                            {{ $transaction->wallet->bank_name }}</div>
                                                        <div class="text-muted fs-8">
                                                            {{ $transaction->wallet->bank_account }}</div>
                                                        <div class="text-muted fs-8">
                                                            {{ $transaction->wallet->account_name }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    <!--end::Wallet Info-->

                                    <!--begin::Actions-->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.withdraw.show', $transaction->id) }}"
                                            class="btn btn-sm btn-light-primary flex-grow-1">
                                            <i class="ki-outline ki-eye fs-5 me-1"></i>
                                            View Detail
                                        </a>
                                        <button class="btn btn-sm btn-light-danger"
                                            data-kt-customer-table-filter="delete_row">
                                            <i class="ki-outline ki-trash fs-5"></i>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                <div class="text-gray-800 fw-bold mb-1">No withdraw transactions found</div>
                                <div class="text-muted">Try adjusting your search or filter criteria</div>
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
            }

            .card-body {
                padding: 1rem;
            }

            /* Mobile Card Styles */
            .mobile-withdraw-card {
                border: 1px solid #e4e6ef;
                border-radius: 0.625rem;
                transition: all 0.3s ease;
            }

            .mobile-withdraw-card:hover {
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
            .mobile-withdraw-card {
                margin-bottom: 0.75rem !important;
            }

            .mobile-withdraw-card .card-body {
                padding: 1rem !important;
            }

            /* Button Adjustments */
            .btn-sm {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }
        }

        /* Animation for mobile cards */
        @media (max-width: 991px) {
            .mobile-withdraw-card {
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

        /* Empty state styling */
        .no-data-mobile {
            padding: 3rem 1rem;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Search functionality untuk withdraw transactions
        const searchInput = document.querySelector('[data-kt-withdraw-table-filter="search"]');

        // Desktop table
        const desktopTable = document.querySelector('#kt_customers_table tbody');
        const desktopRows = desktopTable ? desktopTable.querySelectorAll('tr:not([class*="no-data"])') : [];

        // Mobile cards
        const mobileList = document.querySelector('.mobile-withdraw-list');
        const mobileCards = mobileList ? mobileList.querySelectorAll('.mobile-withdraw-card') : [];

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();

            // Search desktop table
            if (desktopRows.length > 0) {
                desktopRows.forEach(row => {
                    if (row.querySelector('td[colspan]')) {
                        return;
                    }

                    const reference = row.querySelector('td:first-child .text-gray-800')?.textContent
                        .toLowerCase().trim() || '';
                    const userPhone = row.querySelector('td:nth-child(2) .text-gray-800')?.textContent
                        .toLowerCase().trim() || '';

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
                    const colspan = desktopTable.closest('table').querySelectorAll('thead th').length;
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
                    const reference = card.querySelector('.text-gray-800.fw-bold.fs-6')?.textContent
                        .toLowerCase().trim() || '';
                    const userPhone = card.querySelectorAll('.text-gray-800.fw-semibold.fs-7')[0]
                        ?.textContent.toLowerCase().trim() || '';

                    if (reference.includes(searchTerm) || userPhone.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show "no data" message for mobile
                const visibleMobileCards = Array.from(mobileCards).filter(card => card.style.display !== 'none');
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

        // Delete functionality
        document.querySelectorAll('[data-kt-customer-table-filter="delete_row"]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const card = this.closest('.mobile-withdraw-card');
                const row = this.closest('tr');
                const transactionId = card ? card.dataset.transactionId : row.dataset.transactionId;

                if (!transactionId) {
                    console.error('Transaction ID not found');
                    return;
                }

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Withdraw transaction akan dihapus permanen beserta semua transaksi terkait!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form and submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/withdraw/${transactionId}`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
