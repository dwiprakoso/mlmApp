@extends('guest.layouts.app')
@section('content')
    <!--begin::Body-->
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
        <!--begin::Wrapper-->
        <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                <!--begin::Wrapper-->
                <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">

                    @if ($errors->any())
                        <div class="alert alert-danger mb-8">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('guest.process-sign-up') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
                            <!--end::Title-->
                            <!--begin::Subtitle-->
                            <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                            <!--end::Subtitle-->
                        </div>
                        <!--begin::Heading-->

                        <!--begin::Input group - Name-->
                        <div class="fv-row mb-8">
                            <input type="text" placeholder="Nama *" name="name" value="{{ old('name') }}"
                                autocomplete="off"
                                class="form-control bg-transparent @error('name') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Phone-->
                        <div class="fv-row mb-8">
                            <input type="text" placeholder="No HP *" name="phone" value="{{ old('phone') }}"
                                autocomplete="off"
                                class="form-control bg-transparent @error('phone') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Email-->
                        <div class="fv-row mb-8">
                            <input type="email" placeholder="Email (Opsional)" name="email" value="{{ old('email') }}"
                                autocomplete="off"
                                class="form-control bg-transparent @error('email') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Password-->
                        <div class="fv-row mb-8" data-kt-password-meter="true">
                            <!--begin::Wrapper-->
                            <div class="mb-1">
                                <!--begin::Input wrapper-->
                                <div class="position-relative mb-3">
                                    <input class="form-control bg-transparent @error('password') is-invalid @enderror"
                                        type="password" placeholder="Password *" name="password" autocomplete="off" />
                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                        data-kt-password-meter-control="visibility">
                                        <i class="ki-outline ki-eye-slash fs-2"></i>
                                        <i class="ki-outline ki-eye fs-2 d-none"></i>
                                    </span>
                                </div>
                                <!--end::Input wrapper-->
                                <!--begin::Meter-->
                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                </div>
                                <!--end::Meter-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Hint-->
                            <div class="text-muted">Gunakan minimal 8 karakter dengan kombinasi huruf, angka & simbol</div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Confirm Password-->
                        <div class="fv-row mb-8">
                            <input placeholder="Konfirmasi Password *" name="password_confirmation" type="password"
                                autocomplete="off"
                                class="form-control bg-transparent @error('password_confirmation') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Accept-->
                        <div class="fv-row mb-8">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="toc" value="1" required />
                                <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">Saya menyetujui
                                    <a href="#" class="ms-1 link-primary">Syarat & Ketentuan</a></span>
                            </label>
                        </div>
                        <!--end::Accept-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary">
                                Sign up
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Sign in-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">Sudah punya akun?
                            <a href="{{ route('guest.sign-in') }}" class="link-primary fw-semibold">Sign in</a>
                        </div>
                        <!--end::Sign in-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Body-->
@endsection
