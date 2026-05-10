<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->
<head>
    <base href="{{ url('/') }}/" />
    <title>@yield('title') — {{ $appSettings['site_name'] ?? 'StarterTemp' }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="{{ $appSettings['site_name'] ?? 'StarterTemp' }} — Admin Dashboard" />
    <meta name="author" content="Rendy Irawan" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="id_ID" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="{{ $appSettings['site_name'] ?? 'StarterTemp' }} — Dashboard" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $appSettings['site_name'] ?? 'StarterTemp' }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $siteLogo = $appSettings['site_logo'] ?? 'base-logo.png';
        $siteFont = $appSettings['site_font'] ?? 'Plus Jakarta Sans';
        $siteName = $appSettings['site_name'] ?? 'StarterTemp';
    @endphp
    <link rel="shortcut icon" href="{{ asset('assets-front/media/logos/' . $siteLogo) }}" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $siteFont) }}:wght@300;400;500;600;700;800&display=swap" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets-->
    <link href="{{ asset('assets-front/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-front/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets-front/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets-front/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>
        :root {
            --bs-font-sans-serif: '{{ $siteFont }}', sans-serif;
            --bs-body-font-family: '{{ $siteFont }}', sans-serif;
        }
        body {
            font-family: '{{ $siteFont }}', sans-serif !important;
        }
        h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
            font-family: '{{ $siteFont }}', sans-serif !important;
        }
        /* Sidebar overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }

        /* Right Side Modal (Drawer style) */
        .modal.modal-right .modal-dialog {
            margin: 0 0 0 auto;
            max-width: 500px;
            height: 100vh;
            overflow: hidden;
        }
        .modal.modal-right .modal-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
            border-radius: 0;
            border: none;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .modal.modal-right .modal-content > form {
            display: flex;
            flex-direction: column;
            height: 100%;
            overflow: hidden;
        }
        .modal.modal-right.fade .modal-dialog {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        .modal.modal-right.show .modal-dialog {
            transform: translateX(0);
        }
        .modal.modal-right .modal-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding: 1.5rem;
            position: relative;
        }
        .modal.modal-right .modal-header {
            flex-shrink: 0;
            background: white;
            z-index: 5;
            border-bottom: 1px solid #eff2f5;
        }
        .modal.modal-right .modal-footer {
            flex-shrink: 0;
            padding: 1.25rem 1.5rem;
            background: white;
            z-index: 5;
            border-top: 1px solid #eff2f5;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }

        .sidebar-overlay.active { display: block; }
        /* Custom sidebar styles for Demo 11 */
        #kt_app_sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 280px;
            z-index: 105;
            background: #fff;
            border-right: 1px solid var(--bs-gray-200);
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        #kt_app_sidebar.active { transform: translateX(0); }
        [data-bs-theme="dark"] #kt_app_sidebar {
            background: #1e1e2d;
            border-right-color: rgba(255,255,255,0.07);
        }
        /* Custom modal sliding from right (Drawer Style) */
        .modal.drawer.fade .modal-dialog {
            position: fixed;
            margin: auto;
            width: 100%;
            height: 100%;
            right: -100%;
            transform: translate3d(0%, 0, 0);
            transition: right 0.3s ease-out;
        }
        .modal.drawer.show .modal-dialog {
            right: 0;
        }
        .modal.drawer .modal-content {
            height: 100vh;
            border-radius: 0;
            border: none;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
        }
        .modal.drawer .modal-header {
            border-radius: 0;
            padding: 1.5rem;
            background: var(--bs-body-bg);
            border-bottom: 1px solid var(--bs-gray-200);
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .modal.drawer .modal-footer {
            border-radius: 0;
            padding: 1.5rem;
            background: var(--bs-body-bg);
            border-top: 1px solid var(--bs-gray-200);
            position: sticky;
            bottom: 0;
            z-index: 2;
        }
        .modal.drawer .modal-body {
            padding: 1.5rem;
            overflow-y: auto;
        }
        @media (min-width: 576px) {
            .modal.drawer .modal-dialog {
                max-width: 500px;
                right: -500px;
                margin: 0;
            }
        }
        @media (min-width: 992px) {
            .modal.drawer .modal-dialog.modal-lg, 
            .modal.drawer .modal-dialog.modal-xl, 
            .modal.drawer .modal-dialog.mw-750px, 
            .modal.drawer .modal-dialog.mw-950px {
                max-width: 700px;
                right: -700px;
            }
            .modal.drawer.show .modal-dialog.modal-lg, 
            .modal.drawer.show .modal-dialog.modal-xl, 
            .modal.drawer.show .modal-dialog.mw-750px, 
            .modal.drawer.show .modal-dialog.mw-950px {
                right: 0;
            }
        }

        /* Loading Spinner Overlay */
        #global_loader {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255, 255, 255, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        [data-bs-theme="dark"] #global_loader {
            background: rgba(30, 30, 45, 0.7);
        }
    </style>
    <script>
        function hideLoader() {
            const loader = document.getElementById('global_loader');
            if (loader) loader.style.display = 'none';
            document.querySelectorAll('[data-kt-indicator="on"]').forEach(btn => {
                btn.removeAttribute('data-kt-indicator');
                btn.disabled = false;
            });
        }
    </script>
    <script>
        if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
    <style>
        .ck-editor__editable {
            min-height: 200px;
        }
        .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
            border-color: var(--bs-gray-300);
            background-color: var(--bs-gray-100);
        }
    </style>
    @stack('stylesheets')
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Global Loader-->
    <div id="global_loader">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <!--end::Global Loader-->

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                    <!--begin::Container (Top Bar)-->
                    <div class="container-xxl d-flex flex-grow-1 flex-stack">
                        <!--begin::Header Logo-->
                        <div class="d-flex align-items-center me-5">
                            <!--begin::Sidebar toggle-->
                            <div class="btn btn-icon btn-active-color-primary w-30px h-30px ms-n2 me-3" id="kt_app_sidebar_toggle">
                                <i class="ki-outline ki-abstract-14 fs-2"></i>
                            </div>
                            <!--end::Sidebar toggle-->
                            
                            <a href="{{ route('frontend.dashboard') }}">
                                <img alt="Logo" src="{{ asset('assets-front/media/logos/' . $siteLogo) }}" class="theme-light-show h-20px h-lg-30px" />
                                <img alt="Logo" src="{{ asset('assets-front/media/logos/' . $siteLogo) }}" class="theme-dark-show h-20px h-lg-30px" />
                            </a>
                        </div>
                        <!--end::Header Logo-->
 
                        <!--begin::Topbar-->
                        <div class="d-flex align-items-center">
                            @include('frontend.layout.navbar')
                             
                            <!--begin::Header menu toggle (mobile)-->
                            <div class="d-lg-none btn btn-icon btn-active-color-primary w-30px h-30px ms-2" id="kt_header_menu_toggle">
                                <i class="ki-outline ki-text-align-left fs-2"></i>
                            </div>
                            <!--end::Header menu toggle-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                    <!--begin::Separator-->
                    <div class="separator"></div>
                    <!--end::Separator-->
                    <!--begin::Container (Menu Bar)-->
                    <div class="header-menu-container container-xxl d-flex flex-stack h-lg-75px w-100" id="kt_header_nav">
                        <!--begin::Menu wrapper-->
                        @include('frontend.layout.menu')
                        <!--end::Menu wrapper-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->
 
                <!--begin::Container-->
                <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
                    <!--begin::Sidebar (Custom for Demo 11)-->
                    @include('frontend.layout.sidebar')
                    <!--end::Sidebar-->
                    <!--begin::Content-->
                    <div class="content flex-row-fluid" id="kt_content">
                        @yield('content')
                    </div>
                    <!--end::Content-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->

    <!-- Modal Placeholder for Global Yield if needed -->
    @yield('modals')

                <!--begin::Footer-->
                @include('frontend.layout.footer')
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--end::Main-->

    <!--begin::Sidebar Overlay (mobile)-->
    <div class="sidebar-overlay" id="kt_sidebar_overlay"></div>

    <!--begin::Javascript-->
    <script>
        var hostUrl = "{{ asset('assets-front/') }}";
    </script>
    <script src="{{ asset('assets-front/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/js/widgets.bundle.js') }}"></script>
    <script src="{{ asset('assets-front/js/custom/widgets.js') }}"></script>
    <script src="{{ asset('assets-front/js/custom/apps/chat/chat.js') }}"></script>
    <script src="{{ asset('assets-front/js/custom/utilities/modals/create-campaign.js') }}"></script>
    <script src="{{ asset('assets-front/js/custom/utilities/modals/users-search.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Sidebar Toggle ---
            const sidebar = document.getElementById('kt_app_sidebar');
            const overlay = document.getElementById('kt_sidebar_overlay');
            const toggleBtn = document.getElementById('kt_app_sidebar_toggle');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });
            }
            
            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // --- Global Form Submit Spinner (Only for non-AJAX forms) ---
            document.querySelectorAll('form:not([data-kt-no-indicator]):not(.ajax-form)').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!this.checkValidity()) return;

                    const submitBtn = this.querySelector('[type="submit"]');
                    const loader = document.getElementById('global_loader');
                    
                    if (submitBtn) {
                        submitBtn.setAttribute('data-kt-indicator', 'on');
                        submitBtn.disabled = true;
                    }
                    
                    if (loader && !this.classList.contains('no-loader')) {
                        loader.style.display = 'flex';
                    }
                });
            });

            // --- Global SweetAlert for Session ---
            const swalConfig = {
                buttonsStyling: false,
                confirmButtonText: "Ok, Lanjutkan",
                customClass: { confirmButton: "btn btn-primary" }
            };

            @if(session('success'))
                Swal.fire({ ...swalConfig, text: "{!! session('success') !!}", icon: "success" });
            @endif
            @if(session('error'))
                Swal.fire({ ...swalConfig, text: "{!! session('error') !!}", icon: "error", customClass: { confirmButton: "btn btn-danger" } });
            @endif
            @if(session('warning'))
                Swal.fire({ ...swalConfig, text: "{!! session('warning') !!}", icon: "warning", customClass: { confirmButton: "btn btn-warning" } });
            @endif

            // --- Global SweetAlert for Validation Errors ---
            @if($errors->any())
                let errorList = '';
                @foreach($errors->all() as $error)
                    errorList += `<div class="d-flex align-items-center mb-2">
                        <i class="ki-outline ki-cross-circle fs-3 text-danger me-2"></i>
                        <span>{{ $error }}</span>
                    </div>`;
                @endforeach
                Swal.fire({
                    html: `<div class="text-start">${errorList}</div>`,
                    icon: 'error',
                    title: 'Ada Kesalahan Input',
                    buttonsStyling: false,
                    confirmButtonText: 'Perbaiki Sekarang',
                    customClass: { confirmButton: 'btn btn-danger' }
                });
            @endif

            // --- Quick Search ---
            const searchPages = [
                { title: 'Dashboard', url: "{{ route('frontend.dashboard') }}", icon: 'ki-element-11', desc: 'Main dashboard overview' },
            ];
            const searchInput = document.querySelector('[data-kt-search-element="input"]');
            const resultsEl = document.querySelector('[data-kt-search-element="results"]');
            const mainEl = document.querySelector('[data-kt-search-element="main"]');
            const emptyEl = document.querySelector('[data-kt-search-element="empty"]');
            const resultsContainer = document.getElementById('kt_header_search_results');
            if(searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();
                    if(query.length > 1) {
                        mainEl.classList.add('d-none');
                        const filtered = searchPages.filter(p => p.title.toLowerCase().includes(query) || p.desc.toLowerCase().includes(query));
                        if(filtered.length > 0) {
                            emptyEl.classList.add('d-none');
                            resultsEl.classList.remove('d-none');
                            let html = '';
                            filtered.forEach(p => {
                                html += `<a href="${p.url}" class="d-flex text-gray-900 text-hover-primary align-items-center mb-5"><div class="symbol symbol-40px me-4"><span class="symbol-label bg-light"><i class="ki-outline ${p.icon} fs-2 text-primary"></i></span></div><div class="d-flex flex-column"><span class="fs-6 fw-bold">${p.title}</span><span class="fs-7 fw-semibold text-muted">${p.desc}</span></div></a>`;
                            });
                            resultsContainer.innerHTML = html;
                        } else {
                            resultsEl.classList.add('d-none');
                            emptyEl.classList.remove('d-none');
                        }
                    } else {
                        mainEl.classList.remove('d-none');
                        resultsEl.classList.add('d-none');
                        emptyEl.classList.add('d-none');
                    }
                });
            }

            // --- Force Logout Listener ---
            @if(Auth::guard('frontend')->check())
            const userId = "{{ Auth::guard('frontend')->id() }}";
            const waitForEchoLogout = setInterval(() => {
                if (window.Echo) {
                    clearInterval(waitForEchoLogout);
                    window.Echo.private(`App.Models.User.${userId}`)
                        .listen('ForceLogoutNotification', (e) => {
                            Swal.fire({
                                title: 'Keamanan Akun', text: e.message, icon: 'warning',
                                allowOutsideClick: false, allowEscapeKey: false,
                                confirmButtonText: 'OK, Logout', confirmButtonColor: '#d33'
                            }).then(() => { window.location.href = "{{ route('frontend.login') }}"; });
                        });
                }
            }, 500);
            @endif
        });
    </script>
    <!--end::Javascript-->
    @stack('scripts')
</body>
<!--end::Body-->
</html>
