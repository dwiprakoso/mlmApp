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
                                class="form-control form-control-solid w-250px ps-12" placeholder="Search Customers" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                            <!--begin::Filter-->
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                data-kt-menu-placement="bottom-end">
                                <i class="ki-outline ki-filter fs-2"></i>Filter</button>
                            <!--begin::Menu 1-->
                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true"
                                id="kt-toolbar-filter">
                                <!--begin::Header-->
                                <div class="px-7 py-5">
                                    <div class="fs-4 text-gray-900 fw-bold">Filter Options</div>
                                </div>
                                <!--end::Header-->
                                <!--begin::Separator-->
                                <div class="separator border-gray-200"></div>
                                <!--end::Separator-->
                                <!--begin::Content-->
                                <div class="px-7 py-5">
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Month:</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                            data-placeholder="Select option" data-allow-clear="true"
                                            data-kt-customer-table-filter="month" data-dropdown-parent="#kt-toolbar-filter">
                                            <option></option>
                                            <option value="aug">August</option>
                                            <option value="sep">September</option>
                                            <option value="oct">October</option>
                                            <option value="nov">November</option>
                                            <option value="dec">December</option>
                                        </select>
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-10">
                                        <!--begin::Label-->
                                        <label class="form-label fs-5 fw-semibold mb-3">Payment Type:</label>
                                        <!--end::Label-->
                                        <!--begin::Options-->
                                        <div class="d-flex flex-column flex-wrap fw-semibold"
                                            data-kt-customer-table-filter="payment_type">
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    value="all" checked="checked" />
                                                <span class="form-check-label text-gray-600">All</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label
                                                class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    value="visa" />
                                                <span class="form-check-label text-gray-600">Visa</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label class="form-check form-check-sm form-check-custom form-check-solid mb-3">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    value="mastercard" />
                                                <span class="form-check-label text-gray-600">Mastercard</span>
                                            </label>
                                            <!--end::Option-->
                                            <!--begin::Option-->
                                            <label class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input" type="radio" name="payment_type"
                                                    value="american_express" />
                                                <span class="form-check-label text-gray-600">American Express</span>
                                            </label>
                                            <!--end::Option-->
                                        </div>
                                        <!--end::Options-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                                            data-kt-menu-dismiss="true" data-kt-customer-table-filter="reset">Reset</button>
                                        <button type="submit" class="btn btn-primary" data-kt-menu-dismiss="true"
                                            data-kt-customer-table-filter="filter">Apply</button>
                                    </div>
                                    <!--end::Actions-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Menu 1-->
                            <!--end::Filter-->
                            <!--begin::Export-->
                            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                data-bs-target="#kt_customers_export_modal">
                                <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
                            <!--end::Export-->
                            <!--begin::Add deposit-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_deposit">Tambah Deposit</button>
                            <!--end::Add deposit-->
                        </div>
                        <!--end::Toolbar-->
                        <!--begin::Group actions-->
                        <div class="d-flex justify-content-end align-items-center d-none"
                            data-kt-customer-table-toolbar="selected">
                            <div class="fw-bold me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected
                            </div>
                            <button type="button" class="btn btn-danger"
                                data-kt-customer-table-select="delete_selected">Delete Selected</button>
                        </div>
                        <!--end::Group actions-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">Nama</th>
                                <th class="min-w-125px">No Hp</th>
                                <th class="min-w-125px">Jumlah</th>
                                <th class="min-w-125px">Metode Pembayaran</th>
                                <th class="min-w-125px">Status</th>
                                <th class="min-w-125px">Waktu</th>
                                <th class="text-end min-w-70px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach ($deposits as $deposit)
                                <tr>
                                    <td>
                                        <div class="text-gray-800 text-hover-primary mb-1">{{ $deposit->user->name }}</div>
                                    </td>
                                    <td>
                                        <div class="text-gray-800 text-hover-primary mb-1">{{ $deposit->user->phone }}
                                        </div>
                                    </td>
                                    <td data-filter="mastercard">
                                        <div class="text-gray-800 text-hover-primary mb-1">Rp
                                            {{ number_format($deposit->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-gray-800 text-hover-primary mb-1">{{ $deposit->payment_method }}
                                        </div>
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
                                        <div class="text-gray-800 text-hover-primary mb-1">
                                            {{ $deposit->created_at instanceof \Carbon\Carbon ? $deposit->created_at->format('d/m/Y H:i') : \Carbon\Carbon::parse($deposit->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <a href="#"
                                            class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="{{ route('admin.deposit.edit', $deposit->id) }}"
                                                    class="menu-link px-3">Detail</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3"
                                                    data-kt-customer-table-filter="delete_row">Delete</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!--end::Table-->
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
        <div class="modal-dialog modal-dialog-centered mw-650px">
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
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-deposits-modal-action="close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_deposit_scroll" data-kt-scroll="true"
                            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_add_deposit_header"
                            data-kt-scroll-wrappers="#kt_modal_add_deposit_scroll" data-kt-scroll-offset="300px">

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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal close
            document.querySelectorAll('[data-kt-deposits-modal-action="close"]').forEach(function(element) {
                element.addEventListener('click', function() {
                    document.getElementById('kt_modal_add_deposit').style.display = 'none';
                });
            });

            // Handle form validation (optional)
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
