@extends('frontend.layout.app')

@section('title', 'Formulasi Renstra')

@push('stylesheets')
<style>
    .dtrg-group {
        background-color: #009ef7 !important;
        color: white !important;
        font-weight: bold !important;
    }
    .dtrg-group td {
        padding: 12px 15px !important;
        font-size: 1.1rem !important;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Formulasi Sasaran Renstra</h1>
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
                    <li class="breadcrumb-item text-dark">Formulasi</li>
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
                    <img src="{{ asset('assets-front/media/illustrations/sketchy-1/2.png') }}" class="mw-350px mb-10" alt="Pilih Data" />
                    <div class="fs-1 fw-bolder text-dark mb-4">Pilih SKPD untuk melihat formulasi</div>
                    <div class="fs-6 text-gray-500 text-center fw-semibold">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div id="table_card" class="card shadow-sm d-none">
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_formulasi_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">No</th>
                                    <th class="min-w-400px">Indikator & Formulasi</th>
                                    <th class="min-w-100px">Satuan</th>
                                    <th class="min-w-150px">Status Aktif</th>
                                    <th class="text-end min-w-100px">Action</th>
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

<!-- Drawer for Formulasi -->
<div id="kt_drawer_formulasi" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="formulasi" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '600px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_formulasi_toggle" data-kt-drawer-close="#kt_drawer_formulasi_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="kt_drawer_formulasi_header">
            <h3 class="card-title fw-bold text-dark">Kelola Formulasi Indikator</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_formulasi_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_drawer_formulasi_body">
            <form id="kt_modal_formulasi_form" class="form ajax-form">
                @csrf
                <input type="hidden" name="id" id="ind_id">
                
                <div class="mb-8 p-4 bg-light-primary rounded border border-primary border-dashed">
                    <label class="fs-6 fw-bold mb-1 text-primary">Indikator:</label>
                    <div id="display_uraian" class="fs-6 text-gray-800 fw-semibold italic">...</div>
                </div>

                <div class="row mb-7">
                    <div class="col-md-12 fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                        <input type="text" name="indikatorsasaranrenstra_satuan" id="form_satuan" class="form-control form-control-solid" placeholder="Contoh: Persen, Nilai, Poin" />
                    </div>
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2">Alasan</label>
                    <textarea name="alasan_sasaranrenstra" id="form_alasan" class="form-control form-control-solid" rows="3" placeholder="Masukkan alasan..."></textarea>
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2">Cara Pengukuran (Formulasi)</label>
                    <textarea name="formulasi_sasaranrenstra" id="form_pengukuran" class="form-control form-control-solid" rows="3" placeholder="Masukkan cara pengukuran..."></textarea>
                </div>

                <div class="fv-row mb-7">
                    <label class="fs-6 fw-semibold mb-2">Kriteria</label>
                    <textarea name="kriteria_sasaranrenstra" id="form_kriteria" class="form-control form-control-solid" rows="3" placeholder="Masukkan kriteria..."></textarea>
                </div>

                <div class="row g-9 mb-8">
                    <div class="col-md-6 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Status IKU</label>
                        <select name="iku_isaktif" id="form_iku" class="form-select form-select-solid">
                            <option value="T">AKTIF</option>
                            <option value="F">NON-AKTIF</option>
                        </select>
                    </div>
                    <div class="col-md-6 fv-row">
                        <label class="required fs-6 fw-semibold mb-2">Status PK</label>
                        <select name="pk_isaktif" id="form_pk" class="form-select form-select-solid">
                            <option value="T">AKTIF</option>
                            <option value="F">NON-AKTIF</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end pt-10">
                    <button type="button" class="btn btn-light me-3" id="kt_drawer_formulasi_close_v2">Batal</button>
                    <button type="submit" id="kt_modal_formulasi_submit" class="btn btn-primary">
                        <span class="indicator-label">Simpan Perubahan</span>
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
        table = $('#kt_formulasi_table').DataTable({
            searchDelay: 500,
            processing: false,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.formulasi.index') }}",
                type: "GET",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'indikator_detail', name: 'uraian_indikatorsasaranrenstra'},
                {data: 'indikatorsasaranrenstra_satuan', name: 'indikatorsasaranrenstra_satuan'},
                {data: 'status_aktif', name: 'iku_isaktif', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-end'}
            ],
            order: [[1, 'asc']],
            rowGroup: {
                dataSrc: 'uraian_sasaranrenstra',
                startRender: function (rows, group) {
                    return 'SASARAN: ' + group;
                }
            },
            language: {
                emptyTable: "Data formulasi tidak ditemukan."
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

        $('#kt_modal_formulasi_form').on('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        $('#kt_modal_formulasi_submit').on('click', function(e) {
            e.preventDefault();
            submitForm();
        });

        $('#kt_drawer_formulasi_close_v2, #kt_drawer_formulasi_close').on('click', function() {
            var drawerElement = document.querySelector("#kt_drawer_formulasi");
            var drawerFormulasi = KTDrawer.getInstance(drawerElement);
            if (drawerFormulasi) {
                drawerFormulasi.hide();
            }
        });
    });

    function editFormulasi(id) {
        $('#kt_modal_formulasi_form')[0].reset();
        
        $.get("{{ route('frontend.renstra.formulasi.index') }}/" + id + "/edit", function(data) {
            $('#ind_id').val(data.refindikatorsasaranrenstra_id);
            $('#display_uraian').text(data.uraian_indikatorsasaranrenstra);
            $('#form_satuan').val(data.indikatorsasaranrenstra_satuan);
            $('#form_alasan').val(data.alasan_sasaranrenstra);
            $('#form_pengukuran').val(data.formulasi_sasaranrenstra);
            $('#form_kriteria').val(data.kriteria_sasaranrenstra);
            $('#form_iku').val(data.iku_isaktif || 'F');
            $('#form_pk').val(data.pk_isaktif || 'F');
            
            var drawerElement = document.querySelector("#kt_drawer_formulasi");
            var drawerFormulasi = KTDrawer.getInstance(drawerElement);
            if (!drawerFormulasi) {
                drawerFormulasi = new KTDrawer(drawerElement);
            }
            drawerFormulasi.show();
        });
    }

    function submitForm() {
        var id = $('#ind_id').val();
        var url = "{{ route('frontend.renstra.formulasi.index') }}/" + id;
        
        var formData = $('#kt_modal_formulasi_form').serializeArray();
        formData.push({ name: '_method', value: 'PUT' });

        var btn = $('#kt_modal_formulasi_submit');
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

                var drawerElement = document.querySelector("#kt_drawer_formulasi");
                var drawerFormulasi = KTDrawer.getInstance(drawerElement);
                if (drawerFormulasi) {
                    drawerFormulasi.hide();
                }

                if (table) {
                    table.ajax.reload(function() {
                        Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                    }, false);
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
</script>
@endpush
