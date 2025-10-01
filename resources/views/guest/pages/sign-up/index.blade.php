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

                    @if ($referrer ?? false)
                        <div class="alert alert-info mb-10">
                            <i class="ki-outline ki-information fs-2 me-2"></i>
                            Anda diundang oleh: <strong>{{ $referrer->name }}</strong>
                        </div>
                    @endif

                    <!--begin::Form-->
                    <form class="form w-100" method="POST" action="{{ route('guest.process-sign-up') }}">
                        @csrf

                        <!-- Hidden field untuk referral code -->
                        @if ($referralCode ?? false)
                            <input type="hidden" name="ref" value="{{ $referralCode }}">
                        @endif

                        <!--begin::Heading-->
                        <div class="text-center mb-13">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Sign Up</h1>
                            <!--end::Title-->
                            <!--begin::Subtitle-->
                            <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                            <!--end::Subtitle-->
                        </div>
                        <!--begin::Heading-->

                        <!--begin::Input group - Name-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Nama Lengkap</label>
                            <input type="text" placeholder="Masukkan nama lengkap" name="name"
                                value="{{ old('name') }}" autocomplete="off"
                                class="form-control form-control-lg bg-transparent @error('name') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Phone-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Nomor HP</label>
                            <input type="text" placeholder="Masukkan nomor HP" name="phone" value="{{ old('phone') }}"
                                autocomplete="off"
                                class="form-control form-control-lg bg-transparent @error('phone') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Email-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Email <span
                                    class="text-muted">(Opsional)</span></label>
                            <input type="email" placeholder="Masukkan email" name="email" value="{{ old('email') }}"
                                autocomplete="off"
                                class="form-control form-control-lg bg-transparent @error('email') is-invalid @enderror" />
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Password-->
                        <div class="fv-row mb-10" data-kt-password-meter="true">
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Password</label>
                            <!--begin::Wrapper-->
                            <div class="mb-1">
                                <!--begin::Input wrapper-->
                                <div class="position-relative mb-3">
                                    <input
                                        class="form-control form-control-lg bg-transparent @error('password') is-invalid @enderror"
                                        type="password" placeholder="Masukkan password" name="password" autocomplete="off"
                                        id="password-field" />
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
                            <div class="text-muted fs-7">Gunakan minimal 8 karakter dengan kombinasi huruf, angka & simbol
                            </div>
                            <!--end::Hint-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group - Confirm Password-->
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Konfirmasi Password</label>
                            <div class="position-relative">
                                <input placeholder="Masukkan kembali password" name="password_confirmation" type="password"
                                    autocomplete="off" id="password-confirm-field"
                                    class="form-control form-control-lg bg-transparent @error('password_confirmation') is-invalid @enderror" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    id="toggle-password-confirm" style="cursor: pointer;">
                                    <i class="ki-duotone ki-eye fs-2" id="eye-icon-confirm">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    <i class="ki-duotone ki-eye-slash fs-2 d-none" id="eye-slash-icon-confirm">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                    </i>
                                </span>
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Accept-->
                        <div class="fv-row mb-10">
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="toc" value="1" required />
                                <span class="form-check-label fw-semibold text-gray-700 fs-6 ms-1">Saya menyetujui
                                    <a href="#" class="link-primary">Syarat & Ketentuan</a></span>
                            </label>
                        </div>
                        <!--end::Accept-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="indicator-label">Sign Up</span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Sign in-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="link-primary fw-bold">Sign in</a>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle untuk password
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

                // Toggle untuk confirm password
                const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
                const passwordConfirmField = document.getElementById('password-confirm-field');
                const eyeIconConfirm = document.getElementById('eye-icon-confirm');
                const eyeSlashIconConfirm = document.getElementById('eye-slash-icon-confirm');

                if (togglePasswordConfirm) {
                    togglePasswordConfirm.addEventListener('click', function() {
                        const type = passwordConfirmField.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        passwordConfirmField.setAttribute('type', type);
                        eyeIconConfirm.classList.toggle('d-none');
                        eyeSlashIconConfirm.classList.toggle('d-none');
                    });
                }
            });
        </script>
    @endpush
@endsection
