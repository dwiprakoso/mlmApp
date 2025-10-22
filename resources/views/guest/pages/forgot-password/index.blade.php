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
                    <form class="form w-100" method="POST" action="{{ route('reset-password.send-otp') }}">
                        @csrf
                        <!--begin::Heading-->
                        <div class="text-center mb-10">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 fw-bolder mb-3">Lupa Password?</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-gray-500 fw-semibold fs-6">
                                Masukkan email yang terdaftar untuk menerima kode OTP
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-10">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-semibold text-gray-900 mb-3">Email</label>
                            <!--end::Label-->
                            <!--begin::Email-->
                            <input type="email" placeholder="Contoh: nama@email.com" name="email"
                                value="{{ old('email') }}" autocomplete="email"
                                class="form-control form-control-lg bg-transparent @error('email') is-invalid @enderror" />
                            <!--end::Email-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <span class="indicator-label">Kirim Kode OTP</span>
                            </button>
                        </div>
                        <!--end::Submit button-->

                        <!--begin::Back to login-->
                        <div class="text-gray-500 text-center fw-semibold fs-6">
                            Sudah ingat password?
                            <a href="{{ route('login') }}" class="link-primary fw-bold">Kembali ke Sign In</a>
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
@endsection
