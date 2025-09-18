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
                        Customer Orders Report</h1>
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
                        <li class="breadcrumb-item text-muted">eCommerce</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Reports</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="#"
                        class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"
                        data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">Add Member</a>
                    <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_create_campaign">New Campaign</a>
                </div>
                <!--end::Actions-->
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
                                class="form-control form-control-solid w-250px ps-12" placeholder="Search Report" />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_customer_orders_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title==

            <!==begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Daterangepicker-->
                        <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range"
                            id="kt_ecommerce_report_customer_orders_daterangepicker" />
                        <!--end::Daterangepicker-->
                        <!--begin::Filter-->
                        <div class="w-150px">
                            <!--begin::Select2-->
                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                                data-placeholder="Status" data-kt-ecommerce-order-filter="status">
                                <option></option>
                                <option value="all">All</option>
                                <option value="active">Active</option>
                                <option value="locked">Locked</option>
                                <option value="disabled">Disabled</option>
                                <option value="banned">Banned</option>
                            </select>
                            <!--end::Select2-->
                        </div>
                        <!--end::Filter-->
                        <!--begin::Export dropdown-->
                        <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-exit-up fs-2"></i>Export Report</button>
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
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">Export as CSV</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">Export as PDF</a>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu-->
                        <!--end::Export dropdown-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5"
                        id="kt_ecommerce_report_customer_orders_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Customer Name</th>
                                <th class="min-w-100px">Email</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-100px">Date Joined</th>
                                <th class="text-end min-w-75px">No. Orders</th>
                                <th class="text-end min-w-75px">No. Products</th>
                                <th class="text-end min-w-100px">Total</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 9:23 pm</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">27</td>
                                <td class="text-end">$1829.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Melody Macy</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">melody@altbox.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Mar 2024, 11:05 am</td>
                                <td class="text-end pe-0">59</td>
                                <td class="text-end pe-0">70</td>
                                <td class="text-end">$878.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Max Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">max@kt.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 10:10 pm</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">33</td>
                                <td class="text-end">$1435.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Sean Bean</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">sean@dellito.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 5:30 pm</td>
                                <td class="text-end pe-0">58</td>
                                <td class="text-end pe-0">68</td>
                                <td class="text-end">$3136.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Brian Cox</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">brian@exchange.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Jun 2024, 9:23 pm</td>
                                <td class="text-end pe-0">17</td>
                                <td class="text-end pe-0">31</td>
                                <td class="text-end">$942.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Mikaela Collins</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">mik@pex.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-warning">Locked</div>
                                </td>
                                <td>21 Feb 2024, 10:30 am</td>
                                <td class="text-end pe-0">32</td>
                                <td class="text-end pe-0">42</td>
                                <td class="text-end">$4051.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Francis Mitcham</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">f.mit@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-danger">Banned</div>
                                </td>
                                <td>22 Sep 2024, 10:30 am</td>
                                <td class="text-end pe-0">30</td>
                                <td class="text-end pe-0">36</td>
                                <td class="text-end">$2888.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Olivia Wild</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">olivia@corpmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 11:30 am</td>
                                <td class="text-end pe-0">92</td>
                                <td class="text-end pe-0">98</td>
                                <td class="text-end">$1105.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Neil Owen</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">owen.neil@gmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 10:10 pm</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">27</td>
                                <td class="text-end">$720.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Dan Wilson</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">dam@consilting.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 10:30 am</td>
                                <td class="text-end pe-0">38</td>
                                <td class="text-end pe-0">46</td>
                                <td class="text-end">$1919.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Bold</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">emma@intenso.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Mar 2024, 8:43 pm</td>
                                <td class="text-end pe-0">67</td>
                                <td class="text-end pe-0">73</td>
                                <td class="text-end">$2337.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ana Crown</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ana.cf@limtel.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Oct 2024, 9:23 pm</td>
                                <td class="text-end pe-0">35</td>
                                <td class="text-end pe-0">42</td>
                                <td class="text-end">$1933.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Robert Doe</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">robert@benko.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>21 Feb 2024, 5:30 pm</td>
                                <td class="text-end pe-0">88</td>
                                <td class="text-end pe-0">102</td>
                                <td class="text-end">$3029.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">John Miller</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">miller@mapple.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-warning">Locked</div>
                                </td>
                                <td>20 Jun 2024, 10:30 am</td>
                                <td class="text-end pe-0">88</td>
                                <td class="text-end pe-0">94</td>
                                <td class="text-end">$4091.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Lucy Kunic</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">lucy.m@fentech.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Dec 2024, 11:05 am</td>
                                <td class="text-end pe-0">34</td>
                                <td class="text-end pe-0">41</td>
                                <td class="text-end">$4452.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ethan Wilder</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ethan@loop.com.au</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-danger">Banned</div>
                                </td>
                                <td>21 Feb 2024, 5:20 pm</td>
                                <td class="text-end pe-0">69</td>
                                <td class="text-end pe-0">81</td>
                                <td class="text-end">$3565.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Mikaela Collins</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">mik@pex.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Jun 2024, 9:23 pm</td>
                                <td class="text-end pe-0">76</td>
                                <td class="text-end pe-0">89</td>
                                <td class="text-end">$2197.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Mar 2024, 5:20 pm</td>
                                <td class="text-end pe-0">78</td>
                                <td class="text-end pe-0">90</td>
                                <td class="text-end">$171.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Melody Macy</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">melody@altbox.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 5:20 pm</td>
                                <td class="text-end pe-0">86</td>
                                <td class="text-end pe-0">97</td>
                                <td class="text-end">$195.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Max Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">max@kt.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-danger">Banned</div>
                                </td>
                                <td>10 Mar 2024, 5:20 pm</td>
                                <td class="text-end pe-0">88</td>
                                <td class="text-end pe-0">95</td>
                                <td class="text-end">$1193.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Sean Bean</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">sean@dellito.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>19 Aug 2024, 6:05 pm</td>
                                <td class="text-end pe-0">10</td>
                                <td class="text-end pe-0">21</td>
                                <td class="text-end">$4475.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Brian Cox</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">brian@exchange.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 10:10 pm</td>
                                <td class="text-end pe-0">60</td>
                                <td class="text-end pe-0">65</td>
                                <td class="text-end">$4405.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Mikaela Collins</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">mik@pex.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-danger">Banned</div>
                                </td>
                                <td>19 Aug 2024, 11:30 am</td>
                                <td class="text-end pe-0">39</td>
                                <td class="text-end pe-0">50</td>
                                <td class="text-end">$1121.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Francis Mitcham</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">f.mit@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Dec 2024, 11:05 am</td>
                                <td class="text-end pe-0">86</td>
                                <td class="text-end pe-0">96</td>
                                <td class="text-end">$2522.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Olivia Wild</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">olivia@corpmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Nov 2024, 6:43 am</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">25</td>
                                <td class="text-end">$2595.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Neil Owen</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">owen.neil@gmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>19 Aug 2024, 6:05 pm</td>
                                <td class="text-end pe-0">64</td>
                                <td class="text-end pe-0">74</td>
                                <td class="text-end">$5039.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Dan Wilson</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">dam@consilting.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>24 Jun 2024, 6:05 pm</td>
                                <td class="text-end pe-0">63</td>
                                <td class="text-end pe-0">75</td>
                                <td class="text-end">$3193.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Bold</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">emma@intenso.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>24 Jun 2024, 5:20 pm</td>
                                <td class="text-end pe-0">36</td>
                                <td class="text-end pe-0">49</td>
                                <td class="text-end">$2535.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ana Crown</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ana.cf@limtel.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Oct 2024, 2:40 pm</td>
                                <td class="text-end pe-0">62</td>
                                <td class="text-end pe-0">74</td>
                                <td class="text-end">$5024.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Robert Doe</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">robert@benko.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Mar 2024, 2:40 pm</td>
                                <td class="text-end pe-0">85</td>
                                <td class="text-end pe-0">96</td>
                                <td class="text-end">$3062.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">John Miller</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">miller@mapple.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 6:43 am</td>
                                <td class="text-end pe-0">29</td>
                                <td class="text-end pe-0">43</td>
                                <td class="text-end">$2346.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Lucy Kunic</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">lucy.m@fentech.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 5:30 pm</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">37</td>
                                <td class="text-end">$3245.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ethan Wilder</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ethan@loop.com.au</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>21 Feb 2024, 6:05 pm</td>
                                <td class="text-end pe-0">21</td>
                                <td class="text-end pe-0">29</td>
                                <td class="text-end">$4456.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Robert Doe</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">robert@benko.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Jul 2024, 10:30 am</td>
                                <td class="text-end pe-0">31</td>
                                <td class="text-end pe-0">38</td>
                                <td class="text-end">$4350.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 6:43 am</td>
                                <td class="text-end pe-0">3</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end">$4711.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Melody Macy</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">melody@altbox.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Dec 2024, 9:23 pm</td>
                                <td class="text-end pe-0">67</td>
                                <td class="text-end pe-0">77</td>
                                <td class="text-end">$1399.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Max Smith</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">max@kt.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>15 Apr 2024, 2:40 pm</td>
                                <td class="text-end pe-0">42</td>
                                <td class="text-end pe-0">49</td>
                                <td class="text-end">$1505.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Sean Bean</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">sean@dellito.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-info">Disabled</div>
                                </td>
                                <td>21 Feb 2024, 6:43 am</td>
                                <td class="text-end pe-0">24</td>
                                <td class="text-end pe-0">30</td>
                                <td class="text-end">$3102.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Brian Cox</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">brian@exchange.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>10 Nov 2024, 5:20 pm</td>
                                <td class="text-end pe-0">48</td>
                                <td class="text-end pe-0">56</td>
                                <td class="text-end">$1095.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Mikaela Collins</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">mik@pex.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>21 Feb 2024, 9:23 pm</td>
                                <td class="text-end pe-0">39</td>
                                <td class="text-end pe-0">49</td>
                                <td class="text-end">$4967.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Francis Mitcham</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">f.mit@kpmg.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-info">Disabled</div>
                                </td>
                                <td>20 Jun 2024, 11:30 am</td>
                                <td class="text-end pe-0">25</td>
                                <td class="text-end pe-0">30</td>
                                <td class="text-end">$2423.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Olivia Wild</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">olivia@corpmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>15 Apr 2024, 9:23 pm</td>
                                <td class="text-end pe-0">26</td>
                                <td class="text-end pe-0">31</td>
                                <td class="text-end">$2570.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Neil Owen</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">owen.neil@gmail.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>15 Apr 2024, 8:43 pm</td>
                                <td class="text-end pe-0">76</td>
                                <td class="text-end pe-0">81</td>
                                <td class="text-end">$415.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Dan Wilson</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">dam@consilting.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>15 Apr 2024, 5:30 pm</td>
                                <td class="text-end pe-0">92</td>
                                <td class="text-end pe-0">104</td>
                                <td class="text-end">$2055.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Emma Bold</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">emma@intenso.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Dec 2024, 6:05 pm</td>
                                <td class="text-end pe-0">55</td>
                                <td class="text-end pe-0">69</td>
                                <td class="text-end">$3717.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ana Crown</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ana.cf@limtel.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>22 Sep 2024, 8:43 pm</td>
                                <td class="text-end pe-0">49</td>
                                <td class="text-end pe-0">54</td>
                                <td class="text-end">$2229.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Robert Doe</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">robert@benko.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>24 Jun 2024, 2:40 pm</td>
                                <td class="text-end pe-0">23</td>
                                <td class="text-end pe-0">32</td>
                                <td class="text-end">$2598.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">John Miller</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">miller@mapple.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>05 May 2024, 2:40 pm</td>
                                <td class="text-end pe-0">23</td>
                                <td class="text-end pe-0">35</td>
                                <td class="text-end">$2766.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Lucy Kunic</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">lucy.m@fentech.com</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>20 Jun 2024, 5:30 pm</td>
                                <td class="text-end pe-0">64</td>
                                <td class="text-end pe-0">69</td>
                                <td class="text-end">$1384.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="apps/ecommerce/customers/details.html"
                                        class="text-gray-900 text-hover-primary">Ethan Wilder</a>
                                </td>
                                <td>
                                    <a href="#" class="text-gray-900 text-hover-primary">ethan@loop.com.au</a>
                                </td>
                                <td>
                                    <div class="badge badge-light-success">Active</div>
                                </td>
                                <td>25 Oct 2024, 10:10 pm</td>
                                <td class="text-end pe-0">67</td>
                                <td class="text-end pe-0">75</td>
                                <td class="text-end">$2665.00</td>
                            </tr>
                        </tbody>
                    </table>
                    <!--end::Table-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
