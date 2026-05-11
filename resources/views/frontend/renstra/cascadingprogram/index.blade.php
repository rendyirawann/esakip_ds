@extends('frontend.layout.app')

@section('title', 'Cascading Program')

@push('stylesheets')
<style>
    /* Custom Styling for RowGroup headers */
    .group-misi { background-color: #009ef7 !important; color: white !important; font-weight: bold !important; }
    .group-tujuan { background-color: #f1416c !important; color: white !important; font-weight: bold !important; }
    .group-sasaran { background-color: #50cd89 !important; color: white !important; font-weight: bold !important; }
    .group-program { background-color: #1e1e2d !important; color: white !important; font-weight: bold !important; }
    
    .dtrg-group td { padding: 10px 15px !important; border: none !important; }
    .dtrg-level-0 td { font-size: 1rem !important; }
    .dtrg-level-1 td { font-size: 0.95rem !important; padding-left: 25px !important; }
    .dtrg-level-2 td { font-size: 0.9rem !important; padding-left: 45px !important; }
    .dtrg-level-3 td { font-size: 0.85rem !important; padding-left: 65px !important; }
</style>
@endpush

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Cascading Program</h1>
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
                    <li class="breadcrumb-item text-dark">Cascading Program</li>
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
                                    <option value="{{ $skpd->refskpd_id }}" {{ (!$isSuperadmin && isset($current_skpd) && $current_skpd->refskpd_id == $skpd->refskpd_id) ? 'selected' : '' }}>
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
                    <img src="{{ asset('assets-front/media/illustrations/sketchy-1/19.png') }}" class="mw-350px mb-10" alt="Pilih Data" />
                    <div class="fs-1 fw-bolder text-dark mb-4">Pilih SKPD untuk melihat cascading</div>
                    <div class="fs-6 text-gray-500 text-center fw-semibold">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</div>
                </div>
            </div>

            <!-- Data Table Card -->
            <div id="table_card" class="card shadow-sm d-none">
                <div class="card-header border-0 pt-6">
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success" id="btn_tambah_cascading">
                            <i class="ki-outline ki-plus-square fs-2 me-1"></i> Data Cascading Program
                        </button>
                    </div>
                </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-7 gy-3" id="kt_cascading_table">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                    <th class="min-w-100px">Action</th>
                                    <th class="min-w-100px">Sasaran/Indikator</th>
                                    <th class="min-w-300px">Uraian</th>
                                    <th class="min-w-50px">Satuan</th>
                                    <th class="min-w-80px">Target</th>
                                    <th class="min-w-120px">Anggaran</th>
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

<!-- Drawer for Cascading -->
    <!-- Drawer for Add/Edit Program -->
    <div id="kt_drawer_program" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="program" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_program_toggle" data-kt-drawer-close="#kt_drawer_program_close">
        <div class="card shadow-none rounded-0 w-100">
            <div class="card-header" id="kt_drawer_program_header">
                <h3 class="card-title fw-bolder text-gray-700" id="drawer_title">Tambah Cascading Program</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_program_close">
                        <span class="svg-icon svg-icon-2">
                            <i class="fas fa-times"></i>
                        </span>
                    </button>
                </div>
            </div>
            <div class="card-body position-relative" id="kt_drawer_program_body">
                <form id="form_program">
                    @csrf
                    <input type="hidden" name="_method" id="form_method" value="POST">
                    <input type="hidden" name="refcascadingprogram_id" id="refcascadingprogram_id">
                    <input type="hidden" name="refskpd_id" id="form_skpd_id">
                    <input type="hidden" name="refsasaran_id" id="form_refsasaran_id">
                    <input type="hidden" name="reftujuan_id" id="form_reftujuan_id">
                    <input type="hidden" name="refmisi_id" id="form_refmisi_id">

                    <div class="mb-5">
                        <label class="form-label required">Periode (Tahun)</label>
                        <select name="refperiode_id" id="form_periode_id" class="form-select" data-control="select2" data-placeholder="Pilih Periode">
                            <option></option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="form_cascading_section" style="display:none;">
                        <div class="mb-5">
                            <label class="form-label required">Sasaran Renstra</label>
                            <select name="refsasaranrenstra_id" id="form_sasaranrenstra_id" class="form-select" data-control="select2" data-placeholder="Pilih Sasaran Renstra">
                                <option></option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Indikator Sasaran Renstra</label>
                            <select name="refindikatorsasaranrenstra_id" id="form_indikatorsasaranrenstra_id" class="form-select" data-control="select2" data-placeholder="Pilih Indikator">
                                <option></option>
                            </select>
                        </div>

                        <div class="separator separator-dashed my-5"></div>

                        <div class="mb-5">
                            <label class="form-label required">Bidang</label>
                            <select name="refbidang_id" id="form_bidang_id" class="form-select" data-control="select2" data-placeholder="Pilih Bidang">
                                <option></option>
                                @foreach($bidangs as $b)
                                    <option value="{{ $b->refbidang_id }}">{{ $b->kode_bidang }} - {{ $b->nama_bidang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Program</label>
                            <select name="refprogram_id" id="form_program_id" class="form-select" data-control="select2" data-placeholder="Pilih Program">
                                <option></option>
                            </select>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Uraian Sasaran Program</label>
                            <textarea name="uraian_sasaranprogram" id="form_uraian_sasaranprogram" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-5">
                            <label class="form-label required">Uraian Indikator Program</label>
                            <textarea name="uraian_indikatorprogram" id="form_uraian_indikatorprogram" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-5">
                                <label class="form-label required">Target</label>
                                <input type="text" name="program_target" id="form_program_target" class="form-control">
                            </div>
                            <div class="col-md-6 mb-5">
                                <label class="form-label required">Satuan</label>
                                <input type="text" name="program_satuan" id="form_program_satuan" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary w-100" id="btn_save_program">
                    <span class="indicator-label">Simpan Data</span>
                    <span class="indicator-progress">Mohon tunggu... 
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Drawer for Add Penjabat -->
    <div id="kt_drawer_penjabat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="penjabat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '450px'}" data-kt-drawer-direction="end" data-kt-drawer-close="#kt_drawer_penjabat_close">
        <div class="card shadow-none rounded-0 w-100">
            <div class="card-header">
                <h3 class="card-title fw-bolder text-gray-700">Tautkan Penjabat SKPD</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_penjabat_close">
                        <span class="svg-icon svg-icon-2"><i class="fas fa-times"></i></span>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="form_penjabat">
                    @csrf
                    <input type="hidden" name="refcascadingprogram_id" id="pj_refcascadingprogram_id">
                    <input type="hidden" name="refskpd_id" id="pj_refskpd_id">
                    <input type="hidden" name="refperiode_id" id="pj_refperiode_id">
                    <input type="hidden" name="refsasaranrenstra_id" id="pj_refsasaranrenstra_id">
                    <input type="hidden" name="refindikatorsasaranrenstra_id" id="pj_refindikatorsasaranrenstra_id">
                    <input type="hidden" name="refbidang_id" id="pj_refbidang_id">
                    <input type="hidden" name="refprogram_id" id="pj_refprogram_id">

                    <div class="mb-7 bg-light-primary p-4 rounded border border-primary border-dashed">
                        <div class="fw-bold text-gray-800 fs-7" id="pj_program_info">Program: -</div>
                        <div class="text-gray-600 fs-9 mt-1" id="pj_indikator_info">Indikator: -</div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Pilih Penjabat SKPD</label>
                        <select name="refpenjabatskpd_id" id="pj_refpenjabatskpd_id" class="form-select" data-control="select2" data-placeholder="Pilih Penjabat">
                            <option></option>
                        </select>
                    </div>

                    <div id="pj_detail_section" style="display:none;" class="bg-light p-4 rounded">
                        <div class="mb-2"><strong>NIP:</strong> <span id="pj_nip">-</span></div>
                        <div class="mb-2"><strong>Jabatan:</strong> <span id="pj_jabatan">-</span></div>
                        <div><strong>Pangkat:</strong> <span id="pj_pangkat">-</span></div>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-success w-100" id="btn_save_penjabat">
                    <span class="indicator-label">Tautkan Penjabat</span>
                    <span class="indicator-progress">Mohon tunggu... 
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let table;
    let drawerProgram;
    let drawerPenjabat;

    function formatNumber(num) {
        return 'Rp. ' + parseFloat(num || 0).toLocaleString('id-ID');
    }

    function initDataTable() {
        table = $('#kt_cascading_table').DataTable({
            searchDelay: 500,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.cascadingprogram.data') }}",
                type: "GET",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {
                    data: null, 
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex flex-nowrap">
                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit" data-id="${row.refcascadingprogram_id}" title="Edit">
                                    <i class="fas fa-edit fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btn-delete" data-id="${row.refcascadingprogram_id}" title="Hapus">
                                    <i class="fas fa-trash fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm btn-add-pj" 
                                    data-id="${row.refcascadingprogram_id}" 
                                    data-skpd="${row.refskpd_id}"
                                    data-periode="${row.refperiode_id}"
                                    data-sasaranrenstra="${row.refsasaranrenstra_id}"
                                    data-indikatorrenstra="${row.refindikatorsasaranrenstra_id}"
                                    data-bidang="${row.refbidang_id}"
                                    data-program="${row.refprogram_id}"
                                    data-program-name="${row.program_nama}"
                                    data-indikator-name="${row.uraian_indikatorprogram}"
                                    title="Tautkan Penjabat">
                                    <i class="fas fa-check fs-4"></i>
                                </button>
                            </div>
                        `;
                    }
                },
                { 
                    data: null, 
                    render: () => '<span class="badge badge-light-info">Sasaran/Indikator</span>',
                    orderable: false, searchable: false
                },
                { 
                    data: null,
                    render: function(data, type, row) {
                        let html = `<div class="text-gray-800 fw-bold fs-7">${row.uraian_sasaranprogram || '-'} - ${row.uraian_indikatorprogram || '-'}</div>`;
                        
                        let penjabatHtml = '<span class="text-muted fs-9">Belum ada penjabat yang ditautkan.</span>';
                        if (row.penjabat_list && Array.isArray(row.penjabat_list) && row.penjabat_list.length > 0) {
                            penjabatHtml = row.penjabat_list.map(p => `
                                <div class="mb-3 border-bottom pb-2 border-gray-300">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-primary fs-7">${p.nama}</div>
                                            <div class="text-muted fs-9">NIP: ${p.nip}</div>
                                            <div class="text-gray-600 fs-9 italic">${p.jabatan}</div>
                                        </div>
                                        <button class="btn btn-icon btn-light-danger btn-xs btn-delete-pj" data-id="${p.id}" title="Hapus Penjabat">
                                            <i class="fas fa-times fs-6"></i>
                                        </button>
                                    </div>
                                </div>
                            `).join('');
                        }

                        return `
                            ${html}
                            <div class="accordion mt-2" id="acc_pj_${row.refcascadingprogram_id}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2 px-0 fs-8 fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_pj_${row.refcascadingprogram_id}">
                                            Lihat Penjabat SKPD (${row.penjabat_list ? row.penjabat_list.length : 0})
                                        </button>
                                    </h2>
                                    <div id="collapse_pj_${row.refcascadingprogram_id}" class="accordion-collapse collapse" data-bs-parent="#acc_pj_${row.refcascadingprogram_id}">
                                        <div class="accordion-body p-4 bg-light-secondary rounded mt-2">
                                            ${penjabatHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'program_satuan' },
                { data: 'program_target' },
                { 
                    data: 'anggaran', 
                    render: function(data) { return formatNumber(data); },
                    orderable: false, searchable: false
                },
                { data: 'misi_text', visible: false, orderable: false },
                { data: 'tujuan_text', visible: false, orderable: false },
                { data: 'sasaran_text', visible: false, orderable: false },
                { data: 'program_kode', visible: false, orderable: false },
                { data: 'program_nama', visible: false, orderable: false },
            ],
            order: [],
            rowGroup: {
                dataSrc: ['misi_text', 'tujuan_text', 'sasaran_text', 'program_kode'],
                startRender: function (rows, group, level) {
                    if (level === 0) return $('<tr class="group-misi"><td colspan="6" class="p-3 fs-6 fw-bolder bg-primary text-white">' + group + '</td></tr>');
                    if (level === 1) return $('<tr class="group-tujuan"><td colspan="6" class="p-3 fs-7 fw-bold bg-danger text-white">(Tujuan) ' + group + '</td></tr>');
                    if (level === 2) return $('<tr class="group-sasaran"><td colspan="6" class="p-3 fs-7 fw-bold bg-success text-white">(Sasaran) ' + group + '</td></tr>');
                    if (level === 3) {
                        var progName = rows.data()[0].program_nama;
                        return $('<tr class="group-program"><td colspan="6" class="p-3 fs-8 fw-bold bg-dark text-white">' + group + ' - ' + progName + '</td></tr>');
                    }
                }
            },
            language: { emptyTable: "Data tidak ditemukan atau pilih periode terlebih dahulu." }
        });
    }

    $(document).ready(function() {
        // CSRF Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        initDataTable();
        
        function getDrawer(id) {
            let el = document.querySelector(id);
            if (!el) return null;
            let instance = KTDrawer.getInstance(el);
            if (!instance) {
                instance = new KTDrawer(el);
            }
            return instance;
        }

        $('#btn_tampilkan').on('click', function() {
            const skpd = $('#filter_skpd').val();
            const periode = $('#filter_periode').val();

            if (!skpd || !periode) {
                Swal.fire({ text: "Silakan pilih SKPD dan Periode terlebih dahulu.", icon: "warning" });
                return;
            }

            $('#empty_state').addClass('d-none');
            $('#table_card').removeClass('d-none');
            table.ajax.reload();
        });

        // Open Create
        $('#btn_tambah_cascading').on('click', function() {
            $('#drawer_title').text('Tambah Cascading Program');
            $('#form_program')[0].reset();
            $('#form_method').val('POST');
            $('#form_skpd_id').val($('#filter_skpd').val());
            
            // Enable fields for New Entry
            $('#form_periode_id, #form_sasaranrenstra_id, #form_indikatorsasaranrenstra_id, #form_bidang_id, #form_program_id').prop('disabled', false);
            
            // Reset dropdown ke placeholder kosong
            $('#form_periode_id').val('').trigger('change');
            
            $('#form_cascading_section').hide();
            getDrawer("#kt_drawer_program").show();
        });

        // Cascading Periode -> Sasaran
        $('#form_periode_id').on('change', function() {
            const periodeId = $(this).val();
            const skpdId = $('#form_skpd_id').val();
            if (periodeId && skpdId) {
                $('#form_cascading_section').show();
                $.get("{{ route('frontend.renstra.cascadingprogram.getSasaranRenstra') }}", { skpd_id: skpdId, periode_id: periodeId }, function(data) {
                    let options = '<option></option>';
                    data.forEach(item => options += `<option value="${item.refsasaranrenstra_id}">${item.uraian_sasaranrenstra}</option>`);
                    $('#form_sasaranrenstra_id').html(options).trigger('change');
                });
            } else {
                $('#form_cascading_section').hide();
            }
        });

        // Cascading Sasaran -> Indikator & Associated
        $('#form_sasaranrenstra_id').on('change', function() {
            const sasaranId = $(this).val();
            if (sasaranId) {
                $.get("{{ route('frontend.renstra.cascadingprogram.getIndikatorSasaranRenstra') }}", { sasaran_renstra_id: sasaranId }, function(data) {
                    let options = '<option></option>';
                    data.forEach(item => options += `<option value="${item.refindikatorsasaranrenstra_id}">${item.uraian_indikatorsasaranrenstra}</option>`);
                    $('#form_indikatorsasaranrenstra_id').html(options).trigger('change');
                });
                $.get("{{ route('frontend.renstra.cascadingprogram.getAssociatedValues') }}", { sasaran_renstra_id: sasaranId }, function(data) {
                    $('#form_refsasaran_id').val(data.refsasaran_id);
                    $('#form_reftujuan_id').val(data.reftujuan_id);
                    $('#form_refmisi_id').val(data.refmisi_id);
                });
            }
        });

        // Bidang -> Program
        $('#form_bidang_id').on('change', function() {
            const bidangId = $(this).val();
            if (bidangId) {
                $.get("{{ route('frontend.renstra.cascadingprogram.getPrograms') }}", { bidang_id: bidangId }, function(data) {
                    let options = '<option></option>';
                    data.forEach(item => options += `<option value="${item.refprogram_id}">${item.kode_program} - ${item.nama_program}</option>`);
                    $('#form_program_id').html(options).trigger('change');
                });
            }
        });

        // Save Program
        $('#btn_save_program').on('click', function() {
            const btn = $(this);
            const method = $('#form_method').val();
            const id = $('#refcascadingprogram_id').val();
            const url = method === 'POST' ? "{{ route('frontend.renstra.cascadingprogram.store') }}" : `/frontend/renstra/cascadingprogram/${id}`;
            
            // Add loading indicator
            btn.attr("data-kt-indicator", "on");
            btn.prop("disabled", true);

            // Temporarily enable disabled fields so they are included in serialization
            const disabledFields = $('#form_program').find(':disabled').prop('disabled', false);
            let formData = $('#form_program').serializeArray();
            disabledFields.prop('disabled', true); // Lock them back

            // Remove empty primary key if creating
            if (method === 'POST') {
                formData = formData.filter(item => item.name !== 'refcascadingprogram_id');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: $.param(formData) + (method === 'PUT' ? '&_method=PUT' : ''),
                success: function(res) {
                    btn.removeAttr("data-kt-indicator");
                    btn.prop("disabled", false);
                    Swal.fire('Berhasil!', res.success, 'success');
                    getDrawer("#kt_drawer_program").hide();
                    table.ajax.reload();
                },
                error: function(err) {
                    btn.removeAttr("data-kt-indicator");
                    btn.prop("disabled", false);
                    Swal.fire('Error!', err.responseJSON.error || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        // Edit
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const baseUrl = "{{ url('frontend/renstra/cascadingprogram') }}";
            $.get(`${baseUrl}/${id}`, function(data) {
                $('#drawer_title').text('Edit Cascading Program');
                $('#form_method').val('PUT');
                $('#refcascadingprogram_id').val(data.refcascadingprogram_id);
                $('#form_skpd_id').val(data.refskpd_id);
                
                // Disable fields to maintain integrity
                $('#form_periode_id, #form_sasaranrenstra_id, #form_indikatorsasaranrenstra_id, #form_bidang_id, #form_program_id').prop('disabled', true);
                
                $('#form_periode_id').val(data.refperiode_id).trigger('change');
                
                setTimeout(() => {
                    $('#form_sasaranrenstra_id').val(data.refsasaranrenstra_id).trigger('change');
                    setTimeout(() => {
                        $('#form_indikatorsasaranrenstra_id').val(data.refindikatorsasaranrenstra_id).trigger('change');
                    }, 800);
                }, 800);

                $('#form_bidang_id').val(data.refbidang_id).trigger('change');
                setTimeout(() => { $('#form_program_id').val(data.refprogram_id).trigger('change'); }, 800);

                $('#form_uraian_sasaranprogram').val(data.uraian_sasaranprogram);
                $('#form_uraian_indikatorprogram').val(data.uraian_indikatorprogram);
                $('#form_program_target').val(data.program_target);
                $('#form_program_satuan').val(data.program_satuan);
                getDrawer("#kt_drawer_program").show();
            });
        });

        // Delete
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({ title: 'Hapus data ini?', text: "Data triwulan dan indikator terkait akan ikut terhapus!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!' }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ url: `/frontend/renstra/cascadingprogram/${id}`, type: 'DELETE', success: function(res) {
                        Swal.fire('Terhapus!', res.success, 'success');
                        table.ajax.reload();
                    }});
                }
            });
        });

        // --- Penjabat ---
        $(document).on('click', '.btn-add-pj', function() {
            const btn = $(this);
            $('#pj_refcascadingprogram_id').val(btn.data('id'));
            $('#pj_refskpd_id').val(btn.data('skpd'));
            $('#pj_refperiode_id').val(btn.data('periode'));
            $('#pj_refsasaranrenstra_id').val(btn.data('sasaranrenstra'));
            $('#pj_refindikatorsasaranrenstra_id').val(btn.data('indikatorrenstra'));
            $('#pj_refbidang_id').val(btn.data('bidang'));
            $('#pj_refprogram_id').val(btn.data('program'));
            $('#pj_program_info').text('Program: ' + btn.data('program-name'));
            $('#pj_indikator_info').text('Indikator: ' + btn.data('indikator-name'));
            $('#pj_detail_section').hide();

            $.get("{{ route('frontend.renstra.penjabat-cascading.fetch') }}", { 
                refperiode_id: btn.data('periode'), 
                refskpd_id: btn.data('skpd'),
                refcascadingprogram_id: btn.data('id') 
            }, function(data) {
                let options = '<option></option>';
                data.forEach(item => options += `<option value="${item.refpenjabatskpd_id}" data-nip="${item.nip_penjabat}" data-jabatan="${item.jabatan_eselon}" data-pangkat="${item.pangkat_eselon}">${item.nama_penjabat}</option>`);
                $('#pj_refpenjabatskpd_id').html(options).trigger('change');
                getDrawer("#kt_drawer_penjabat").show();
            });
        });

        $('#pj_refpenjabatskpd_id').on('change', function() {
            const opt = $(this).find(':selected');
            if (opt.val()) {
                $('#pj_nip').text(opt.data('nip')); $('#pj_jabatan').text(opt.data('jabatan')); $('#pj_pangkat').text(opt.data('pangkat'));
                $('#pj_detail_section').fadeIn();
            } else { $('#pj_detail_section').hide(); }
        });

        $('#btn_save_penjabat').on('click', function() {
            const btn = $(this);
            btn.attr("data-kt-indicator", "on");
            btn.prop("disabled", true);

            $.ajax({ 
                url: "{{ route('frontend.renstra.penjabat-cascading.store') }}", 
                type: 'POST', 
                data: $('#form_penjabat').serialize(), 
                success: function(res) {
                    btn.removeAttr("data-kt-indicator");
                    btn.prop("disabled", false);
                    Swal.fire('Berhasil!', res.success, 'success'); 
                    getDrawer("#kt_drawer_penjabat").hide(); 
                    table.ajax.reload();
                },
                error: function(err) {
                    btn.removeAttr("data-kt-indicator");
                    btn.prop("disabled", false);
                    Swal.fire('Error!', err.responseJSON.error || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        $(document).on('click', '.btn-delete-pj', function() {
            const id = $(this).data('id');
            const baseUrl = "{{ url('frontend/renstra/penjabat-cascading') }}";
            Swal.fire({ title: 'Hapus tautan penjabat?', icon: 'warning', showCancelButton: true }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ url: `${baseUrl}/${id}`, type: 'DELETE', success: function(res) {
                        Swal.fire('Terhapus!', res.success, 'success'); table.ajax.reload();
                    }});
                }
            });
        });
    });
</script>
@endpush
