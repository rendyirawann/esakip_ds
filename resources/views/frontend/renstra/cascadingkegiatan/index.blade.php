@extends('frontend.layout.app')

@section('title', 'Cascading Kegiatan')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Cascading Kegiatan</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">Renstra</li>
                    <li class="breadcrumb-item text-muted">Cascading Kegiatan</li>
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
                    <p class="text-gray-400">Pilih filter di atas untuk menampilkan data Cascading Kegiatan.</p>
                </div>
            </div>

            <!-- Table Card -->
            <div id="table_card" class="card d-none">
                <div class="card-header border-0 pt-6">
                    <div class="card-title"></div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success" id="btn_tambah_cascading">
                            <i class="ki-outline ki-plus-square fs-2 me-1"></i> Data Cascading Kegiatan
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
<div id="kt_drawer_program" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="program" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_program_toggle" data-kt-drawer-close="#kt_drawer_program_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="kt_drawer_program_header">
            <h3 class="card-title fw-bolder text-gray-700" id="drawer_title">Tambah Cascading Kegiatan</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_program_close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_drawer_program_body">
            <form id="form_program">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="POST">
                <input type="hidden" name="refcascadingkegiatan_id" id="refcascadingkegiatan_id">
                <input type="hidden" name="refskpd_id" id="form_skpd_id">
                <input type="hidden" name="refsasaranrenstra_id" id="form_refsasaranrenstra_id">
                <input type="hidden" name="refindikatorsasaranrenstra_id" id="form_refindikatorsasaranrenstra_id">
                <input type="hidden" name="refbidang_id" id="form_refbidang_id">
                <input type="hidden" name="refprogram_id" id="form_refprogram_id">
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
                        <label class="form-label required">Indikator Program (Cascading)</label>
                        <select name="refcascadingprogram_id" id="form_cascading_program_id" class="form-select" data-control="select2" data-placeholder="Pilih Indikator Program">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-5 border-start border-primary border-4 ps-4 bg-light-primary py-3 rounded">
                        <div class="fs-8 fw-bold text-gray-600">Info Cascading:</div>
                        <div id="info_sasaran" class="fs-7 text-dark fw-bold"></div>
                        <div id="info_indikator" class="fs-7 text-dark italic"></div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Kegiatan Master</label>
                        <select name="refkegiatan_id" id="form_kegiatan_id" class="form-select" data-control="select2" data-placeholder="Pilih Kegiatan">
                            <option></option>
                        </select>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Uraian Sasaran Kegiatan</label>
                        <textarea name="uraian_sasarankegiatan" id="form_uraian_sasarankegiatan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-5">
                        <label class="form-label required">Uraian Indikator Kegiatan</label>
                        <textarea name="uraian_indikatorkegiatan" id="form_uraian_indikatorkegiatan" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label class="form-label required">Target</label>
                                <input type="text" name="kegiatan_target" id="form_kegiatan_target" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-5">
                                <label class="form-label required">Satuan</label>
                                <input type="text" name="kegiatan_satuan" id="form_kegiatan_satuan" class="form-control">
                            </div>
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

