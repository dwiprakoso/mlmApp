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
                        Transaction</h1>
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
                        <li class="breadcrumb-item text-muted">Transaction</li>
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

            <!-- Alert Messages -->
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Success</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Error</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                            <input type="text" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-100 w-md-250px ps-12"
                                placeholder="Search Report" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->

                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-column flex-sm-row justify-content-end gap-2">
                        <div class="d-flex gap-2 w-100 w-sm-auto">
                            <!--begin::Filter-->
                            <div class="flex-fill flex-sm-grow-0 w-sm-150px">
                                <!--begin::Select2-->
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                    data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="success">Success</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>
                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Filter-->
                            <!--begin::Export dropdown-->
                            <button type="button" class="btn btn-light-primary flex-shrink-0" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                <i class="ki-outline ki-exit-up fs-2 d-none d-sm-inline"></i>
                                <span class="d-sm-none">Export</span>
                                <span class="d-none d-sm-inline">Export Report</span>
                            </button>
                            <!--begin::Menu-->
                            <div id="kt_ecommerce_report_customer_orders_export_menu"
                                class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                                data-kt-menu="true">
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">Copy to
                                        clipboard</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">Export as
                                        Excel</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as
                                        CSV</a>
                                </div>
                                <!--end::Menu item-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as
                                        PDF</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                            <!--end::Menu-->
                            <!--end::Export dropdown-->
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5"
                            id="kt_ecommerce_report_customer_orders_table">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-100px">Product</th>
                                    <th class="min-w-100px d-none d-lg-table-cell">Reference</th>
                                    <th class="min-w-100px d-none d-md-table-cell">Customer Name</th>
                                    <th class="min-w-100px d-none d-xl-table-cell">Phone</th>
                                    <th class="text-end min-w-75px">Amount</th>
                                    <th class="min-w-100px d-none d-sm-table-cell">Status</th>
                                    <th class="min-w-100px d-none d-lg-table-cell">Created At</th>
                                    <th class="text-end min-w-100px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                                @forelse($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="text-gray-900 fw-bold">{{ $transaction->product->name ?? 'N/A' }}</span>

                                                <!-- Mobile-only info -->
                                                <div class="d-md-none mt-1">
                                                    <div class="text-muted fs-8 mb-1">
                                                        {{ $transaction->user->name ?? 'N/A' }}</div>
                                                </div>

                                                <!-- Mobile reference and phone -->
                                                <div class="d-lg-none mt-1">
                                                    <div class="text-muted fs-8">Ref: {{ $transaction->reference ?? 'N/A' }}
                                                    </div>
                                                </div>

                                                <div class="d-xl-none mt-1">
                                                    <div class="text-muted fs-8">{{ $transaction->user->phone ?? 'N/A' }}
                                                    </div>
                                                </div>

                                                <!-- Mobile status -->
                                                <div class="d-sm-none mt-2">
                                                    @switch($transaction->status)
                                                        @case('success')
                                                            <div class="badge badge-light-success fs-8">Success</div>
                                                        @break

                                                        @case('pending')
                                                            <div class="badge badge-light-warning fs-8">Pending</div>
                                                        @break

                                                        @case('failed')
                                                            <div class="badge badge-light-danger fs-8">Failed</div>
                                                        @break

                                                        @default
                                                            <div class="badge badge-light-secondary fs-8">
                                                                {{ ucfirst($transaction->status) }}</div>
                                                    @endswitch
                                                </div>

                                                <!-- Mobile date -->
                                                <div class="d-lg-none mt-1">
                                                    <div class="text-muted fs-8">
                                                        {{ $transaction->created_at->format('d M Y, h:i A') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <span class="text-gray-900">{{ $transaction->reference ?? 'N/A' }}</span>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <span class="text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</span>
                                        </td>
                                        <td class="d-none d-xl-table-cell">
                                            <span class="text-gray-900">{{ $transaction->user->phone ?? 'N/A' }}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex flex-column align-items-end">
                                                <span class="text-gray-900 fw-bold">
                                                    <span class="d-none d-sm-inline">Rp
                                                    </span>{{ number_format($transaction->amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="d-none d-sm-table-cell">
                                            @switch($transaction->status)
                                                @case('success')
                                                    <div class="badge badge-light-success">Success</div>
                                                @break

                                                @case('pending')
                                                    <div class="badge badge-light-warning">Pending</div>
                                                @break

                                                @case('failed')
                                                    <div class="badge badge-light-danger">Failed</div>
                                                @break

                                                @default
                                                    <div class="badge badge-light-secondary">{{ ucfirst($transaction->status) }}
                                                    </div>
                                            @endswitch
                                        </td>
                                        <td class="d-none d-lg-table-cell">
                                            <span
                                                class="text-gray-900">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
                                        </td>
                                        <td class="text-end">
                                            @if ($transaction->status === 'pending')
                                                <div class="d-flex justify-content-end gap-2">
                                                    <!-- Approve Button -->
                                                    <form
                                                        action="{{ route('admin.transaction.approve', $transaction->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Approve">
                                                            <i class="ki-outline ki-check fs-5"></i>
                                                            <span class="d-none d-md-inline ms-1">Approve</span>
                                                        </button>
                                                    </form>

                                                    <!-- Reject Button -->
                                                    <form
                                                        action="{{ route('admin.transaction.reject', $transaction->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menolak transaksi ini?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            title="Reject">
                                                            <i class="ki-outline ki-cross fs-5"></i>
                                                            <span class="d-none d-md-inline ms-1">Reject</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-muted fs-8">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-10">
                                                <div class="text-gray-600">No purchase transactions found</div>
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
                <!--end::Products-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

        @push('scripts')
            <script>
                // Auto dismiss alerts after 5 seconds
                document.addEventListener('DOMContentLoaded', function() {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        setTimeout(() => {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }, 5000);
                    });
                });
            </script>
        @endpush
    @endsection
