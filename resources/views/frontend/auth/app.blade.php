<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <title>@yield('title') — {{ $appSettings['site_name'] ?? 'eSakip' }} Deli Serdang</title>
    <meta charset="utf-8" />
    <meta name="description" content="eSakip Pemerintah Kabupaten Deli Serdang — Portal SKPD" />
    <meta name="author" content="Rendy Irawan" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="eSakip Deli Serdang — Login" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="eSakip Deli Serdang" />
    
    @php
        $siteLogo = $appSettings['site_logo'] ?? 'base-logo.png';
        $siteFont = $appSettings['site_font'] ?? 'Plus Jakarta Sans';
    @endphp
    <link rel="shortcut icon" href="{{ asset('assets-front/media/logos/' . $siteLogo) }}" />
    
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $siteFont) }}:wght@300;400;500;600;700;800&display=swap" />
    <!--end::Fonts-->
    
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets-front/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-front/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    
    <style>
        :root {
            --bs-font-sans-serif: '{{ $siteFont }}', sans-serif;
            --bs-body-font-family: '{{ $siteFont }}', sans-serif;
            --gov-blue: #004aad;
            --gov-dark: #003a8c;
        }
        body { 
            font-family: '{{ $siteFont }}', sans-serif !important;
            background-color: #f8f9fa;
        }
        .auth-bg {
            background-image: url('{{ asset('assets-front/media/auth/bg10.jpeg') }}');
            background-size: cover;
            background-position: center;
        }
        [data-bs-theme="dark"] .auth-bg {
            background-image: url('{{ asset('assets-front/media/auth/bg10-dark.jpeg') }}');
        }
        .aside-content {
            background: linear-gradient(135deg, rgba(0, 74, 173, 0.95) 0%, rgba(0, 58, 140, 0.9) 100%);
            backdrop-filter: blur(5px);
        }
        .login-aside-img {
            max-width: 400px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
        }
    </style>
    @stack('stylesheets')
</head>
<!--end::Head-->

<body id="kt_body" class="app-blank">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-lg-row flex-column-fluid auth-bg">
            <!--begin::Body-->
            <div class="d-flex flex-column flex-column-fluid flex-center w-lg-50 p-10">
                @yield('content')
            </div>
            <!--end::Body-->

            <!--begin::Aside-->
            <div class="d-none d-lg-flex flex-lg-row-fluid w-lg-50 aside-content">
                <div class="d-flex flex-column flex-center p-10 w-100">
                    <!--Logo Putih Besar -->
                    <img class="mx-auto mw-100 w-150px w-lg-200px mb-10 login-aside-img"
                        src="{{ asset('assets-front/media/logos/' . $siteLogo) }}" alt="Logo Deli Serdang" />

                    <h1 class="text-white fs-2qx fw-bolder text-center mb-7">
                        Sistem Akuntabilitas Kinerja <br> Instansi Pemerintah
                    </h1>
                    
                    <div class="text-white fs-4 text-center fw-semibold opacity-75">
                        Pemerintah Kabupaten Deli Serdang <br>
                        <span class="fs-6 mt-4 d-block">Mewujudkan Deli Serdang yang Maju dan Sejahtera dengan Tata Kelola Pemerintahan yang Akuntabel.</span>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="mt-20 d-flex gap-5">
                        <div class="badge badge-outline badge-light px-4 py-3 opacity-50">Transparan</div>
                        <div class="badge badge-outline badge-light px-4 py-3 opacity-50">Akuntabel</div>
                        <div class="badge badge-outline badge-light px-4 py-3 opacity-50">Efektif</div>
                    </div>
                </div>
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script src="{{ asset('assets-front/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/js/scripts.bundle.js') }}"></script>
    @stack('scripts')
</body>
</html>
