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
<div id="kt_drawer_cascading" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cascading" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '600px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_cascading_toggle" data-kt-drawer-close="#kt_drawer_cascading_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header">
            <h3 class="card-title fw-bold text-dark">Kelola Cascading Program</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_cascading_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative">
            <form id="kt_modal_cascading_form" class="form ajax-form">
                @csrf
                <p class="text-muted fst-italic">Formulir input data cascading program sedang disiapkan...</p>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var table;
    var datatableInitialized = false;

    function formatNumber(num) {
        return 'Rp. ' + parseFloat(num).toLocaleString('id-ID');
    }

    function initDataTable() {
        table = $('#kt_cascading_table').DataTable({
            searchDelay: 500,
            processing: false,
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
                            <div class="d-flex gap-1">
                                <button class="btn btn-icon btn-sm btn-light-success h-25px w-25px" onclick="editCascading(${row.refcascadingprogram_id})"><i class="ki-outline ki-pencil fs-7"></i></button>
                                <button class="btn btn-icon btn-sm btn-light-danger h-25px w-25px" onclick="deleteCascading(${row.refcascadingprogram_id})"><i class="ki-outline ki-trash fs-7"></i></button>
                                <button class="btn btn-icon btn-sm btn-light-primary h-25px w-25px" onclick="togglePenjabat(${row.refcascadingprogram_id})"><i class="ki-outline ki-check fs-7"></i></button>
                            </div>
                        `;
                    }
                },
                { data: null, render: () => 'Sasaran/Indikator' },
                { 
                    data: null, 
                    render: function(data, type, row) {
                        let html = `<div class="text-gray-800">${row.uraian_sasaranprogram || '-'} - ${row.uraian_indikatorprogram || '-'}</div>`;
                        
                        // Check if penjabat_list exists and is an array
                        let penjabatHtml = '<span class="text-muted fs-9">Belum ada penjabat yang ditautkan.</span>';
                        if (row.penjabat_list && Array.isArray(row.penjabat_list) && row.penjabat_list.length > 0) {
                            penjabatHtml = row.penjabat_list.map(p => `
                                <div class="mb-3 border-bottom pb-2 border-gray-300">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark fs-8">${p.nama || '-'}</div>
                                            <div class="text-muted fs-9">NIP: ${p.nip || '-'}</div>
                                            <div class="text-muted fs-9">Jabatan: ${p.jabatan || '-'}</div>
                                        </div>
                                        <button class="btn btn-icon btn-sm btn-active-light-danger h-20px w-20px" onclick="deletePenjabat(${p.id})"><i class="ki-outline ki-trash fs-9"></i></button>
                                    </div>
                                </div>
                            `).join('');
                        }

                        // Accordion for Penjabat
                        html += `
                            <div class="mt-2 accordion" id="penjabat_accordion_${row.refcascadingprogram_id}" style="display: none;">
                                <div class="accordion-item border-dashed">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button py-2 fs-8 fw-bold bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_penjabat_${row.refcascadingprogram_id}">
                                            Lihat Penjabat SKPD
                                        </button>
                                    </h2>
                                    <div id="collapse_penjabat_${row.refcascadingprogram_id}" class="accordion-collapse collapse">
                                        <div class="accordion-body py-3 bg-light-secondary">
                                            ${penjabatHtml}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        return html;
                    }
                },
                { data: 'program_satuan' },
                { data: 'program_target' },
                { 
                    data: 'anggaran', 
                    render: function(data) { return formatNumber(data); },
                    orderable: false, searchable: false
                },
                // Hidden columns for grouping
                { data: 'misi_text', visible: false, orderable: false },
                { data: 'tujuan_text', visible: false, orderable: false },
                { data: 'sasaran_text', visible: false, orderable: false },
                { data: 'program_kode', visible: false, orderable: false },
                { data: 'program_nama', visible: false, orderable: false },
            ],
            order: [], // Disable initial order to avoid virtual column sorting
            rowGroup: {
                dataSrc: ['misi_text', 'tujuan_text', 'sasaran_text', 'program_kode'],
                startRender: function (rows, group, level) {
                    if (level === 0) {
                        return $('<tr class="group-misi"><td colspan="6">' + group + '</td></tr>');
                    } else if (level === 1) {
                        return $('<tr class="group-tujuan"><td colspan="6">(Tujuan) ' + group + '</td></tr>');
                    } else if (level === 2) {
                        return $('<tr class="group-sasaran"><td colspan="6">(Sasaran) ' + group + '</td></tr>');
                    } else if (level === 3) {
                        var progName = rows.data()[0].program_nama;
                        return $('<tr class="group-program"><td colspan="6">' + group + ' - ' + progName + '</td></tr>');
                    }
                }
            },
            language: {
                emptyTable: "Pilih Periode dan klik Tampilkan Data untuk memuat cascading."
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

        $('#kt_drawer_cascading_close').on('click', function() {
            var drawerElement = document.querySelector("#kt_drawer_cascading");
            var drawerCascading = KTDrawer.getInstance(drawerElement);
            if (drawerCascading) { drawerCascading.hide(); }
        });
    });

    function togglePenjabat(id) {
        $(`#penjabat_accordion_${id}`).toggle();
    }

    function editCascading(id) {
        Swal.fire({ text: "Modul edit sedang dikembangkan.", icon: "info" });
    }

    function deleteCascading(id) {
        Swal.fire({
            title: "Hapus Data?",
            text: "Data cascading program akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('frontend.renstra.cascadingprogram.index') }}/" + id,
                    type: "POST",
                    data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                    success: function(data) {
                        table.ajax.reload(() => Swal.fire({ text: data.success, icon: "success" }), false);
                    }
                });
            }
        });
    }

    function deletePenjabat(id) {
        Swal.fire({ text: "Fitur hapus penjabat sedang disiapkan.", icon: "info" });
    }
</script>
@endpush
