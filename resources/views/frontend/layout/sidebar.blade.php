{{-- Sidebar (Custom addition for Demo 11 layout) --}}
<div id="kt_app_sidebar" class="d-flex flex-column">

    <!--begin::Sidebar Header-->
    <div class="d-flex flex-column align-items-center px-6 pt-8 pb-5">
        @php $siteLogo = $appSettings['site_logo'] ?? 'base-logo.png'; @endphp
        <a href="{{ route('frontend.dashboard') }}" class="mb-4">
            <img alt="Logo" src="{{ asset('assets-front/media/logos/' . $siteLogo) }}" class="h-40px" />
        </a>
        <h5 class="fw-bold text-gray-800 mb-0 fs-6">eSakip Deli Serdang</h5>
        <span class="text-muted fs-8">Navigation</span>
    </div>
    <!--end::Sidebar Header-->

    <div class="separator mx-6 mb-3"></div>

    <!--begin::Sidebar Menu-->
    <div class="px-4 flex-column-fluid">
        <div class="menu menu-column menu-rounded menu-sub-indention menu-active-bg fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true">

            <!--begin::Dashboard-->
            <div class="menu-item">
                <a class="menu-link {{ request()->routeIs('frontend.dashboard') ? 'active' : '' }}" href="{{ route('frontend.dashboard') }}">
                    <span class="menu-icon">
                        <i class="ki-outline ki-element-11 fs-3"></i>
                    </span>
                    <span class="menu-title">Dashboard</span>
                </a>
            </div>
            <!--end::Dashboard-->

            <!--begin::Section: Perencanaan-->
            <div class="menu-item pt-5">
                <div class="menu-content">
                    <span class="menu-heading fw-bold text-uppercase fs-7">Perencanaan</span>
                </div>
            </div>

            <!--begin::RENSTRA-->
            <div class="menu-item menu-accordion {{ request()->is('frontend/renstra*') ? 'here show' : '' }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-document fs-3"></i></span><span class="menu-title">RENSTRA</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.renstra.dataskpd.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.dataskpd.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Data SKPD</span></a></div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Sasaran-Tujuan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.renstra.sasaran.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.sasaran.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Sasaran Renstra</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tujuan Renstra</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Indikator Tujuan Renstra</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Formulasi Renstra</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Strategi</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Kebijakan</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.renstra.cascadingprogram.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingprogram.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Cascading Program</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.renstra.cascadingkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Cascading Kegiatan</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.renstra.cascadingsubkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingsubkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Cascading Sub Kegiatan</span></a></div>
                </div>
            </div>

            <!--begin::RKT-->
            <div class="menu-item menu-accordion {{ request()->is('frontend/rkt*') ? 'here show' : '' }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-scroll fs-3"></i></span><span class="menu-title">RKT</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.sasaran.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.sasaran.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Sasaran</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.program.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Program</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Kegiatan</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.subkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.subkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Output Sub Kegiatan</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.anggaran-program.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.anggaran-program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program RKT</span></a></div>
                    <div class="menu-item"><a class="menu-link {{ request()->routeIs('frontend.rkt.anggaran-kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.anggaran-kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan RKT</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan RKT</span></a></div>
                </div>
            </div>

            <!--begin::PK-->
            <div class="menu-item menu-accordion {{ request()->is('frontend/pk*') ? 'here show' : '' && !request()->is('frontend/pk-perubahan*') }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-clipboard fs-3"></i></span><span class="menu-title">PK</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Sasaran</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <!-- Repeat for Program, Kegiatan, Sub Kegiatan -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Program</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program PK</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan PK</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan PK</span></a></div>
                </div>
            </div>

            <!--begin::PK Perubahan-->
            <div class="menu-item menu-accordion {{ request()->is('frontend/pk-perubahan*') ? 'here show' : '' }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-notepad-edit fs-3"></i></span><span class="menu-title">PK Perubahan</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Sasaran</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <!-- Repeat for Program, Kegiatan, Sub Kegiatan -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Program</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program PK Perubahan</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan PK Perubahan</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan PK Perubahan</span></a></div>
                </div>
            </div>

            <!--begin::Capaian Kinerja-->
            <div class="menu-item menu-accordion {{ request()->is('frontend/capaian*') ? 'here show' : '' }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-chart-line fs-3"></i></span><span class="menu-title">Capaian Kinerja</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <!-- Realisasi Indikator Sasaran -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Sasaran</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        </div>
                    </div>
                    <!-- Realisasi Indikator Program -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Program</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        </div>
                    </div>
                    <!-- Realisasi Indikator Kegiatan -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        </div>
                    </div>
                    <!-- Realisasi Indikator Sub Kegiatan -->
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Penyerapan Anggaran</span></a></div>
                </div>
            </div>

            <!--begin::Laporan SAKIP-->
            <div class="menu-item pt-5">
                <div class="menu-content"><span class="menu-heading fw-bold text-uppercase fs-7">Laporan</span></div>
            </div>
            <div class="menu-item menu-accordion {{ request()->is('frontend/laporan*') ? 'here show' : '' }}" data-kt-menu-trigger="click">
                <span class="menu-link"><span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-3"></i></span><span class="menu-title">Laporan SAKIP</span><span class="menu-arrow"></span></span>
                <div class="menu-sub menu-sub-accordion">
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Renstra</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Rencana Kinerja Tahunan</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Indikator Kinerja Utama</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Perjanjian Kinerja</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Capaian Indikator Kinerja Utama</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Pagu dan Realisasi Anggaran</span></a></div>
                    <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                        <span class="menu-link"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Analisis Pencapaian Sasaran</span><span class="menu-arrow"></span></span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                            <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        </div>
                    </div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Rencana Aksi</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Efisiensi dan Efektifitas Kinerja</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Evaluasi RKPD</span></a></div>
                    <div class="menu-item"><a class="menu-link" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Evaluasi Renja</span></a></div>
                </div>
            </div>

        </div>
    </div>
    <!--end::Sidebar Menu-->

    <!--begin::Sidebar Footer-->
    <div class="px-6 py-5 mt-auto">
        <div class="separator mb-4"></div>
        <div class="d-flex align-items-center">
            @if(Auth::guard('frontend')->check())
            <div class="symbol symbol-35px me-3">
                <img alt="Avatar" src="{{ asset('assets-front/media/avatars/' . (Auth::guard('frontend')->user()->avatar ?? 'default.png')) }}" />
            </div>
            <div class="d-flex flex-column flex-grow-1">
                <span class="fw-bold fs-7 text-gray-800">{{ Auth::guard('frontend')->user()->name }}</span>
                <span class="text-muted fs-8">{{ Auth::guard('frontend')->user()->roles->first()->name ?? 'User' }}</span>
            </div>
            @endif
        </div>
    </div>
    <!--end::Sidebar Footer-->
</div>
