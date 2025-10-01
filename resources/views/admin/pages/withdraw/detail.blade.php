@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Detail
                        Withdraw</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.withdraw.index') }}" class="text-muted text-hover-primary">Withdraw</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Detail</li>
                    </ul>
                </div>
            </div>
        </div>
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
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="d-flex flex-column flex-lg-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
                    <!--begin::Card-->
                    <div class="card card-flush pt-3 mb-5 mb-xl-10">
                        <div class="card-body pt-3">
                            <!--begin::Section-->
                            <div class="mb-0">
                                <!--begin::Title-->
                                <h5 class="mb-4">Balance Allocation:</h5>
                                <!--end::Title-->

                                <!--begin::Allocation Details-->
                                @if (isset($withdraw->allocation_details) && $withdraw->allocation_details->count() > 0)
                                    <div class="mb-7">
                                        @foreach ($withdraw->allocation_details as $allocation)
                                            <div class="d-flex align-items-center bg-light-primary rounded p-4 mb-3">
                                                <div class="flex-grow-1">
                                                    <div class="fw-bold text-gray-800 fs-5">
                                                        {{ ucfirst($allocation['source'] ?? 'Unknown') }}
                                                    </div>
                                                    <div class="text-gray-600 fs-7">Source Balance Type</div>
                                                </div>
                                                <div class="fw-bold text-primary fs-4">
                                                    Rp {{ number_format($allocation['amount'], 0, ',', '.') }}
                                                </div>
                                            </div>
                                        @endforeach

                                        <!--begin::Total-->
                                        <div class="separator separator-dashed my-4"></div>
                                        <div
                                            class="d-flex justify-content-between align-items-center bg-light-success rounded p-4">
                                            <span class="fw-bold text-gray-800 fs-5">Total Amount</span>
                                            <span class="fw-bold text-success fs-3">
                                                Rp {{ number_format($withdraw->amount, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <!--end::Total-->
                                    </div>
                                @else
                                    <div class="alert alert-light-info d-flex align-items-center p-5 mb-7">
                                        <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                                        <div class="d-flex flex-column">
                                            <span>No allocation details available</span>
                                        </div>
                                    </div>
                                @endif
                                <!--end::Allocation Details-->

                                <!--begin::Action buttons-->
                                @if (in_array($withdraw->status, ['pending', 'waiting_confirmation']))
                                    <div class="mb-7">
                                        <h6 class="mb-3">Process Withdraw:</h6>
                                        <div class="d-flex gap-3">
                                            <!--begin::Accept-->
                                            <form action="{{ route('admin.withdraw.confirm', $withdraw->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success"
                                                    onclick="return confirm('Apakah Anda yakin ingin menerima withdraw ini?')">
                                                    <i class="ki-outline ki-check fs-2"></i>
                                                    Accept Withdraw
                                                </button>
                                            </form>
                                            <!--end::Accept-->

                                            <!--begin::Reject-->
                                            <form action="{{ route('admin.withdraw.reject', $withdraw->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger"
                                                    onclick="return confirm('Apakah Anda yakin ingin menolak withdraw ini?')">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                    Reject Withdraw
                                                </button>
                                            </form>
                                            <!--end::Reject-->
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-light-info d-flex align-items-center p-5 mb-7">
                                        <i class="ki-outline ki-information-5 fs-2hx text-info me-4"></i>
                                        <div class="d-flex flex-column">
                                            <span>Withdraw sudah diproses. Status:
                                                {{ ucfirst(str_replace('_', ' ', $withdraw->status)) }}</span>
                                        </div>
                                    </div>
                                @endif
                                <!--end::Action buttons-->
                            </div>
                            <!--end::Section-->
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content-->

                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2">
                    <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="subscription-summary"
                        data-kt-sticky-offset="{default: false, lg: '200px'}"
                        data-kt-sticky-width="{lg: '250px', xl: '300px'}" data-kt-sticky-left="auto"
                        data-kt-sticky-top="150px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Withdraw Summary</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0 fs-6">
                            <!--begin::User Info-->
                            <div class="mb-7">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-60px symbol-circle me-3">
                                        <div class="symbol-label bg-light-primary text-primary fw-bold">
                                            {{ strtoupper(substr($withdraw->user->name, 0, 2)) }}
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#"
                                            class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">{{ $withdraw->user->name }}</a>
                                        <span class="fw-semibold text-gray-600">{{ $withdraw->user->phone ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-7"></div>
                            <!--end::User Info-->

                            <!--begin::Transfer Details-->
                            <div class="mb-7">
                                <h5 class="mb-4">Transfer Details</h5>
                                <div class="mb-0">
                                    <div class="fw-bold text-gray-600 d-flex justify-content-between mb-3">
                                        <span>Amount:</span>
                                        <span class="text-gray-800">Rp
                                            {{ number_format($withdraw->amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="fw-bold text-gray-600 d-flex justify-content-between mb-3">
                                        <span>Fee:</span>
                                        <span class="text-danger">Rp
                                            {{ number_format($withdraw->withdrawal_fee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="fw-bold text-gray-600 d-flex justify-content-between mb-3">
                                        <span>Net Amount:</span>
                                        <span class="text-success">Rp
                                            {{ number_format($withdraw->net_amount, 0, ',', '.') }}</span>
                                    </div>

                                    @if ($withdraw->wallet)
                                        <div class="fw-semibold text-gray-600 mb-3">
                                            <span class="d-block mb-2">Destination:</span>
                                            <div class="bg-light-info p-3 rounded">
                                                @if ($withdraw->wallet->wallet_type == 'ewallet')
                                                    <div class="fw-bold text-info">
                                                        {{ $withdraw->wallet->ewallet_provider }}</div>
                                                    <div class="text-gray-700">{{ $withdraw->wallet->ewallet_number }}
                                                    </div>
                                                    <div class="text-gray-600 fs-7">{{ $withdraw->wallet->ewallet_name }}
                                                    </div>
                                                @elseif ($withdraw->wallet->wallet_type == 'bank')
                                                    <div class="fw-bold text-info">{{ $withdraw->wallet->bank_name }}
                                                    </div>
                                                    <div class="text-gray-700">{{ $withdraw->wallet->bank_account }}</div>
                                                    <div class="text-gray-600 fs-7">{{ $withdraw->wallet->account_name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="separator separator-dashed mb-7"></div>
                            <!--end::Transfer Details-->

                            <!--begin::Withdraw Details-->
                            <div class="mb-10">
                                <h5 class="mb-4">Withdraw Details</h5>
                                <table class="table fs-6 fw-semibold gs-0 gy-2 gx-2">
                                    <tr>
                                        <td class="text-gray-500">Withdraw ID:</td>
                                        <td class="text-gray-800">#{{ $withdraw->main_reference ?? $withdraw->reference }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500">Created:</td>
                                        <td class="text-gray-800">{{ $withdraw->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-500">Status:</td>
                                        <td>
                                            @if ($withdraw->status == 'success')
                                                <span class="badge badge-light-success">Success</span>
                                            @elseif(in_array($withdraw->status, ['pending', 'waiting_confirmation']))
                                                <span
                                                    class="badge badge-light-warning">{{ ucfirst(str_replace('_', ' ', $withdraw->status)) }}</span>
                                            @elseif($withdraw->status == 'failed' || $withdraw->status == 'rejected')
                                                <span
                                                    class="badge badge-light-danger">{{ ucfirst($withdraw->status) }}</span>
                                            @else
                                                <span
                                                    class="badge badge-light-secondary">{{ ucfirst($withdraw->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($withdraw->approved_by && !in_array($withdraw->status, ['pending', 'waiting_confirmation']))
                                        <tr>
                                            <td class="text-gray-500">Processed by:</td>
                                            <td class="text-gray-800">{{ $withdraw->approvedBy->name ?? 'Admin' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-gray-500">Processed at:</td>
                                            <td class="text-gray-800">
                                                {{ $withdraw->approved_at ? $withdraw->approved_at->format('d M Y, H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <!--end::Withdraw Details-->

                            <!--begin::Actions-->
                            <div class="mb-0">
                                <a href="{{ route('admin.withdraw.index') }}" class="btn btn-light w-100">
                                    <i class="ki-outline ki-arrow-left fs-2"></i>
                                    Kembali ke List Withdraw
                                </a>
                            </div>
                            <!--end::Actions-->
                        </div>
                    </div>
                </div>
                <!--end::Sidebar-->
            </div>
        </div>
    </div>
@endsection
