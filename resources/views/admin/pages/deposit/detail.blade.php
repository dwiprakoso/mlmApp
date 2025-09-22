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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Detail
                        Deposits</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.deposit.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.deposit.index') }}" class="text-muted text-hover-primary">Deposits</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Detail</li>
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
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                    <!--begin::Card-->
                    <div class="card card-flush pt-3 mb-5 mb-xl-10">
                        <!--begin::Card body-->
                        <div class="card-body pt-3">
                            <!--begin::Section-->
                            <div class="mb-0">
                                <!--begin::Title-->
                                <h5 class="mb-4">Proof of Payment:</h5>
                                <!--end::Title-->

                                @if ($deposit->payment_proof)
                                    <div class="mb-7">
                                        <img src="{{ Storage::url($deposit->payment_proof) }}" alt="Proof of Payment"
                                            class="mw-100" style="max-height: 400px;">
                                    </div>
                                @else
                                    <div class="alert alert-light-info d-flex align-items-center p-5">
                                        <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                                        <div class="d-flex flex-column">
                                            <span>Tidak ada bukti pembayaran (Deposit dibuat manual oleh admin)</span>
                                        </div>
                                    </div>
                                @endif
                                <!--begin::Action buttons-->
                                @if (in_array($deposit->status, ['pending', 'waiting_confirmation']))
                                    <div class="d-flex gap-3 mt-5">
                                        <form action="{{ route('admin.deposit.confirm', $deposit->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success"
                                                onclick="return confirm('Apakah Anda yakin ingin mengkonfirmasi deposit ini?')">
                                                <i class="ki-outline ki-check fs-2"></i>
                                                Konfirmasi Pembayaran
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.deposit.reject', $deposit->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menolak deposit ini?')">
                                                <i class="ki-outline ki-cross fs-2"></i>
                                                Tolak Pembayaran
                                            </button>
                                        </form>
                                    </div>
                                @endif
                                <!--end::Action buttons-->
                            </div>
                            <!--end::Section-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                    <!--begin::Card-->
                    <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary"
                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                        data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto"
                        data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <!--begin::Card title-->
                            <div class="card-title">
                                <h2>Deposit Summary</h2>
                            </div>
                            <!--end::Card title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0 fs-6">
                            <!--begin::Section-->
                            <div class="mb-7">
                                <!--begin::Details-->
                                <div class="d-flex align-items-center">
                                    <!--begin::Avatar-->
                                    <div class="symbol symbol-60px symbol-circle me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold">
                                            {{ strtoupper(substr($deposit->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <!--end::Avatar-->
                                    <!--begin::Info-->
                                    <div class="d-flex flex-column">
                                        <!--begin::Name-->
                                        <a href="#"
                                            class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">{{ $deposit->user->name }}</a>
                                        <!--end::Name-->
                                        <!--begin::Phone-->
                                        <span class="fw-semibold text-gray-600">{{ $deposit->user->phone }}</span>
                                        <!--end::Phone-->
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Seperator-->
                            <div class="separator separator-dashed mb-7"></div>
                            <!--end::Seperator-->
                            <!--begin::Section-->
                            <div class="mb-7">
                                <!--begin::Title-->
                                <h5 class="mb-4">Payment Details</h5>
                                <!--end::Title-->
                                <!--begin::Details-->
                                <div class="mb-0">
                                    <!--begin::Amount-->
                                    <div class="fw-bold text-gray-600 d-flex justify-content-between mb-3">
                                        <span>Amount:</span>
                                        <span class="text-gray-800">Rp
                                            {{ number_format($deposit->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <!--end::Amount-->
                                    <!--begin::Method-->
                                    <div class="fw-semibold text-gray-600 d-flex justify-content-between mb-3">
                                        <span>Method:</span>
                                        <span class="text-gray-800">{{ $deposit->payment_method }}</span>
                                    </div>
                                    <!--end::Method-->
                                </div>
                                <!--end::Details-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Seperator-->
                            <div class="separator separator-dashed mb-7"></div>
                            <!--end::Seperator-->
                            <!--begin::Section-->
                            <div class="mb-10">
                                <!--begin::Title-->
                                <h5 class="mb-4">Deposit Details</h5>
                                <!--end::Title-->
                                <!--begin::Details-->
                                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Deposit ID:</td>
                                        <td class="text-gray-800">#{{ $deposit->reference }}</td>
                                    </tr>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Created:</td>
                                        <td class="text-gray-800">{{ $deposit->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <!--end::Row-->
                                    <!--begin::Row-->
                                    <tr>
                                        <td class="text-gray-500">Status:</td>
                                        <td>
                                            @if ($deposit->status == 'success')
                                                <span class="badge badge-light-success">Success</span>
                                            @elseif(in_array($deposit->status, ['pending', 'waiting_confirmation']))
                                                <span
                                                    class="badge badge-light-warning">{{ ucfirst(str_replace('_', ' ', $deposit->status)) }}</span>
                                            @elseif($deposit->status == 'failed')
                                                <span class="badge badge-light-danger">Failed</span>
                                            @else
                                                <span class="badge badge-light-secondary">{{ $deposit->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!--end::Row-->
                                    @if ($deposit->approved_by)
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-500">Processed by:</td>
                                            <td class="text-gray-800">Admin</td>
                                        </tr>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <tr>
                                            <td class="text-gray-500">Processed at:</td>
                                            <td class="text-gray-800">
                                                {{ $deposit->approved_at ? $deposit->approved_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                        </tr>
                                        <!--end::Row-->
                                    @endif
                                </table>
                                <!--end::Details-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Actions-->
                            <div class="mb-0">
                                <a href="{{ route('admin.deposit.index') }}" class="btn btn-light w-100">
                                    <i class="ki-outline ki-arrow-left fs-2"></i>
                                    Kembali ke List Deposit
                                </a>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
