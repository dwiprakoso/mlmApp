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
                        <div class="alert alert-danger mb-10">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success mb-10">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('guest.process-sign-in') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-13">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                            <!--end::Title-->
                        </div>
                        <!--begin::Heading-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Email atau No HP</label>
                            <!--end::Label-->
                            <!--begin::Email/Phone-->
                            <input type="text" placeholder="Masukkan email atau nomor HP" name="login"
                                value="{{ old('login') }}" autocomplete="off"
                                class="form-control form-control-lg bg-transparent @error('login') is-invalid @enderror" />
                            <!--end::Email/Phone-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Password</label>
                            <!--end::Label-->
                            <!--begin::Password-->
                            <div class="position-relative">
                                <input type="password" placeholder="Masukkan password" name="password" autocomplete="off"
                                    id="password-field"
                                    class="form-control form-control-lg bg-transparent @error('password') is-invalid @enderror" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    id="toggle-password" style="cursor: pointer;">
                                    <i class="ki-duotone ki-eye fs-2" id="eye-icon">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <i class="ki-duotone ki-eye-slash fs-2 d-none" id="eye-slash-icon">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <!--end::Password-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="indicator-label">Sign In</span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Sign up-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            Not a Member yet?
                            <a href="{{ route('guest.sign-up') }}" class="link-primary fw-bold">Sign up</a>
                        </div>
                        <!--end::Sign up-->
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const togglePassword = document.getElementById('toggle-password');
                const passwordField = document.getElementById('password-field');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeSlashIcon = document.getElementById('eye-slash-icon');

                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        // Toggle password visibility
                        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', type);

                        // Toggle icon
                        eyeIcon.classList.toggle('d-none');
                        eyeSlashIcon.classList.toggle('d-none');
                    });
                }
            });
        </script>
    @endpush
@endsection
