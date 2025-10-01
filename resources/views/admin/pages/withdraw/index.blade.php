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
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Refference</th>
                                    <th class="min-w-125px d-none d-lg-table-cell">User</th>
                                    <th class="min-w-125px d-none d-lg-table-cell">Amount</th>
                                    <th class="min-w-125px d-none d-xl-table-cell">Wallet</th>
                                    <th class="min-w-100px d-none d-md-table-cell">Status</th>
                                    <th class="min-w-125px d-none d-lg-table-cell">Date</th>
                                    <th class="text-end min-w-70px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($withdrawTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="text-gray-800 text-hover-primary fw-bold mb-1">
                                                    {{ $transaction->reference }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="text-gray-800 text-hover-primary fw-bold mb-1">
                                                    {{ $transaction->user->phone }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <div class="text-gray-800 text-hover-primary fw-bold mb-1">
                                                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-xl-table-cell">
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
                                        <td class="d-none d-md-table-cell">
                                            <span
                                                class="badge badge-light-{{ $transaction->status == 'success' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }} fw-bold">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            {{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                        <td class="text-end">
                                            <a href="#"
                                                class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                <span class="d-none d-sm-inline">Actions</span>
                                                <i class="ki-outline ki-dots-vertical d-sm-none fs-5"></i>
                                                <i class="ki-outline ki-down fs-5 ms-1 d-none d-sm-inline"></i>
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
                                        <td colspan="8" class="text-center py-10">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="ki-outline ki-file-deleted fs-3x text-muted mb-3"></i>
                                                <div class="text-gray-800 fw-bold mb-1">No withdraw transactions found
                                                </div>
                                                <div class="text-muted">Try adjusting your search or filter criteria</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
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
        @media (max-width: 768px) {
            .app-toolbar-wrapper {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .page-title {
                margin-bottom: 1rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
            }

            .card-title {
                width: 100%;
                margin-bottom: 1rem;
            }

            .card-toolbar {
                width: 100%;
            }

            .table-responsive {
                border: none;
                margin: -1rem;
                padding: 1rem;
            }

            /* Mobile table styling */
            .table td {
                padding: 1rem 0.75rem;
            }

            /* Filter menu responsive */
            .menu.menu-sub.menu-sub-dropdown {
                position: fixed !important;
                left: 1rem !important;
                right: 1rem !important;
                width: auto !important;
                max-width: none !important;
            }
        }

        @media (max-width: 576px) {
            .app-container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card {
                margin: 0 -0.5rem;
            }

            .card-body {
                padding: 1rem;
            }

            /* Stack buttons vertically on very small screens */
            .card-toolbar .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .card-toolbar .btn {
                margin-bottom: 0.5rem;
                margin-right: 0 !important;
            }

            .card-toolbar .btn:last-child {
                margin-bottom: 0;
            }
        }

        /* Improve mobile table readability */
        @media (max-width: 991px) {
            .table td .d-flex.flex-column>div {
                margin-bottom: 0.25rem;
            }

            .table td .d-flex.flex-column>div:last-child {
                margin-bottom: 0;
            }

            .badge {
                font-size: 0.75rem;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        // Search functionality untuk withdraw transactions
        const searchInput = document.querySelector('[data-kt-withdraw-table-filter="search"]');
        const tableBody = document.querySelector('#kt_customers_table tbody');
        const tableRows = tableBody.querySelectorAll('tr');

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();

            tableRows.forEach(row => {
                // Skip jika ini adalah empty state row (punya colspan)
                if (row.querySelector('td[colspan]')) {
                    return;
                }

                // Ambil reference (kolom pertama)
                const reference = row.querySelector('td:first-child .text-gray-800')?.textContent
                    .toLowerCase().trim() || '';

                // Ambil user phone (kolom kedua)
                const userPhone = row.querySelector('td:nth-child(2) .text-gray-800')?.textContent
                    .toLowerCase().trim() || '';

                // Search hanya di reference dan user phone
                if (reference.includes(searchTerm) || userPhone.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan pesan "No matching records found" jika semua row tersembunyi
            const visibleRows = Array.from(tableRows).filter(row => {
                return row.style.display !== 'none' && !row.querySelector('td[colspan]');
            });

            const noDataRow = tableBody.querySelector('.no-data-row');

            if (visibleRows.length === 0 && !noDataRow) {
                const colspan = tableBody.closest('table').querySelectorAll('thead th').length;
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
                tableBody.appendChild(emptyRow);
            } else if (visibleRows.length > 0 && noDataRow) {
                noDataRow.remove();
            }
        });
    </script>
@endpush
