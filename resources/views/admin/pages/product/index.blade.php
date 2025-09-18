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
                        Products</h1>
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
                        <li class="breadcrumb-item text-muted">Products</li>
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
                            <input type="text" data-kt-product-table-filter="search"
                                class="form-control form-control-solid w-250px ps-13" placeholder="Search product" />
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-product-table-toolbar="base">
                            <!--begin::Filter-->
                            <div class="w-150px me-3">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                    data-placeholder="Status" data-kt-product-filter="status">
                                    <option></option>
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <!--end::Filter-->
                            <!--begin::Add product-->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_add_product">
                                <i class="ki-outline ki-plus fs-2"></i>Add Product</button>
                            <!--end::Add product-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_products">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-200px">Product</th>
                                <th class="min-w-125px">Price</th>
                                <th class="min-w-125px">Status</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <!--begin::Product details-->
                                        <div class="d-flex flex-column">
                                            <a href="#"
                                                class="text-gray-800 text-hover-primary mb-1 fw-bold">{{ $product->name }}</a>
                                            <span
                                                class="text-muted">{{ Str::limit($product->description, 60) ?? 'No description' }}</span>
                                        </div>
                                        <!--end::Product details-->
                                    </td>
                                    <td>
                                        <span class="fw-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        @if ($product->is_active)
                                            <div class="badge badge-light-success fw-bold">Active</div>
                                        @else
                                            <div class="badge badge-light-danger fw-bold">Inactive</div>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="#"
                                            class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm"
                                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                            <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                        <!--begin::Menu-->
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                            data-kt-menu="true">
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_edit_product"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}"
                                                    data-product-description="{{ $product->description }}"
                                                    data-product-price="{{ $product->price }}"
                                                    data-product-status="{{ $product->is_active ? '1' : '0' }}">Edit</a>
                                            </div>
                                            <!--end::Menu item-->
                                            <!--begin::Menu item-->
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3 text-danger" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_delete_product"
                                                    data-product-id="{{ $product->id }}"
                                                    data-product-name="{{ $product->name }}">Delete</a>
                                            </div>
                                            <!--end::Menu item-->
                                        </div>
                                        <!--end::Menu-->
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <span class="text-muted">No products found</span>
                                    </td>
                                </tr>
                            @endforelse
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

    {{-- Add Product Modal --}}
    <div class="modal fade" id="kt_modal_add_product" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Add Product</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form action="{{ route('admin.product.store') }}" method="POST" id="kt_modal_add_product_form">
                        @csrf
                        <div class="d-flex flex-column scroll-y me-n7 pe-7">
                            {{-- Product Name --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Product Name</label>
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter product name" value="{{ old('name') }}" required />
                            </div>
                            {{-- Description --}}
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Description</label>
                                <textarea name="description" class="form-control form-control-solid" rows="4"
                                    placeholder="Enter product description">{{ old('description') }}</textarea>
                            </div>
                            {{-- Price --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control form-control-solid"
                                        step="0.01" min="0" placeholder="0" value="{{ old('price') }}"
                                        required />
                                </div>
                            </div>
                            {{-- Status --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <select name="is_active" class="form-select form-select-solid fw-bold" required>
                                    <option value="">Select status</option>
                                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Submit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Product Modal --}}
    <div class="modal fade" id="kt_modal_edit_product" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Edit Product</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <form action="" method="POST" id="kt_modal_edit_product_form">
                        @csrf
                        @method('PUT')
                        <div class="d-flex flex-column scroll-y me-n7 pe-7">
                            {{-- Product Name --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Product Name</label>
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                                    placeholder="Enter product name" id="edit_name" required />
                            </div>
                            {{-- Description --}}
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Description</label>
                                <textarea name="description" class="form-control form-control-solid" rows="4"
                                    placeholder="Enter product description" id="edit_description"></textarea>
                            </div>
                            {{-- Price --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" class="form-control form-control-solid"
                                        step="0.01" min="0" id="edit_price" required />
                                </div>
                            </div>
                            {{-- Status --}}
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Status</label>
                                <select name="is_active" class="form-select form-select-solid fw-bold" id="edit_status"
                                    required>
                                    <option value="">Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Product Modal --}}
    <div class="modal fade" id="kt_modal_delete_product" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Delete Product</h2>
                    <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="ki-outline ki-trash text-danger fs-3x mb-5"></i>
                        <p class="fs-6 text-gray-600 mb-5">Are you sure you want to delete <strong
                                id="delete_product_name"></strong>?</p>
                        <p class="fs-7 text-muted">This action cannot be undone.</p>
                    </div>
                    <form action="" method="POST" id="kt_modal_delete_product_form">
                        @csrf
                        @method('DELETE')
                        <div class="text-center pt-5">
                            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Edit Product Modal
            document.addEventListener('DOMContentLoaded', function() {
                const editModal = document.getElementById('kt_modal_edit_product');
                editModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const productId = button.getAttribute('data-product-id');
                    const productName = button.getAttribute('data-product-name');
                    const productDescription = button.getAttribute('data-product-description');
                    const productPrice = button.getAttribute('data-product-price');
                    const productStatus = button.getAttribute('data-product-status');

                    // Update form action
                    const form = document.getElementById('kt_modal_edit_product_form');
                    form.action = `{{ route('admin.product.index') }}/${productId}`;

                    // Fill form fields
                    document.getElementById('edit_name').value = productName;
                    document.getElementById('edit_description').value = productDescription || '';
                    document.getElementById('edit_price').value = productPrice;
                    document.getElementById('edit_status').value = productStatus;
                });

                // Delete Product Modal
                const deleteModal = document.getElementById('kt_modal_delete_product');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const productId = button.getAttribute('data-product-id');
                    const productName = button.getAttribute('data-product-name');

                    // Update form action
                    const form = document.getElementById('kt_modal_delete_product_form');
                    form.action = `{{ route('admin.product.index') }}/${productId}`;

                    // Update product name in modal
                    document.getElementById('delete_product_name').textContent = productName;
                });
            });
        </script>
    @endpush
@endsection
