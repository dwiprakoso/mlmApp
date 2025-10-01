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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">User
                        Managements</h1>
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
                        <li class="breadcrumb-item text-muted">User Managements</li>
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

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-user-table-filter="search"
                                class="form-control form-control-solid w-100 w-md-250px ps-13" placeholder="Search user" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add user-->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_user">
                                <i class="ki-outline ki-plus fs-2 d-none d-md-inline"></i>
                                <span class="d-none d-md-inline">Add User</span>
                                <span class="d-md-none">Add</span>
                            </button>
                            <!--end::Add user-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-25 ps-4">User</th>
                                    <th class="w-15 d-none d-md-table-cell">Status</th>
                                    <th class="w-30 d-none d-lg-table-cell">Joined Date</th>
                                    <th class="text-end w-15 pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex flex-column">
                                                <div class="text-gray-800 text-hover-primary mb-1 fw-bold">
                                                    {{ $user->phone }}</div>
                                                <div class="d-md-none">
                                                    <span
                                                        class="badge badge-light-{{ $user->status_badge_color }} fw-bold me-2">
                                                        {{ ucfirst($user->status) }}
                                                    </span>
                                                    <small
                                                        class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="badge badge-light-{{ $user->status_badge_color }} fw-bold">
                                                {{ ucfirst($user->status) }}
                                            </div>
                                        </td>
                                        <td class="d-none d-lg-table-cell">{{ $user->created_at->format('d M Y, g:i a') }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="#"
                                                class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
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
                                                    <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                        data-bs-target="#kt_modal_edit_user"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-user-email="{{ $user->email }}"
                                                        data-user-status="{{ $user->status }}">Edit</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3 text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_delete_user"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}">Delete</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu-->
                                        </td>
                                    </tr>
                                @endforeach
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

    {{-- Add User Modal --}}
    <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add User</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body scroll-y mx-2 mx-xl-15 my-7">
                    <form action="{{ route('admin.user-management.store') }}" method="POST"
                        id="kt_modal_add_user_form">
                        @csrf
                        <div class="d-flex flex-column scroll-y me-n7 pe-7">
                            {{-- Name --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Full name" value="{{ old('name') }}" required />
                            </div>
                            {{-- Email --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="example@domain.com" value="{{ old('email') }}" required />
                            </div>
                            {{-- Password --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Password</label>
                                <input type="password" name="password"
                                    class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Password"
                                    required />
                            </div>
                            {{-- Status --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <select name="status" class="form-select form-select-solid fw-bold" required>
                                    <option value="">Select status</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>
                                        Suspended</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3 btn-sm" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <span class="indicator-label">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="kt_modal_edit_user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Edit User</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body scroll-y mx-2 mx-xl-15 my-7">
                    <form action="" method="POST" id="kt_modal_edit_user_form">
                        @csrf
                        @method('PUT')
                        <div class="d-flex flex-column scroll-y me-n7 pe-7">
                            {{-- Name --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Full name" id="edit_name" required />
                            </div>
                            {{-- Email --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="example@domain.com" id="edit_email" required />
                            </div>
                            {{-- Password --}}
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Password</label>
                                <input type="password" name="password"
                                    class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Leave blank to keep current password" />
                                <div class="form-text">Leave blank if you don't want to change the password</div>
                            </div>
                            {{-- Status --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <select name="status" class="form-select form-select-solid fw-bold" id="edit_status"
                                    required>
                                    <option value="">Select status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3 btn-sm" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <span class="indicator-label">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div class="modal fade" id="kt_modal_delete_user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Delete User</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="ki-outline ki-trash text-danger fs-3x mb-5"></i>
                        <p class="fs-6 text-gray-600 mb-5">Are you sure you want to delete <strong
                                id="delete_user_name"></strong>?</p>
                        <p class="fs-7 text-muted">This action cannot be undone.</p>
                    </div>
                    <form action="" method="POST" id="kt_modal_delete_user_form">
                        @csrf
                        @method('DELETE')
                        <div class="text-center pt-5">
                            <button type="button" class="btn btn-light me-3 btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
                }

                .modal-body .form-control {
                    font-size: 16px;
                    /* Prevent zoom on iOS */
                }
            }

            @media (max-width: 576px) {
                .app-container {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }

                .modal-content {
                    margin: 0.5rem;
                }

                .modal-body {
                    padding: 1rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Search functionality
            const searchInput = document.querySelector('[data-kt-user-table-filter="search"]');
            const tableBody = document.querySelector('#kt_table_users tbody');
            const tableRows = tableBody.querySelectorAll('tr');

            searchInput.addEventListener('keyup', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                tableRows.forEach(row => {
                    const phoneNumber = row.querySelector('td:first-child .text-gray-800').textContent
                        .toLowerCase();
                    const status = row.querySelector('td:nth-child(2) .badge')?.textContent.toLowerCase() ||
                        '';
                    const date = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                    // Search in phone, status, and date
                    if (phoneNumber.includes(searchTerm) ||
                        status.includes(searchTerm) ||
                        date.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show "No matching records found" message if all rows are hidden
                const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                const noDataRow = tableBody.querySelector('.no-data-row');

                if (visibleRows.length === 0 && !noDataRow) {
                    const colspan = tableBody.closest('table').querySelectorAll('thead th').length;
                    const emptyRow = document.createElement('tr');
                    emptyRow.className = 'no-data-row';
                    emptyRow.innerHTML =
                        `<td colspan="${colspan}" class="text-center py-10 text-muted">No matching records found</td>`;
                    tableBody.appendChild(emptyRow);
                } else if (visibleRows.length > 0 && noDataRow) {
                    noDataRow.remove();
                }
            });

            // Edit User Modal
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('kt_modal_edit_user');
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');
                    const userName = button.getAttribute('data-user-name');
                    const userEmail = button.getAttribute('data-user-email');
                    const userStatus = button.getAttribute('data-user-status');

                    // Update form action
                    const form = document.getElementById('kt_modal_edit_user_form');
                    form.action = `{{ route('admin.user-management.index') }}/${userId}`;

                    // Fill form fields
                    document.getElementById('edit_name').value = userName;
                    document.getElementById('edit_email').value = userEmail;
                    document.getElementById('edit_status').value = userStatus;
                });

                // Delete User Modal
                const deleteModal = document.getElementById('kt_modal_delete_user');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const userId = button.getAttribute('data-user-id');
                    const userName = button.getAttribute('data-user-name');

                    // Update form action
                    const form = document.getElementById('kt_modal_delete_user_form');
                    form.action = `{{ route('admin.user-management.index') }}/${userId}`;

                    // Update user name in modal
                    document.getElementById('delete_user_name').textContent = userName;
                });
            });
        </script>
    @endpush
@endsection
