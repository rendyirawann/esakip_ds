@extends('frontend.layout.app')

@section('title', 'Cascading Sub Kegiatan')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Cascading Sub Kegiatan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">Renstra</li>
                    <li class="breadcrumb-item text-muted">Cascading Sub Kegiatan</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!-- Filter Card -->
            <div class="card mb-5">
                <div class="card-body">
                    <div class="row g-5">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Pilih SKPD</label>
                            <select id="filter_skpd" class="form-select" data-control="select2" data-placeholder="Pilih SKPD" {{ $isSuperadmin ? '' : 'disabled' }}>
                                <option></option>
                                @foreach($skpds as $s)
                                    <option value="{{ $s->refskpd_id }}" {{ (!$isSuperadmin && $s->refskpd_id == $current_skpd) ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pilih Periode (Tahun)</label>
                            <select id="filter_periode" class="form-select" data-control="select2">
                                <option value="">Pilih Periode</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
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
            <div id="empty_state" class="card">
                <div class="card-body p-20 text-center">
                    <img src="{{ asset('assets/media/illustrations/sigma-1/5.png') }}" class="h-150px mb-5" alt="">
                    <h3 class="text-gray-800 fw-bold">Silakan Pilih SKPD dan Periode</h3>
                    <p class="text-gray-400">Pilih filter di atas untuk menampilkan data Cascading Sub Kegiatan.</p>
                </div>
            </div>

            <!-- Table Card -->
            <div id="table_card" class="card d-none">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"></div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success" id="btn_tambah_cascading">
                            <i class="ki-outline ki-plus-square fs-2 me-1"></i> Data Cascading Sub Kegiatan
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

<!-- Drawer Add/Edit -->
<div id="kt_drawer_subkegiatan" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="subkegiatan" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_subkegiatan_toggle" data-kt-drawer-close="#kt_drawer_subkegiatan_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="kt_drawer_subkegiatan_header">
            <h3 class="card-title fw-bolder text-gray-700" id="drawer_title">Tambah Cascading Sub Kegiatan</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_subkegiatan_close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_drawer_subkegiatan_body">
            <form id="form_subkegiatan">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="POST">
                <input type="hidden" name="refcascadingsubkegiatan_id" id="refcascadingsubkegiatan_id">
                <input type="hidden" name="refskpd_id" id="form_skpd_id">
                <input type="hidden" name="refcascadingprogram_id" id="form_refcascadingprogram_id">
                <input type="hidden" name="refsasaranrenstra_id" id="form_refsasaranrenstra_id">
                <input type="hidden" name="refindikatorsasaranrenstra_id" id="form_refindikatorsasaranrenstra_id">
                <input type="hidden" name="refprogram_id" id="form_refprogram_id">
                <input type="hidden" name="refkegiatan_id" id="form_refkegiatan_id">

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
                        <label class="form-label required">Indikator Kegiatan (Cascading)</label>
                        <select name="refcascadingkegiatan_id" id="form_cascading_kegiatan_id" class="form-select" data-control="select2" data-placeholder="Pilih Indikator Kegiatan">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-5 border-start border-primary border-4 ps-4 bg-light-primary py-3 rounded">
                        <div class="fs-8 fw-bold text-gray-600">Info Cascading Program:</div>
                        <div id="info_program" class="fs-7 text-dark fw-bold mb-2"></div>
                        <div class="fs-8 fw-bold text-gray-600 border-top pt-2">Info Cascading Kegiatan:</div>
                        <div id="info_sasaran_keg" class="fs-7 text-dark fw-bold"></div>
                        <div id="info_indikator_keg" class="fs-7 text-dark italic"></div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Sub Kegiatan Master</label>
                        <select name="refsubkegiatan_id" id="form_refsubkegiatan_id" class="form-select" data-control="select2" data-placeholder="Pilih Sub Kegiatan">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Uraian Sasaran Sub Kegiatan</label>
                        <textarea name="uraian_sasaransubkegiatan" id="form_uraian_sasaransubkegiatan" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Uraian Indikator Sub Kegiatan</label>
                        <textarea name="uraian_indikatorsubkegiatan" id="form_uraian_indikatorsubkegiatan" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label class="form-label required">Target</label>
                                <input type="text" name="subkegiatan_target" id="form_subkegiatan_target" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label class="form-label required">Satuan</label>
                                <input type="text" name="subkegiatan_satuan" id="form_subkegiatan_satuan" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Anggaran</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="text" name="subkegiatan_anggaran" id="form_subkegiatan_anggaran" class="form-control mask-money">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary w-100" id="btn_save_subkegiatan">
                <span class="indicator-label">Simpan Data</span>
                <span class="indicator-progress">Mohon tunggu... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Drawer Penjabat -->
<div id="kt_drawer_penjabat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="penjabat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '450px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_penjabat_toggle" data-kt-drawer-close="#kt_drawer_penjabat_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header">
            <h3 class="card-title fw-bolder text-gray-700">Tautkan Penjabat SKPD</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_penjabat_close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative">
            <form id="form_penjabat">
                @csrf
                <input type="hidden" name="refskpd_id" id="pj_refskpd_id">
                <input type="hidden" name="refperiode_id" id="pj_refperiode_id">
                <input type="hidden" name="refcascadingsubkegiatan_id" id="pj_refcascadingsubkegiatan_id">
                
                <div class="mb-5 border-start border-success border-4 ps-4 bg-light-success py-3 rounded">
                    <div id="pj_program_info" class="fs-8 fw-bold text-gray-800 mb-1"></div>
                    <div id="pj_indikator_info" class="fs-8 text-gray-600 italic"></div>
                </div>

                <div class="mb-7">
                    <label class="form-label required fw-bold">Pilih Penjabat SKPD</label>
                    <select name="refpenjabatskpd_id" id="pj_refpenjabatskpd_id" class="form-select" data-control="select2" data-placeholder="Cari Penjabat...">
                        <option></option>
                    </select>
                </div>

                <div id="pj_detail_section" style="display:none;">
                    <div class="separator separator-dashed my-5"></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px me-3">
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-outline ki-user fs-2 text-primary"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-400 fs-8 fw-bold">NIP</span>
                            <span id="pj_nip" class="text-gray-800 fs-7 fw-bolder"></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="symbol symbol-40px me-3">
                            <span class="symbol-label bg-light-info">
                                <i class="ki-outline ki-briefcase fs-2 text-info"></i>
                            </span>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="text-gray-400 fs-8 fw-bold">Jabatan</span>
                            <span id="pj_jabatan" class="text-gray-800 fs-7 fw-bolder"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-success w-100" id="btn_save_penjabat">
                <span class="indicator-label">Tautkan Sekarang</span>
                <span class="indicator-progress">Mohon tunggu... 
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
<script>
    let table;
    let anAnggaran;

    function formatNumber(num) {
        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function initDataTable() {
        table = $('#kt_cascading_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.cascadingsubkegiatan.data') }}",
                data: function(d) {
                    d.skpd_id = $('#filter_skpd').val();
                    d.periode_id = $('#filter_periode').val();
                }
            },
            columns: [
                {
                    data: null, 
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex flex-nowrap">
                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit" data-id="${row.refcascadingsubkegiatan_id}" title="Edit">
                                    <i class="fas fa-edit fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btn-delete" data-id="${row.refcascadingsubkegiatan_id}" title="Hapus">
                                    <i class="fas fa-trash fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm btn-add-pj" 
                                    data-id="${row.refcascadingsubkegiatan_id}" 
                                    data-skpd="${row.refskpd_id}"
                                    data-periode="${row.refperiode_id}"
                                    data-name="${row.subkegiatan ? row.subkegiatan.nama_subkegiatan : '-'}"
                                    data-indikator="${row.uraian_indikatorsubkegiatan || '-'}"
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
                        let html = `<div class="text-gray-800 fw-bold fs-7 italic text-wrap">${row.uraian_sasaransubkegiatan || '-'} - ${row.uraian_indikatorsubkegiatan || '-'}</div>`;
                        
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
                            <div class="accordion mt-2" id="acc_pj_${row.refcascadingsubkegiatan_id}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2 px-0 fs-8 fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_pj_${row.refcascadingsubkegiatan_id}">
                                            Lihat Penjabat SKPD (${row.penjabat_list ? row.penjabat_list.length : 0})
                                        </button>
                                    </h2>
                                    <div id="collapse_pj_${row.refcascadingsubkegiatan_id}" class="accordion-collapse collapse" data-bs-parent="#acc_pj_${row.refcascadingsubkegiatan_id}">
                                        <div id="pj_list_container_${row.refcascadingsubkegiatan_id}" class="accordion-body p-4 bg-white rounded mt-2 border border-dashed border-gray-300">
                                            ${penjabatHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'subkegiatan_satuan' },
                { data: 'subkegiatan_target' },
                { 
                    data: 'subkegiatan_anggaran', 
                    render: function(data) { return formatNumber(data || 0); },
                },
                { data: 'misi_text', visible: false },
                { data: 'tujuan_text', visible: false },
                { data: 'sasaran_text', visible: false },
                { data: 'program_text', visible: false },
                { data: 'kegiatan_text', visible: false },
                { data: 'subkegiatan_nama', visible: false },
                { data: 'subkegiatan_kode', visible: false },
            ],
            order: [[6, 'asc'], [7, 'asc'], [8, 'asc'], [9, 'asc'], [10, 'asc'], [11, 'asc']],
            rowGroup: {
                dataSrc: ['misi_text', 'tujuan_text', 'sasaran_text', 'program_text', 'kegiatan_text', 'subkegiatan_nama'],
                startRender: function(rows, group, level) {
                    if (level === 0) return $('<tr class="group-misi"><td colspan="6" class="p-3 fs-6 fw-bolder bg-success text-white">' + group + '</td></tr>');
                    if (level === 1) return $('<tr class="group-tujuan"><td colspan="6" class="p-3 fs-7 fw-bold bg-danger text-white">(Tujuan) ' + group + '</td></tr>');
                    if (level === 2) return $('<tr class="group-sasaran"><td colspan="6" class="p-3 fs-7 fw-bold bg-primary text-white">(Sasaran) ' + group + '</td></tr>');
                    if (level === 3) return $('<tr class="group-program"><td colspan="6" class="p-3 fs-8 fw-bold bg-dark text-white">(Program) ' + group + '</td></tr>');
                    if (level === 4) return $('<tr class="group-kegiatan"><td colspan="6" class="p-3 fs-8 fw-bold bg-info text-white">(Kegiatan) ' + group + '</td></tr>');
                    if (level === 5) {
                        var kgtKode = rows.data()[0].subkegiatan_kode;
                        return $('<tr class="group-subkegiatan"><td colspan="6" class="p-2 ps-5 fs-8 fw-bold bg-secondary text-dark border-bottom border-dark">[' + kgtKode + '] - ' + group + '</td></tr>');
                    }
                }
            },
            drawCallback: function(settings) {
                KTMenu.createInstances();
            }
        });
    }

    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        anAnggaran = new AutoNumeric('#form_subkegiatan_anggaran', {
            digitGroupSeparator: '.', decimalCharacter: ',', decimalPlaces: 0,
            unformatOnSubmit: true
        });

        initDataTable();
        
        function getDrawer(id) {
            let el = document.querySelector(id);
            if (!el) return null;
            let instance = KTDrawer.getInstance(el);
            if (!instance) { instance = new KTDrawer(el); }
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

        $('#btn_tambah_cascading').on('click', function() {
            $('#drawer_title').text('Tambah Cascading Sub Kegiatan');
            $('#form_subkegiatan')[0].reset();
            $('#form_method').val('POST');
            $('#form_skpd_id').val($('#filter_skpd').val());
            anAnggaran.set(0);
            
            $('#form_periode_id, #form_cascading_kegiatan_id, #form_refsubkegiatan_id').prop('disabled', false);
            $('#form_periode_id').val('').trigger('change');
            $('#form_cascading_section').hide();
            getDrawer("#kt_drawer_subkegiatan").show();
        });

        $('#form_periode_id').on('change', function() {
            const periodeId = $(this).val();
            const skpdId = $('#form_skpd_id').val();
            if (periodeId && skpdId) {
                $('#form_cascading_section').show();
                $.get("{{ route('frontend.renstra.cascadingsubkegiatan.getKegiatanCascading') }}", { skpd_id: skpdId, periode_id: periodeId }, function(data) {
                    let options = '<option></option>';
                    data.forEach(item => {
                        const kgtName = item.kegiatan ? item.kegiatan.nama_kegiatan : '-';
                        options += `<option value="${item.refcascadingkegiatan_id}" data-sasaran="${item.uraian_sasarankegiatan}" data-indikator="${item.uraian_indikatorkegiatan}">${kgtName} - ${item.uraian_indikatorkegiatan}</option>`;
                    });
                    $('#form_cascading_kegiatan_id').html(options).trigger('change');
                });
            } else { $('#form_cascading_section').hide(); }
        });

        $('#form_cascading_kegiatan_id').on('change', function() {
            const cascadingId = $(this).val();
            const opt = $(this).find(':selected');
            if (cascadingId) {
                $('#info_sasaran_keg').text(opt.data('sasaran'));
                $('#info_indikator_keg').text(opt.data('indikator'));

                $.get("{{ route('frontend.renstra.cascadingsubkegiatan.getAssociatedValues') }}", { cascading_kegiatan_id: cascadingId }, function(data) {
                    $('#info_program').text(data.program_info);
                    $('#form_refcascadingprogram_id').val(data.refcascadingprogram_id);
                    $('#form_refsasaranrenstra_id').val(data.refsasaranrenstra_id);
                    $('#form_refindikatorsasaranrenstra_id').val(data.refindikatorsasaranrenstra_id);
                    $('#form_refprogram_id').val(data.refprogram_id);
                    $('#form_refkegiatan_id').val(data.refkegiatan_id);

                    $.get("{{ route('frontend.renstra.cascadingsubkegiatan.getSubKegiatans') }}", { kegiatan_id: data.refkegiatan_id }, function(res) {
                        let options = '<option></option>';
                        res.forEach(item => options += `<option value="${item.refsubkegiatan_id}">${item.kode_subkegiatan} - ${item.nama_subkegiatan}</option>`);
                        $('#form_refsubkegiatan_id').html(options).trigger('change');
                    });
                });
            }
        });

        $('#btn_save_subkegiatan').on('click', function() {
            const btn = $(this);
            const method = $('#form_method').val();
            const id = $('#refcascadingsubkegiatan_id').val();
            const url = method === 'POST' ? "{{ route('frontend.renstra.cascadingsubkegiatan.store') }}" : `/frontend/renstra/cascadingsubkegiatan/${id}`;
            
            btn.attr("data-kt-indicator", "on").prop("disabled", true);
            const disabledFields = $('#form_subkegiatan').find(':disabled').prop('disabled', false);
            let formData = $('#form_subkegiatan').serializeArray();
            disabledFields.prop('disabled', true);

            $.ajax({
                url: url, type: 'POST',
                data: $.param(formData) + (method === 'PUT' ? '&_method=PUT' : ''),
                success: function(res) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    Swal.fire('Berhasil!', res.success, 'success');
                    getDrawer("#kt_drawer_subkegiatan").hide();
                    table.ajax.reload();
                },
                error: function(err) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    Swal.fire('Error!', err.responseJSON.error || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const baseUrl = "{{ url('frontend/renstra/cascadingsubkegiatan') }}";
            $.get(`${baseUrl}/${id}`, function(data) {
                $('#drawer_title').text('Edit Cascading Sub Kegiatan');
                $('#form_method').val('PUT');
                $('#refcascadingsubkegiatan_id').val(data.refcascadingsubkegiatan_id);
                $('#form_skpd_id').val(data.refskpd_id);
                
                $('#form_periode_id, #form_cascading_kegiatan_id, #form_refsubkegiatan_id').prop('disabled', true);
                $('#form_periode_id').val(data.refperiode_id).trigger('change');
                
                setTimeout(() => {
                    $('#form_cascading_kegiatan_id').val(data.refcascadingkegiatan_id).trigger('change');
                    setTimeout(() => {
                        $('#form_refsubkegiatan_id').val(data.refsubkegiatan_id).trigger('change');
                    }, 800);
                }, 800);

                $('#form_uraian_sasaransubkegiatan').val(data.uraian_sasaransubkegiatan);
                $('#form_uraian_indikatorsubkegiatan').val(data.uraian_indikatorsubkegiatan);
                $('#form_subkegiatan_target').val(data.subkegiatan_target);
                $('#form_subkegiatan_satuan').val(data.subkegiatan_satuan);
                anAnggaran.set(data.subkegiatan_anggaran);
                getDrawer("#kt_drawer_subkegiatan").show();
            });
        });

        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({ title: 'Hapus data ini?', text: "Data triwulan dan indikator terkait akan ikut terhapus!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!' }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ url: `/frontend/renstra/cascadingsubkegiatan/${id}`, type: 'DELETE', success: function(res) {
                        Swal.fire('Terhapus!', res.success, 'success');
                        table.ajax.reload();
                    }});
                }
            });
        });

        // --- Penjabat ---
        $(document).on('click', '.btn-add-pj', function() {
            const btn = $(this);
            $('#pj_refcascadingsubkegiatan_id').val(btn.data('id'));
            $('#pj_refskpd_id').val(btn.data('skpd'));
            $('#pj_refperiode_id').val(btn.data('periode'));
            $('#pj_program_info').text('Sub Kegiatan: ' + btn.data('name'));
            $('#pj_indikator_info').text('Indikator: ' + btn.data('indikator'));
            $('#pj_detail_section').hide();

            $.get("{{ route('frontend.renstra.penjabat-subkegiatan.fetch') }}", { 
                refperiode_id: btn.data('periode'), 
                refskpd_id: btn.data('skpd'),
                refcascadingsubkegiatan_id: btn.data('id') 
            }, function(data) {
                let options = '<option></option>';
                data.forEach(item => options += `<option value="${item.refpenjabatskpd_id}" data-nip="${item.nip_penjabat}" data-jabatan="${item.jabatan_eselon}">${item.nama_penjabat}</option>`);
                $('#pj_refpenjabatskpd_id').html(options).trigger('change');
                getDrawer("#kt_drawer_penjabat").show();
            });
        });

        $('#pj_refpenjabatskpd_id').on('change', function() {
            const opt = $(this).find(':selected');
            if (opt.val()) {
                $('#pj_nip').text(opt.data('nip')); 
                $('#pj_jabatan').text(opt.data('jabatan'));
                $('#pj_detail_section').fadeIn();
            } else { $('#pj_detail_section').hide(); }
        });

        $('#btn_save_penjabat').on('click', function() {
            const btn = $(this);
            btn.attr("data-kt-indicator", "on").prop("disabled", true);

            $.ajax({ 
                url: "{{ route('frontend.renstra.penjabat-subkegiatan.store') }}", 
                type: 'POST', 
                data: $('#form_penjabat').serialize(), 
                success: function(res) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    Swal.fire('Berhasil!', res.success, 'success'); 
                    getDrawer("#kt_drawer_penjabat").hide(); 
                    table.ajax.reload();
                },
                error: function(err) {
                    btn.removeAttr("data-kt-indicator").prop("disabled", false);
                    Swal.fire('Error!', err.responseJSON.error || 'Terjadi kesalahan.', 'error');
                }
            });
        });

        $(document).on('click', '.btn-delete-pj', function() {
            const id = $(this).data('id');
            const baseUrl = "{{ url('frontend/renstra/penjabat-subkegiatan') }}";
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
