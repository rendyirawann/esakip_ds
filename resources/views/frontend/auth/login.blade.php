@extends('frontend.auth.app')
@section('title', 'Login — eSakip Pemerintah Kabupaten Deli Serdang')

@push('stylesheets')
<style>
    .login-bg-overlay {
        background: linear-gradient(135deg, rgba(0, 74, 173, 0.9) 0%, rgba(0, 153, 255, 0.8) 100%);
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1;
    }
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    .government-seal {
        width: 120px;
        height: auto;
        margin-bottom: 1.5rem;
        filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
    }
    .btn-primary-gov {
        background-color: #004aad !important;
        border-color: #004aad !important;
        transition: all 0.3s ease;
    }
    .btn-primary-gov:hover {
        background-color: #003a8c !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 74, 173, 0.3);
    }
    .login-title {
        color: #004aad;
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .login-subtitle {
        color: #6c757d;
        font-weight: 500;
    }
    .form-control-gov {
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        border: 1.5px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .form-control-gov:focus {
        border-color: #004aad;
        box-shadow: 0 0 0 0.25rem rgba(0, 74, 173, 0.1);
    }
</style>
@endpush

@section('content')
    <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-start p-12">
        <div class="login-card d-flex flex-column flex-center rounded-4 w-md-600px p-10">
            <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
                <div class="d-flex flex-center flex-column flex-column-fluid mb-10">
                    <!-- Logo Pemerintah Deli Serdang -->
                    <img alt="Logo Deli Serdang" class="government-seal" src="{{ asset('assets-front/media/logos/base-logo.png') }}" />
                    
                    <h1 class="login-title fs-2qx text-center mb-2">eSakip</h1>
                    <p class="login-subtitle fs-5 text-center mb-8">Pemerintah Kabupaten Deli Serdang</p>
                    <div class="separator separator-dashed border-primary opacity-25 w-100 mb-10"></div>
                </div>

                <div class="d-flex flex-center flex-column flex-column-fluid pb-15">
                    <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('frontend.login') }}">
                        @csrf
                        <div class="fv-row mb-8">
                            <label class="form-label fs-6 fw-bold text-gray-900">Email atau Username</label>
                            <input type="text" placeholder="Masukkan Email / Username" name="email" autocomplete="off"
                                class="form-control form-control-gov bg-transparent" />
                        </div>

                        <div class="fv-row mb-3 position-relative" data-kt-password-meter="true">
                            <label class="form-label fs-6 fw-bold text-gray-900">Kata Sandi</label>
                            <div class="position-relative">
                                <input type="password" placeholder="Masukkan Kata Sandi" name="password" autocomplete="off"
                                    class="form-control form-control-gov bg-transparent" id="passwordInput" />
                                <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" id="togglePassword">
                                    <i class="ki-outline ki-eye-slash fs-2" id="toggleIcon"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-10">
                            <div></div>
                            <a href="#" class="link-primary fw-bold">Lupa Kata Sandi?</a>
                        </div>

                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary-gov btn-lg py-4">
                                <span class="indicator-label fw-bold">Masuk</span>
                                <span class="indicator-progress fw-bold">Harap tunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center">
                        <p class="text-gray-500 fw-semibold fs-7">© {{ date('Y') }} Dinas Komunikasi, Informatika, Statistik dan Persandian <br> Kabupaten Deli Serdang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const togglePassword = document.querySelector('#togglePassword');
            const passwordInput = document.querySelector('#passwordInput');
            const toggleIcon = document.querySelector('#toggleIcon');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function(e) {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    if (type === 'text') {
                        toggleIcon.classList.remove('ki-eye-slash');
                        toggleIcon.classList.add('ki-eye');
                    } else {
                        toggleIcon.classList.remove('ki-eye');
                        toggleIcon.classList.add('ki-eye-slash');
                    }
                });
            }

            document.getElementById('kt_sign_in_form').addEventListener('submit', function(e) {
                e.preventDefault();
                const submitButton = document.getElementById('kt_sign_in_submit');
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.classList.add("disabled");
                submitButton.disabled = true;

                const label = submitButton.querySelector('.indicator-label');
                const progress = submitButton.querySelector('.indicator-progress');
                if (label) label.style.display = 'none';
                if (progress) progress.style.display = 'inline-block';

                let formData = new FormData(this);
                fetch("{{ route('frontend.login') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(async response => {
                        let result = await response.json();
                        if (!response.ok) {
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.classList.remove("disabled");
                            submitButton.disabled = false;
                            if (label) label.style.display = 'block';
                            if (progress) progress.style.display = 'none';

                            let errorMessage = "Terjadi kesalahan sistem.";
                            if (result.errors && result.errors.email) {
                                errorMessage = result.errors.email[0];
                            } else if (result.message) {
                                errorMessage = result.message;
                            }

                            Swal.fire({
                                icon: "error",
                                title: "Login Gagal!",
                                text: errorMessage,
                                buttonsStyling: false,
                                customClass: { confirmButton: "btn btn-danger" }
                            });
                            return;
                        }

                        let redirectUrl = result.redirect || "{{ route('frontend.dashboard') }}";
                        Swal.fire({
                            icon: "success",
                            title: "Login Berhasil",
                            text: "Selamat Datang di Portal eSakip Deli Serdang",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = redirectUrl;
                        });
                    })
                    .catch(err => {
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                        if (label) label.style.display = 'block';
                        if (progress) progress.style.display = 'none';
                        Swal.fire({
                            icon: "error",
                            title: "Error Jaringan",
                            text: "Tidak dapat terhubung ke server.",
                            confirmButtonColor: "#d33"
                        });
                    });
            });
        </script>
    @endpush
@endsection
