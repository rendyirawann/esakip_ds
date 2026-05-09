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
        <div class="menu-item me-0 me-lg-2 {{ request()->routeIs('dashboard') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('dashboard') }}">
                <span class="menu-icon">
                    <i class="ki-outline ki-element-11 fs-3"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </div>
        <!--end::Dashboard-->

        <!--begin::User Management-->
        @role('Superadmin|superadmin')
        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('admin/users*') || request()->is('admin/roles*') ? 'here show menu-here-bg' : '' }}">
            <span class="menu-link py-3">
                <span class="menu-icon">
                    <i class="ki-outline ki-people fs-3"></i>
                </span>
                <span class="menu-title">User Management</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown py-4 w-200px">
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-user fs-4"></i></span>
                        <span class="menu-title">Users</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                        <span class="menu-icon"><i class="ki-outline ki-shield-tick fs-4"></i></span>
                        <span class="menu-title">Roles & Permissions</span>
                    </a>
                </div>
            </div>
        </div>
        @endrole
        <!--end::User Management-->

        <!--begin::SAKIP Data Master-->
        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2 {{ request()->is('admin/sakip*') ? 'here show menu-here-bg' : '' }}">
            <span class="menu-link py-3">
                <span class="menu-icon">
                    <i class="ki-outline ki-save-2 fs-3"></i>
                </span>
                <span class="menu-title">SAKIP Data Master</span>
                <span class="menu-arrow d-lg-none"></span>
            </span>
            <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown py-4 w-250px">
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/visi*') ? 'active' : '' }}" href="{{ route('sakip.visi.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Visi</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/misi*') ? 'active' : '' }}" href="{{ route('sakip.misi.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Misi</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/urusan*') ? 'active' : '' }}" href="{{ route('sakip.urusan.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Urusan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/bidang*') ? 'active' : '' }}" href="{{ route('sakip.bidang.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Bidang</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/periode*') ? 'active' : '' }}" href="{{ route('sakip.periode.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Periode</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/program*') ? 'active' : '' }}" href="{{ route('sakip.program.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Program</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/kegiatan*') ? 'active' : '' }}" href="{{ route('sakip.kegiatan.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Kegiatan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/subkegiatan*') ? 'active' : '' }}" href="{{ route('sakip.subkegiatan.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Sub Kegiatan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/skpd*') ? 'active' : '' }}" href="{{ route('sakip.skpd.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">SKPD & Penjabat</span>
                    </a>
                </div>
                <div class="separator mx-5 my-2"></div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/pimpinan*') ? 'active' : '' }}" href="{{ route('sakip.pimpinan.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Pimpinan</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/subunit*') ? 'active' : '' }}" href="{{ route('sakip.subunit.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Subunit</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/title*') ? 'active' : '' }}" href="{{ route('sakip.title.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Title</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/unitkerja*') ? 'active' : '' }}" href="{{ route('sakip.unitkerja.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Unit Kerja</span>
                    </a>
                </div>
                <div class="menu-item">
                    <a class="menu-link {{ request()->is('admin/sakip/pegawaibappeda*') ? 'active' : '' }}" href="{{ route('sakip.pegawaibappeda.index') }}">
                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                        <span class="menu-title">Pegawai Bappeda</span>
                    </a>
                </div>
            </div>
        </div>
        <!--end::SAKIP Data Master-->

        <!--begin::Settings-->
        @role('Superadmin|superadmin')
        <div class="menu-item me-0 me-lg-2 {{ request()->routeIs('settings.*') ? 'here show menu-here-bg' : '' }}">
            <a class="menu-link py-3" href="{{ route('settings.index') }}">
                <span class="menu-icon">
                    <i class="ki-outline ki-setting-2 fs-3"></i>
                </span>
                <span class="menu-title">Settings</span>
            </a>
        </div>
        @endrole
        <!--end::Settings-->

    </div>
    <!--end::Menu-->
</div>
