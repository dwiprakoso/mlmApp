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
                    <form class="form w-100" method="POST" action="{{ route('reset-password.verify-otp.process') }}">
                        @csrf
                        <input type="hidden" name="phone" value="{{ $phone }}">

                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Verifikasi OTP</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-gray-500 fw-semibold fs-6 mb-2">
                                Kode OTP telah dikirim ke nomor
                            </div>
                            <div class="text-gray-900 fw-bold fs-5 mb-5">
                                {{ $phone }}
                            </div>
                            <div class="text-gray-500 fw-semibold fs-7">
                                Masukkan 6 digit kode OTP yang Anda terima
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Kode OTP</label>
                            <!--end::Label-->
                            <!--begin::OTP-->
                            <input type="text" placeholder="Masukkan 6 digit kode OTP" name="otp"
                                value="{{ old('otp') }}" autocomplete="off" maxlength="6"
                                class="form-control form-control-lg bg-transparent text-center fs-1 fw-bold letter-spacing-10 @error('otp') is-invalid @enderror"
                                id="otp-input" />
                            <!--end::OTP-->
                            <div class="form-text text-center">Kode OTP berlaku selama 5 menit</div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="indicator-label">Verifikasi OTP</span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Resend OTP-->
                        <div class="text-center mb-10">
                            <span class="text-gray-500 fw-semibold fs-6">Tidak menerima kode?</span>
                            <form action="{{ route('reset-password.resend-otp') }}" method="POST" class="d-inline"
                                id="resend-form">
                                @csrf
                                <input type="hidden" name="phone" value="{{ $phone }}">
                                <button type="submit" class="btn btn-link link-primary fw-bold p-0" id="resend-btn">
                                    Kirim Ulang
                                </button>
                            </form>
                        </div>
                        <!--end::Resend OTP-->

                        <!--begin::Back-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            <a href="{{ route('reset-password.request') }}" class="link-primary fw-bold">
                                <i class="ki-duotone ki-arrow-left fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Kembali
                            </a>
                        </div>
                        <!--end::Back-->
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

    @push('styles')
        <style>
            .letter-spacing-10 {
                letter-spacing: 10px;
            }

            #otp-input::-webkit-inner-spin-button,
            #otp-input::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const otpInput = document.getElementById('otp-input');

                // Only allow numbers
                if (otpInput) {
                    otpInput.addEventListener('input', function(e) {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }

                // Resend OTP with cooldown
                const resendBtn = document.getElementById('resend-btn');
                const resendForm = document.getElementById('resend-form');

                if (resendBtn && resendForm) {
                    let cooldown = 0;

                    function startCooldown() {
                        cooldown = 60;
                        resendBtn.disabled = true;

                        const interval = setInterval(function() {
                            cooldown--;
                            resendBtn.textContent = `Kirim Ulang (${cooldown}s)`;

                            if (cooldown <= 0) {
                                clearInterval(interval);
                                resendBtn.disabled = false;
                                resendBtn.textContent = 'Kirim Ulang';
                            }
                        }, 1000);
                    }

                    resendForm.addEventListener('submit', function(e) {
                        if (cooldown > 0) {
                            e.preventDefault();
                            return false;
                        }
                        startCooldown();
                    });
                }
            });
        </script>
    @endpush
@endsection
