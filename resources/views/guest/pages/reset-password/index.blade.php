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
                    <form class="form w-100" method="POST" action="{{ route('reset-password.reset.process') }}">
                        @csrf
                        <input type="hidden" name="phone" value="{{ $phone }}">
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Reset Password</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-gray-500 fw-semibold fs-6">
                                Masukkan password baru untuk akun Anda
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Password Baru</label>
                            <!--end::Label-->
                            <!--begin::Password-->
                            <div class="position-relative">
                                <input type="password" placeholder="Masukkan password baru" name="password"
                                    autocomplete="off" id="password-field"
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
                            <div class="form-text">Password minimal 8 karakter</div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Konfirmasi Password</label>
                            <!--end::Label-->
                            <!--begin::Password Confirmation-->
                            <div class="position-relative">
                                <input type="password" placeholder="Masukkan ulang password baru"
                                    name="password_confirmation" autocomplete="off" id="password-confirmation-field"
                                    class="form-control form-control-lg bg-transparent @error('password_confirmation') is-invalid @enderror" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    id="toggle-password-confirmation" style="cursor: pointer;">
                                    <i class="ki-duotone ki-eye fs-2" id="eye-confirmation-icon">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <i class="ki-duotone ki-eye-slash fs-2 d-none" id="eye-slash-confirmation-icon">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                            <!--end::Password Confirmation-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="indicator-label">Reset Password</span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Back to login-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            <a href="{{ route('login') }}" class="link-primary fw-bold">
                                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Kembali ke Sign In
                            </a>
                        </div>
                        <!--end::Back to login-->
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
                // Toggle password visibility
                const togglePassword = document.getElementById('toggle-password');
                const passwordField = document.getElementById('password-field');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeSlashIcon = document.getElementById('eye-slash-icon');

                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
                        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordField.setAttribute('type', type);
                        eyeIcon.classList.toggle('d-none');
                        eyeSlashIcon.classList.toggle('d-none');
                    });
                }

                // Toggle password confirmation visibility
                const togglePasswordConfirmation = document.getElementById('toggle-password-confirmation');
                const passwordConfirmationField = document.getElementById('password-confirmation-field');
                const eyeConfirmationIcon = document.getElementById('eye-confirmation-icon');
                const eyeSlashConfirmationIcon = document.getElementById('eye-slash-confirmation-icon');

                if (togglePasswordConfirmation) {
                    togglePasswordConfirmation.addEventListener('click', function() {
                        const type = passwordConfirmationField.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        passwordConfirmationField.setAttribute('type', type);
                        eyeConfirmationIcon.classList.toggle('d-none');
                        eyeSlashConfirmationIcon.classList.toggle('d-none');
                    });
                }

                // Password strength indicator (optional)
                if (passwordField) {
                    passwordField.addEventListener('input', function() {
                        const password = this.value;
                        const hasMinLength = password.length >= 8;
                        const hasNumber = /\d/.test(password);
                        const hasLetter = /[a-zA-Z]/.test(password);

                        // You can add visual feedback here if needed
                    });
                }
            });
        </script>
    @endpush
@endsection