<!-- Drawer Penjabat -->
<div id="kt_drawer_penjabat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="penjabat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '450px'}" data-kt-drawer-direction="end" data-kt-drawer-close="#kt_drawer_penjabat_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header">
            <h3 class="card-title fw-bolder text-gray-700">Tautkan Penjabat SKPD</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_penjabat_close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form id="form_penjabat">
                @csrf
                <input type="hidden" name="refcascadingkegiatan_id" id="pj_refcascadingkegiatan_id">
                <input type="hidden" name="refskpd_id" id="pj_refskpd_id">
                <input type="hidden" name="refperiode_id" id="pj_refperiode_id">

                <div class="mb-8 p-4 bg-light-info rounded border border-info border-dashed">
                    <div id="pj_program_info" class="fs-8 fw-bold text-gray-700 mb-1"></div>
                    <div id="pj_indikator_info" class="fs-7 text-dark italic"></div>
                </div>

                <div class="mb-5">
                    <label class="form-label required">Pilih Penjabat</label>
                    <select name="refpenjabatskpd_id" id="pj_refpenjabatskpd_id" class="form-select" data-control="select2" data-placeholder="Cari Penjabat...">
                        <option></option>
                    </select>
                </div>

                <div id="pj_detail_section" style="display:none;">
                    <div class="separator separator-dashed my-5"></div>
                    <div class="d-flex flex-column mb-3">
                        <label class="text-muted fs-8 fw-bold">NIP</label>
                        <span id="pj_nip" class="text-dark fw-bold fs-7"></span>
                    </div>
                    <div class="d-flex flex-column mb-3">
                        <label class="text-muted fs-8 fw-bold">Jabatan</label>
                        <span id="pj_jabatan" class="text-dark fs-7 italic"></span>
                    </div>
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
    function formatNumber(num) {
        return 'Rp. ' + new Intl.NumberFormat('id-ID').format(num);
    }

    function initDataTable() {
        const skpd = $('#filter_skpd').val();
        const periode = $('#filter_periode').val();

        table = $('#kt_cascading_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.cascadingkegiatan.data') }}",
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
                                <button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btn-edit" data-id="${row.refcascadingkegiatan_id}" title="Edit">
                                    <i class="fas fa-edit fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btn-delete" data-id="${row.refcascadingkegiatan_id}" title="Hapus">
                                    <i class="fas fa-trash fs-4"></i>
                                </button>
                                <button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm btn-add-pj" 
                                    data-id="${row.refcascadingkegiatan_id}" 
                                    data-skpd="${row.refskpd_id}"
                                    data-periode="${row.refperiode_id}"
                                    data-program-name="${row.kegiatan_nama}"
                                    data-indikator-name="${row.uraian_indikatorkegiatan}"
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
                        let html = `<div class="text-gray-800 fw-bold fs-7">${row.uraian_sasarankegiatan || '-'} - ${row.uraian_indikatorkegiatan || '-'}</div>`;
                        
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
                            <div class="accordion mt-2" id="acc_pj_${row.refcascadingkegiatan_id}">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed py-2 px-0 fs-8 fw-bold bg-transparent shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_pj_${row.refcascadingkegiatan_id}">
                                            Lihat Penjabat SKPD (${row.penjabat_list ? row.penjabat_list.length : 0})
                                        </button>
                                    </h2>
                                    <div id="collapse_pj_${row.refcascadingkegiatan_id}" class="accordion-collapse collapse" data-bs-parent="#acc_pj_${row.refcascadingkegiatan_id}">
                                        <div class="accordion-body p-4 bg-light-secondary rounded mt-2">
                                            ${penjabatHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                },
                { data: 'kegiatan_satuan' },
                { data: 'kegiatan_target' },
                { 
                    data: 'anggaran', 
                    render: function(data) { return formatNumber(data); },
                    orderable: false, searchable: false
                },
                { data: 'misi_text', visible: false, orderable: false },
                { data: 'tujuan_text', visible: false, orderable: false },
                { data: 'sasaran_text', visible: false, orderable: false },
                { data: 'program_text', visible: false, orderable: false },
                { data: 'kegiatan_kode', visible: false, orderable: false },
                { data: 'kegiatan_nama', visible: false, orderable: false },
            ],
            order: [[6, 'asc'], [7, 'asc'], [8, 'asc'], [9, 'asc'], [10, 'asc']],
            rowGroup: {
                dataSrc: ['misi_text', 'tujuan_text', 'sasaran_text', 'program_text', 'kegiatan_kode'],
                startRender: function(rows, group, level) {
                    if (level === 0) return $('<tr class="group-misi"><td colspan="6" class="p-3 fs-6 fw-bolder bg-success text-white">' + group + '</td></tr>');
                    if (level === 1) return $('<tr class="group-tujuan"><td colspan="6" class="p-3 fs-7 fw-bold bg-danger text-white">(Tujuan) ' + group + '</td></tr>');
                    if (level === 2) return $('<tr class="group-sasaran"><td colspan="6" class="p-3 fs-7 fw-bold bg-primary text-white">(Sasaran) ' + group + '</td></tr>');
                    if (level === 3) return $('<tr class="group-program"><td colspan="6" class="p-3 fs-8 fw-bold bg-dark text-white">(Program) ' + group + '</td></tr>');
                    if (level === 4) {
                        var kgtName = rows.data()[0].kegiatan_nama;
                        return $('<tr class="group-kegiatan"><td colspan="6" class="p-3 fs-8 fw-bold bg-info text-white">' + group + ' - ' + kgtName + '</td></tr>');
                    }
                }
            },
            drawCallback: function(settings) {
                KTMenu.createInstances();
            }
        });
    }

    $(document).ready(function() {
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

        // Open Create
        $('#btn_tambah_cascading').on('click', function() {
            $('#drawer_title').text('Tambah Cascading Kegiatan');
            $('#form_program')[0].reset();
            $('#form_method').val('POST');
            $('#form_skpd_id').val($('#filter_skpd').val());
            
            // Enable fields for New Entry
            $('#form_periode_id, #form_cascading_program_id, #form_kegiatan_id').prop('disabled', false);
            $('#form_periode_id').val('').trigger('change');
            
            $('#form_cascading_section').hide();
            getDrawer("#kt_drawer_program").show();
        });

        // Cascading Periode -> Program Cascading
        $('#form_periode_id').on('change', function() {
            const periodeId = $(this).val();
            const skpdId = $('#form_skpd_id').val();
            if (periodeId && skpdId) {
                $('#form_cascading_section').show();
                $.get("{{ route('frontend.renstra.cascadingkegiatan.getProgramCascading') }}", { skpd_id: skpdId, periode_id: periodeId }, function(data) {
                    let options = '<option></option>';
                    data.forEach(item => {
                        const progName = item.program ? item.program.nama_program : '-';
                        options += `<option value="${item.refcascadingprogram_id}" data-sasaran="${item.uraian_sasaranprogram}" data-indikator="${item.uraian_indikatorprogram}">${progName} - ${item.uraian_indikatorprogram}</option>`;
                    });
                    $('#form_cascading_program_id').html(options).trigger('change');
                });
            } else {
                $('#form_cascading_section').hide();
            }
        });

        // Cascading Program -> Kegiatan & Associated
        $('#form_cascading_program_id').on('change', function() {
            const cascadingId = $(this).val();
            const opt = $(this).find(':selected');
            if (cascadingId) {
                $('#info_sasaran').text(opt.data('sasaran'));
                $('#info_indikator').text(opt.data('indikator'));

                $.get("{{ route('frontend.renstra.cascadingkegiatan.getAssociatedValues') }}", { cascading_program_id: cascadingId }, function(data) {
                    $('#form_refsasaranrenstra_id').val(data.refsasaranrenstra_id);
                    $('#form_refindikatorsasaranrenstra_id').val(data.refindikatorsasaranrenstra_id);
                    $('#form_refbidang_id').val(data.refbidang_id);
                    $('#form_refprogram_id').val(data.refprogram_id);
                    $('#form_refsasaran_id').val(data.refsasaran_id);
                    $('#form_reftujuan_id').val(data.reftujuan_id);
                    $('#form_refmisi_id').val(data.refmisi_id);

                    // Fetch kegiatans
                    $.get("{{ route('frontend.renstra.cascadingkegiatan.getKegiatans') }}", { program_id: data.refprogram_id }, function(res) {
                        let options = '<option></option>';
                        res.forEach(item => options += `<option value="${item.refkegiatan_id}">${item.kode_kegiatan} - ${item.nama_kegiatan}</option>`);
                        $('#form_kegiatan_id').html(options).trigger('change');
                    });
                });
            }
        });

        // Save Kegiatan
        $('#btn_save_program').on('click', function() {
            const btn = $(this);
            const method = $('#form_method').val();
            const id = $('#refcascadingkegiatan_id').val();
            const url = method === 'POST' ? "{{ route('frontend.renstra.cascadingkegiatan.store') }}" : `/frontend/renstra/cascadingkegiatan/${id}`;
            
            btn.attr("data-kt-indicator", "on");
            btn.prop("disabled", true);

            const disabledFields = $('#form_program').find(':disabled').prop('disabled', false);
            let formData = $('#form_program').serializeArray();
            disabledFields.prop('disabled', true);

            if (method === 'POST') {
                formData = formData.filter(item => item.name !== 'refcascadingkegiatan_id');
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
            const baseUrl = "{{ url('frontend/renstra/cascadingkegiatan') }}";
            $.get(`${baseUrl}/${id}`, function(data) {
                $('#drawer_title').text('Edit Cascading Kegiatan');
                $('#form_method').val('PUT');
                $('#refcascadingkegiatan_id').val(data.refcascadingkegiatan_id);
                $('#form_skpd_id').val(data.refskpd_id);
                
                $('#form_periode_id, #form_cascading_program_id, #form_kegiatan_id').prop('disabled', true);
                
                $('#form_periode_id').val(data.refperiode_id).trigger('change');
                
                setTimeout(() => {
                    $('#form_cascading_program_id').val(data.refcascadingprogram_id).trigger('change');
                    setTimeout(() => {
                        $('#form_kegiatan_id').val(data.refkegiatan_id).trigger('change');
                    }, 800);
                }, 800);

                $('#form_uraian_sasarankegiatan').val(data.uraian_sasarankegiatan);
                $('#form_uraian_indikatorkegiatan').val(data.uraian_indikatorkegiatan);
                $('#form_kegiatan_target').val(data.kegiatan_target);
                $('#form_kegiatan_satuan').val(data.kegiatan_satuan);
                getDrawer("#kt_drawer_program").show();
            });
        });

        // Delete
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({ title: 'Hapus data ini?', text: "Data triwulan dan indikator terkait akan ikut terhapus!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Ya, Hapus!' }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({ url: `/frontend/renstra/cascadingkegiatan/${id}`, type: 'DELETE', success: function(res) {
                        Swal.fire('Terhapus!', res.success, 'success');
                        table.ajax.reload();
                    }});
                }
            });
        });

        // --- Penjabat ---
        $(document).on('click', '.btn-add-pj', function() {
            const btn = $(this);
            $('#pj_refcascadingkegiatan_id').val(btn.data('id'));
            $('#pj_refskpd_id').val(btn.data('skpd'));
            $('#pj_refperiode_id').val(btn.data('periode'));
            $('#pj_program_info').text('Kegiatan: ' + btn.data('program-name'));
            $('#pj_indikator_info').text('Indikator: ' + btn.data('indikator-name'));
            $('#pj_detail_section').hide();

            $.get("{{ route('frontend.renstra.penjabat-kegiatan.fetch') }}", { 
                refperiode_id: btn.data('periode'), 
                refskpd_id: btn.data('skpd'),
                refcascadingkegiatan_id: btn.data('id') 
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
                $('#pj_nip').text(opt.data('nip')); $('#pj_jabatan').text(opt.data('jabatan'));
                $('#pj_detail_section').fadeIn();
            } else { $('#pj_detail_section').hide(); }
        });

        $('#btn_save_penjabat').on('click', function() {
            const btn = $(this);
            btn.attr("data-kt-indicator", "on");
            btn.prop("disabled", true);

            $.ajax({ 
                url: "{{ route('frontend.renstra.penjabat-kegiatan.store') }}", 
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
            const baseUrl = "{{ url('frontend/renstra/penjabat-kegiatan') }}";
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
