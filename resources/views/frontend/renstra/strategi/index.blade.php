@extends('frontend.layout.app')

@section('title', 'Strategi Renstra')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Strategi Renstra</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Renstra</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Strategi Renstra</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!-- Filter Card -->
            <div class="card shadow-sm mb-5">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex flex-column">
                            <span class="fs-4 fw-bold text-gray-900">Filter Data</span>
                            <span class="fs-7 text-gray-500">Sesuaikan periode dan unit kerja</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Pilih SKPD</label>
                            <select id="filter_skpd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD" {{ !$isSuperadmin ? 'disabled' : '' }}>
                                <option value="">Pilih SKPD</option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->refskpd_id }}" {{ (isset($current_skpd) && $current_skpd->refskpd_id == $skpd->refskpd_id) ? 'selected' : '' }}>
                                        {{ $skpd->nama_skpd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pilih Periode (Tahun)</label>
                            <select id="filter_periode" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode">
                                <option value="">Pilih Periode</option>
                                @foreach($periodes as $periode)
                                    <option value="{{ $periode->refperiode_id }}">{{ $periode->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 h-45px">
                                <i class="ki-outline ki-magnifier fs-2 me-1"></i> Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" class="card shadow-sm">
                <div class="card-body d-flex flex-column flex-center p-15">
                    <img src="{{ asset('assets-front/media/illustrations/sketchy-1/5.png') }}" class="mw-350px mb-10" alt="Pilih Data" />
                    <div class="fs-1 fw-bolder text-dark mb-4">Pilih SKPD untuk melihat data</div>
                    <div class="fs-6 text-gray-500 text-center fw-semibold">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div id="table_card" class="card shadow-sm d-none">
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_strategi_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">No</th>
                                    <th class="min-w-200px">Hierarki</th>
                                    <th class="min-w-200px">Sasaran Renstra</th>
                                    <th class="min-w-300px">Strategi</th>
                                </tr>
                            </thead>
                            <tbody class="fw-semibold text-gray-600">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drawer for Strategi -->
<div id="kt_drawer_strategi" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="strategi" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_strategi_toggle" data-kt-drawer-close="#kt_drawer_strategi_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="kt_drawer_strategi_header">
            <h3 class="card-title fw-bold text-dark" id="drawer_title">Kelola Strategi Renstra</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_strategi_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_drawer_strategi_body">
            <form id="kt_modal_strategi_form" class="form ajax-form">
                @csrf
                <input type="hidden" name="id" id="str_id">
                <input type="hidden" name="refsasaranrenstra_id" id="form_sasaran_id">
                <input type="hidden" name="refskpd_id" id="form_skpd_id">
                <input type="hidden" name="refperiode_id" id="form_periode_id">

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Uraian Strategi</label>
                    <textarea name="uraian_strategi" id="form_uraian" class="form-control form-control-solid" rows="4" placeholder="Masukkan uraian strategi..."></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-light me-3" id="kt_drawer_strategi_close_v2">Batal</button>
                    <button type="submit" id="kt_modal_strategi_submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Mohon tunggu...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var table;
    var datatableInitialized = false;

    function initDataTable() {
        table = $('#kt_strategi_table').DataTable({
            searchDelay: 500,
            processing: false,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.strategi.index') }}",
                type: "GET",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'hierarki', name: 'hierarki', orderable: false},
                {data: 'sasaran_renstra', name: 'sasaran_renstra'},
                {data: 'strategi_list', name: 'strategi_list', orderable: false, searchable: false}
            ],
            language: {
                emptyTable: "Pilih Periode dan klik Tampilkan Data untuk memuat tabel."
            }
        });

        datatableInitialized = true;
    }

    $(document).ready(function() {
        $('#btn_tampilkan').on('click', function() {
            var skpd = $('#filter_skpd').val();
            var periode = $('#filter_periode').val();

            if (!skpd || !periode) {
                Swal.fire({ text: "Silakan pilih SKPD dan Periode terlebih dahulu.", icon: "warning", confirmButtonText: "Ok" });
                return;
            }

            $('#empty_state').addClass('d-none');
            $('#table_card').removeClass('d-none');

            if (!datatableInitialized) {
                initDataTable();
            } else {
                table.ajax.reload();
            }
        });

        $('#kt_modal_strategi_form').on('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        $('#kt_modal_strategi_submit').on('click', function(e) {
            e.preventDefault();
            submitForm();
        });

        $('#kt_drawer_strategi_close_v2, #kt_drawer_strategi_close').on('click', function() {
            var drawerElement = document.querySelector("#kt_drawer_strategi");
            var drawerStrategi = KTDrawer.getInstance(drawerElement);
            if (drawerStrategi) {
                drawerStrategi.hide();
            }
        });
    });

    function manageStrategi(sasaran_id) {
        $('#kt_modal_strategi_form')[0].reset();
        $('#str_id').val('');
        $('#form_sasaran_id').val(sasaran_id);
        $('#form_skpd_id').val($('#filter_skpd').val());
        $('#form_periode_id').val($('#filter_periode').val());
        $('#drawer_title').text('Tambah Strategi Renstra');

        var drawerElement = document.querySelector("#kt_drawer_strategi");
        var drawerStrategi = KTDrawer.getInstance(drawerElement);
        if (!drawerStrategi) {
            drawerStrategi = new KTDrawer(drawerElement);
        }
        drawerStrategi.show();
    }

    function editStrategi(id) {
        $('#kt_modal_strategi_form')[0].reset();
        $('#drawer_title').text('Edit Strategi Renstra');
        
        $.get("{{ route('frontend.renstra.strategi.index') }}/" + id + "/edit", function(data) {
            $('#str_id').val(data.refstrategi_id);
            $('#form_sasaran_id').val(data.refsasaranrenstra_id);
            $('#form_skpd_id').val(data.refskpd_id);
            $('#form_periode_id').val(data.refperiode_id);
            $('#form_uraian').val(data.uraian_strategi);
            
            var drawerElement = document.querySelector("#kt_drawer_strategi");
            var drawerStrategi = KTDrawer.getInstance(drawerElement);
            if (!drawerStrategi) {
                drawerStrategi = new KTDrawer(drawerElement);
            }
            drawerStrategi.show();
        });
    }

    function submitForm() {
        var id = $('#str_id').val();
        var url = id ? "{{ route('frontend.renstra.strategi.index') }}/" + id : "{{ route('frontend.renstra.strategi.store') }}";
        
        var formData = $('#kt_modal_strategi_form').serializeArray();
        if (id) {
            formData.push({ name: '_method', value: 'PUT' });
        }

        var btn = $('#kt_modal_strategi_submit');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                
                if (typeof KTApp !== 'undefined' && KTApp.unblock) {
                    KTApp.unblock();
                }

                var drawerElement = document.querySelector("#kt_drawer_strategi");
                var drawerStrategi = KTDrawer.getInstance(drawerElement);
                if (drawerStrategi) {
                    drawerStrategi.hide();
                }

                if (table) {
                    table.ajax.reload(function() {
                        Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                    }, false);
                } else {
                    Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                }
            },
            error: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                
                if (typeof KTApp !== 'undefined' && KTApp.unblock) {
                    KTApp.unblock();
                }

                var errorList = '';
                var errors = data.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) { errorList += value + '<br>'; });
                } else {
                    errorList = data.responseJSON.message || 'Terjadi kesalahan sistem.';
                }
                Swal.fire({ html: errorList, icon: "error", confirmButtonText: "Tutup" });
            }
        });
    }

    function deleteStrategi(id) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data strategi ini akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-light" }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('frontend.renstra.strategi.index') }}/" + id,
                    type: "POST",
                    data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                    success: function(data) {
                        if (table) {
                            table.ajax.reload(function() {
                                Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                            }, false);
                        } else {
                            Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
