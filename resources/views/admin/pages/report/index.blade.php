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
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Sales
                        Report</h1>
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
                        <div id="kt_ecommerce_report_sales_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <!--begin::Daterangepicker-->
                        <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range"
                            id="kt_ecommerce_report_sales_daterangepicker" />
                        <!--end::Daterangepicker-->
                        <!--begin::Export dropdown-->
                        <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
                            data-kt-menu-placement="bottom-end">
                            <i class="ki-outline ki-exit-up fs-2"></i>Export Report</button>
                        <!--begin::Menu-->
                        <div id="kt_ecommerce_report_sales_export_menu"
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
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_sales_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">Date</th>
                                <th class="text-end min-w-75px">No. Orders</th>
                                <th class="text-end min-w-75px">Products Sold</th>
                                <th class="text-end min-w-75px">Tax</th>
                                <th class="text-end min-w-100px">Total</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            <tr>
                                <td>Aug 19, 2024</td>
                                <td class="text-end pe-0">3</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">$77.00</td>
                                <td class="text-end">$513.00</td>
                            </tr>
                            <tr>
                                <td>Mar 10, 2024</td>
                                <td class="text-end pe-0">9</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">$34.00</td>
                                <td class="text-end">$227.00</td>
                            </tr>
                            <tr>
                                <td>Jun 20, 2024</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">16</td>
                                <td class="text-end pe-0">$19.00</td>
                                <td class="text-end">$126.00</td>
                            </tr>
                            <tr>
                                <td>Nov 10, 2024</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">$23.00</td>
                                <td class="text-end">$155.00</td>
                            </tr>
                            <tr>
                                <td>Oct 25, 2024</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">$75.00</td>
                                <td class="text-end">$499.00</td>
                            </tr>
                            <tr>
                                <td>Mar 10, 2024</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">$19.00</td>
                                <td class="text-end">$127.00</td>
                            </tr>
                            <tr>
                                <td>Jul 25, 2024</td>
                                <td class="text-end pe-0">17</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$65.00</td>
                                <td class="text-end">$431.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">$3.00</td>
                                <td class="text-end">$22.00</td>
                            </tr>
                            <tr>
                                <td>Jun 20, 2024</td>
                                <td class="text-end pe-0">17</td>
                                <td class="text-end pe-0">21</td>
                                <td class="text-end pe-0">$16.00</td>
                                <td class="text-end">$109.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">24</td>
                                <td class="text-end pe-0">$56.00</td>
                                <td class="text-end">$370.00</td>
                            </tr>
                            <tr>
                                <td>Feb 21, 2024</td>
                                <td class="text-end pe-0">9</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">$60.00</td>
                                <td class="text-end">$397.00</td>
                            </tr>
                            <tr>
                                <td>Feb 21, 2024</td>
                                <td class="text-end pe-0">15</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">$11.00</td>
                                <td class="text-end">$71.00</td>
                            </tr>
                            <tr>
                                <td>May 05, 2024</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">17</td>
                                <td class="text-end pe-0">$76.00</td>
                                <td class="text-end">$508.00</td>
                            </tr>
                            <tr>
                                <td>Sep 22, 2024</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">$75.00</td>
                                <td class="text-end">$499.00</td>
                            </tr>
                            <tr>
                                <td>Mar 10, 2024</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">$76.00</td>
                                <td class="text-end">$507.00</td>
                            </tr>
                            <tr>
                                <td>Dec 20, 2024</td>
                                <td class="text-end pe-0">7</td>
                                <td class="text-end pe-0">9</td>
                                <td class="text-end pe-0">$17.00</td>
                                <td class="text-end">$113.00</td>
                            </tr>
                            <tr>
                                <td>Jul 25, 2024</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">$41.00</td>
                                <td class="text-end">$271.00</td>
                            </tr>
                            <tr>
                                <td>Nov 10, 2024</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$87.00</td>
                                <td class="text-end">$579.00</td>
                            </tr>
                            <tr>
                                <td>Dec 20, 2024</td>
                                <td class="text-end pe-0">6</td>
                                <td class="text-end pe-0">9</td>
                                <td class="text-end pe-0">$11.00</td>
                                <td class="text-end">$75.00</td>
                            </tr>
                            <tr>
                                <td>Oct 25, 2024</td>
                                <td class="text-end pe-0">1</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">$27.00</td>
                                <td class="text-end">$182.00</td>
                            </tr>
                            <tr>
                                <td>Feb 21, 2024</td>
                                <td class="text-end pe-0">4</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">$56.00</td>
                                <td class="text-end">$376.00</td>
                            </tr>
                            <tr>
                                <td>Nov 10, 2024</td>
                                <td class="text-end pe-0">6</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">$18.00</td>
                                <td class="text-end">$122.00</td>
                            </tr>
                            <tr>
                                <td>Apr 15, 2024</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">11</td>
                                <td class="text-end pe-0">$30.00</td>
                                <td class="text-end">$200.00</td>
                            </tr>
                            <tr>
                                <td>Jun 20, 2024</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">7</td>
                                <td class="text-end pe-0">$67.00</td>
                                <td class="text-end">$446.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">$26.00</td>
                                <td class="text-end">$172.00</td>
                            </tr>
                            <tr>
                                <td>Oct 25, 2024</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">$27.00</td>
                                <td class="text-end">$180.00</td>
                            </tr>
                            <tr>
                                <td>Jun 20, 2024</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">7</td>
                                <td class="text-end pe-0">$11.00</td>
                                <td class="text-end">$75.00</td>
                            </tr>
                            <tr>
                                <td>Dec 20, 2024</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$26.00</td>
                                <td class="text-end">$172.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$60.00</td>
                                <td class="text-end">$399.00</td>
                            </tr>
                            <tr>
                                <td>Dec 20, 2024</td>
                                <td class="text-end pe-0">1</td>
                                <td class="text-end pe-0">6</td>
                                <td class="text-end pe-0">$66.00</td>
                                <td class="text-end">$443.00</td>
                            </tr>
                            <tr>
                                <td>Nov 10, 2024</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$71.00</td>
                                <td class="text-end">$470.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">2</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">$21.00</td>
                                <td class="text-end">$141.00</td>
                            </tr>
                            <tr>
                                <td>Oct 25, 2024</td>
                                <td class="text-end pe-0">11</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">$5.00</td>
                                <td class="text-end">$36.00</td>
                            </tr>
                            <tr>
                                <td>Aug 19, 2024</td>
                                <td class="text-end pe-0">16</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">$6.00</td>
                                <td class="text-end">$38.00</td>
                            </tr>
                            <tr>
                                <td>Mar 10, 2024</td>
                                <td class="text-end pe-0">3</td>
                                <td class="text-end pe-0">6</td>
                                <td class="text-end pe-0">$78.00</td>
                                <td class="text-end">$519.00</td>
                            </tr>
                            <tr>
                                <td>Sep 22, 2024</td>
                                <td class="text-end pe-0">10</td>
                                <td class="text-end pe-0">14</td>
                                <td class="text-end pe-0">$58.00</td>
                                <td class="text-end">$384.00</td>
                            </tr>
                            <tr>
                                <td>Jun 24, 2024</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">25</td>
                                <td class="text-end pe-0">$19.00</td>
                                <td class="text-end">$124.00</td>
                            </tr>
                            <tr>
                                <td>Aug 19, 2024</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">15</td>
                                <td class="text-end pe-0">$25.00</td>
                                <td class="text-end">$169.00</td>
                            </tr>
                            <tr>
                                <td>Apr 15, 2024</td>
                                <td class="text-end pe-0">15</td>
                                <td class="text-end pe-0">20</td>
                                <td class="text-end pe-0">$67.00</td>
                                <td class="text-end">$444.00</td>
                            </tr>
                            <tr>
                                <td>Jul 25, 2024</td>
                                <td class="text-end pe-0">3</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">$89.00</td>
                                <td class="text-end">$590.00</td>
                            </tr>
                            <tr>
                                <td>Mar 10, 2024</td>
                                <td class="text-end pe-0">1</td>
                                <td class="text-end pe-0">4</td>
                                <td class="text-end pe-0">$75.00</td>
                                <td class="text-end">$499.00</td>
                            </tr>
                            <tr>
                                <td>Feb 21, 2024</td>
                                <td class="text-end pe-0">7</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">$64.00</td>
                                <td class="text-end">$424.00</td>
                            </tr>
                            <tr>
                                <td>Apr 15, 2024</td>
                                <td class="text-end pe-0">9</td>
                                <td class="text-end pe-0">13</td>
                                <td class="text-end pe-0">$66.00</td>
                                <td class="text-end">$443.00</td>
                            </tr>
                            <tr>
                                <td>Jun 20, 2024</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">22</td>
                                <td class="text-end pe-0">$26.00</td>
                                <td class="text-end">$170.00</td>
                            </tr>
                            <tr>
                                <td>Aug 19, 2024</td>
                                <td class="text-end pe-0">2</td>
                                <td class="text-end pe-0">5</td>
                                <td class="text-end pe-0">$85.00</td>
                                <td class="text-end">$569.00</td>
                            </tr>
                            <tr>
                                <td>May 05, 2024</td>
                                <td class="text-end pe-0">2</td>
                                <td class="text-end pe-0">6</td>
                                <td class="text-end pe-0">$76.00</td>
                                <td class="text-end">$505.00</td>
                            </tr>
                            <tr>
                                <td>Jul 25, 2024</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">16</td>
                                <td class="text-end pe-0">$23.00</td>
                                <td class="text-end">$150.00</td>
                            </tr>
                            <tr>
                                <td>Feb 21, 2024</td>
                                <td class="text-end pe-0">8</td>
                                <td class="text-end pe-0">12</td>
                                <td class="text-end pe-0">$34.00</td>
                                <td class="text-end">$228.00</td>
                            </tr>
                            <tr>
                                <td>May 05, 2024</td>
                                <td class="text-end pe-0">18</td>
                                <td class="text-end pe-0">21</td>
                                <td class="text-end pe-0">$14.00</td>
                                <td class="text-end">$96.00</td>
                            </tr>
                            <tr>
                                <td>Apr 15, 2024</td>
                                <td class="text-end pe-0">16</td>
                                <td class="text-end pe-0">19</td>
                                <td class="text-end pe-0">$67.00</td>
                                <td class="text-end">$448.00</td>
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
