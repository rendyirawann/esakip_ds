{{-- Demo 11 Horizontal Menu Bar --}}
<div class="header-menu flex-column flex-lg-row"
    data-kt-drawer="true" data-kt-drawer-name="header-menu"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_header_menu_toggle"
    data-kt-swapper="true" data-kt-swapper-mode="prepend"
    data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">

    <!--begin::Menu-->
    <div class="menu menu-rounded menu-column menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-state-primary menu-title-gray-800 menu-arrow-gray-500 align-items-stretch flex-grow-1 my-5 my-lg-0 px-2 px-lg-0 fw-semibold fs-6"
        id="#kt_header_menu" data-kt-menu="true">

        <!--begin::Dashboard-->
        <div class="menu-item me-0 me-lg-2 {{ request()->routeIs('frontend.dashboard') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('frontend.dashboard') }}">
                <span class="menu-icon"><i class="ki-outline ki-element-11 fs-3"></i></span>
                <span class="menu-title">Dashboard</span>
            </a>
        </div>

        <!--begin::RENSTRA-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-document fs-3"></i></span>
                <span class="menu-title">RENSTRA</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-250px">
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.dataskpd.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.dataskpd.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Data SKPD</span></a></div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Sasaran-Tujuan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-250px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.sasaran.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.sasaran.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Sasaran Renstra</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.tujuan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.tujuan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tujuan Renstra</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.indikator-tujuan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.indikator-tujuan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Indikator Tujuan Renstra</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.formulasi.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.formulasi.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Formulasi Renstra</span></a></div>
                    </div>
                </div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.strategi.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.strategi.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Strategi</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.kebijakan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.kebijakan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Kebijakan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.cascadingprogram.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingprogram.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Program</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.cascadingkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Kegiatan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.renstra.cascadingsubkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.renstra.cascadingsubkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Sub Kegiatan</span></a></div>
            </div>
        </div>

        <!--begin::RKT-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1 {{ request()->is('frontend/rkt*') ? 'here show' : '' }}" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-scroll fs-3"></i></span>
                <span class="menu-title">RKT</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-300px">
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.sasaran.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.sasaran.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Sasaran</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.program.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Program</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Indikator Kegiatan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.subkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.subkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target RKT Output Sub Kegiatan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.anggaran-program.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.anggaran-program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program RKT</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.anggaran-kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.anggaran-kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan RKT</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.rkt.anggaran-subkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.rkt.anggaran-subkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan RKT</span></a></div>
            </div>
        </div>

        <!--begin::PK-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-clipboard fs-3"></i></span>
                <span class="menu-title">PK</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-300px">
                <div class="menu-item menu-accordion {{ request()->is('frontend/pk/sasaran*') ? 'here show' : '' }}" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3 {{ request()->is('frontend/pk/sasaran*') ? 'active' : '' }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Sasaran</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.sasaran.index') ? 'active' : '' }}" href="{{ route('frontend.pk.sasaran.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.sasaran.triwulan.*') ? 'active' : '' }}" href="{{ route('frontend.pk.sasaran.triwulan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion {{ request()->is('frontend/pk/program*') ? 'here show' : '' }}" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3 {{ request()->is('frontend/pk/program*') ? 'active' : '' }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Program</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.program.index') ? 'active' : '' }}" href="{{ route('frontend.pk.program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.program.triwulan.*') ? 'active' : '' }}" href="{{ route('frontend.pk.program.triwulan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion {{ request()->is('frontend/pk/kegiatan*') ? 'here show' : '' }}" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3 {{ request()->is('frontend/pk/kegiatan*') ? 'active' : '' }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.pk.kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.kegiatan.triwulan.*') ? 'active' : '' }}" href="{{ route('frontend.pk.kegiatan.triwulan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion {{ request()->is('frontend/pk/subkegiatan*') ? 'here show' : '' }}" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3 {{ request()->is('frontend/pk/subkegiatan*') ? 'active' : '' }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.subkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.pk.subkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.subkegiatan.triwulan.*') ? 'active' : '' }}" href="{{ route('frontend.pk.subkegiatan.triwulan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.anggaran-program.index') ? 'active' : '' }}" href="{{ route('frontend.pk.anggaran-program.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program PK</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.anggaran-kegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.pk.anggaran-kegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan PK</span></a></div>
                <div class="menu-item"><a class="menu-link py-3 {{ request()->routeIs('frontend.pk.anggaran-subkegiatan.index') ? 'active' : '' }}" href="{{ route('frontend.pk.anggaran-subkegiatan.index') }}"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan PK</span></a></div>
            </div>
        </div>

        <!--begin::PK Perubahan-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-notepad-edit fs-3"></i></span>
                <span class="menu-title">PK Perubahan</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-350px">
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Sasaran</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <!-- Repeat other PK Perubahan items... -->
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Program</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Target PK Perubahan Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                    </div>
                </div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Program PK Perubahan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Kegiatan PK Perubahan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Anggaran Sub Kegiatan PK Perubahan</span></a></div>
            </div>
        </div>

        <!--begin::Capaian Kinerja-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-chart-line fs-3"></i></span>
                <span class="menu-title">Capaian Kinerja</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-300px">
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Sasaran</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Program</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                    </div>
                </div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Realisasi Indikator Sub Kegiatan</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                    </div>
                </div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Penyerapan Anggaran</span></a></div>
            </div>
        </div>

        <!--begin::Laporan SAKIP-->
        <div class="menu-item menu-lg-indention me-0 me-lg-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start">
            <span class="menu-link py-3">
                <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-3"></i></span>
                <span class="menu-title">Laporan SAKIP</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-dropdown px-lg-2 py-lg-4 w-lg-300px">
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Renstra</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Rencana Kinerja Tahunan</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Indikator Kinerja Utama</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Perjanjian Kinerja</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Capaian Indikator Kinerja Utama</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Pagu dan Realisasi Anggaran</span></a></div>
                <div class="menu-item menu-accordion" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start">
                    <span class="menu-link py-3"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Analisis Pencapaian Sasaran</span><span class="menu-arrow"></span></span>
                    <div class="menu-sub menu-sub-lg-dropdown w-lg-200px px-lg-2 py-lg-4">
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Triwulan</span></a></div>
                        <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Tahunan</span></a></div>
                    </div>
                </div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Rencana Aksi</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Efisiensi dan Efektifitas Kinerja</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Evaluasi RKPD</span></a></div>
                <div class="menu-item"><a class="menu-link py-3" href="#"><span class="menu-bullet"><span class="bullet bullet-dot"></span></span><span class="menu-title">Evaluasi Renja</span></a></div>
            </div>
        </div>

    </div>
    <!--end::Menu-->
</div>
