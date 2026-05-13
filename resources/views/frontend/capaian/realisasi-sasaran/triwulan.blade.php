@extends('frontend.layout.app')
@section('title', 'Capaian Kinerja - Realisasi Sasaran (Triwulan)')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Realisasi Sasaran (Triwulan)</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted"><a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Capaian Kinerja</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Realisasi Sasaran Triwulan</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Filter --}}
            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <div class="row g-5 align-items-end">
                        @if($isSuperadmin)
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-6">Pilih SKPD</label>
                            <select id="filter_skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD">
                                <option></option>
                                @foreach($skpds as $s)
                                    <option value="{{ $s->refskpd_id }}" {{ $current_skpd && $s->refskpd_id == $current_skpd->refskpd_id ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="{{ $isSuperadmin ? 'col-md-3' : 'col-md-5' }}">
                            <label class="form-label fw-bold fs-6">Pilih Tahun</label>
                            <select id="filter_periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun">
                                <option></option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}" {{ $current_periode && $p->refperiode_id == $current_periode->refperiode_id ? 'selected' : '' }}>{{ $p->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold fs-6">Triwulan</label>
                            <select id="filter_triwulan_id" class="form-select form-select-solid" data-control="select2">
                                <option value="1" {{ $current_triwulan == 1 ? 'selected' : '' }}>Triwulan 1</option>
                                <option value="2" {{ $current_triwulan == 2 ? 'selected' : '' }}>Triwulan 2</option>
                                <option value="3" {{ $current_triwulan == 3 ? 'selected' : '' }}>Triwulan 3</option>
                                <option value="4" {{ $current_triwulan == 4 ? 'selected' : '' }}>Triwulan 4</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold no-loader">
                                <span class="indicator-label"><i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data</span>
                                <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Empty State --}}
            <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}">
                <div class="card-body p-20 text-center">
                    <img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="">
                    <h2 class="fw-bold text-gray-800 mb-3">Pilih Filter untuk Melihat Data</h2>
                    <p class="text-gray-400 fs-5">Silakan pilih SKPD, tahun, dan triwulan melalui form filter di atas.</p>
                </div>
            </div>

            {{-- Content area with sidebar tabs --}}
            <div id="table_card" class="{{ $current_skpd && $current_periode ? '' : 'd-none' }}">
                <div class="row">
                    {{-- Sidebar Triwulan Tab --}}
                    <div class="col-md-2">
                        <div class="card shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex flex-column gap-2" id="triwulan_tabs">
                                    @foreach([1,2,3,4] as $tw)
                                    <a href="javascript:void(0)"
                                       class="btn btn-sm fw-bold btn-tab-triwulan {{ $current_triwulan == $tw ? 'btn-primary' : 'btn-light-primary' }}"
                                       data-tw="{{ $tw }}">Triwulan {{ $tw }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Table --}}
                    <div class="col-md-10">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <span id="label_triwulan" class="fw-bold fs-5 text-primary me-3">Triwulan {{ $current_triwulan }}</span>
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                                        <input type="text" id="dt_search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data..." />
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <table id="kt_datatable_capaian" class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-50px text-center">No</th>
                                            <th class="d-none">Sasaran</th>
                                            <th class="min-w-200px">Indikator</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-center">IKU</th>
                                            <th class="text-center">PK</th>
                                            <th class="text-center">Target PK<br/>Tahunan</th>
                                            <th class="text-center">Target PK<br/>Triwulan</th>
                                            <th class="text-center">Realisasi</th>
                                            <th class="text-center">Capaian</th>
                                            <th class="text-center">Keterangan</th>
                                            <th class="text-center min-w-150px">Analisis</th>
                                            <th class="text-end min-w-70px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modal_edit_capaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Input Realisasi Triwulan</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <form id="form_edit_capaian" class="no-loader">
                    <div id="modal_form_content"></div>
                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_capaian">
                            <span class="indicator-label">Simpan</span>
                            <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let dt;
let currentTriwulan = {{ $current_triwulan }};

$(document).ready(function() {
    initDataTable();

    $('#btn_tampilkan').on('click', function() {
        const skpd = @if($isSuperadmin) $('#filter_skpd_id').val() @else '{{ optional($current_skpd)->refskpd_id }}' @endif;
        const periode = $('#filter_periode_id').val();
        const tw = $('#filter_triwulan_id').val();
        const btn = $(this);

        if (!skpd || !periode) { Swal.fire({ text: "Silakan pilih SKPD dan Tahun terlebih dahulu.", icon: "warning" }); return; }

        currentTriwulan = tw;
        updateTriwulanUI(tw);

        btn.attr('data-kt-indicator', 'on').prop('disabled', true);
        $('#empty_state').addClass('d-none');
        $('#table_card').removeClass('d-none');
        dt.ajax.reload(function() { btn.removeAttr('data-kt-indicator').prop('disabled', false); });
    });

    // Tab click
    $(document).on('click', '.btn-tab-triwulan', function() {
        const tw = $(this).data('tw');
        currentTriwulan = tw;
        $('#filter_triwulan_id').val(tw).trigger('change');
        updateTriwulanUI(tw);
        dt.ajax.reload();
    });

    $('#dt_search').on('keyup', function() { dt.search($(this).val()).draw(); });

    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "{{ url('frontend/capaian/realisasi-sasaran/triwulan') }}/" + id + "/edit",
            success: function(res) {
                $('#modal_form_content').html(res.html);
                $('#modal_edit_capaian').modal('show');
                // Auto-kalkulasi capaian dari target PK Triwulan
                $(document).off('input', '#modal_realisasi').on('input', '#modal_realisasi', function() {
                    var target = parseFloat($('#modal_target_pk').val()) || 0;
                    var realisasi = parseFloat($(this).val()) || 0;
                    if (target > 0 && realisasi >= 0) {
                        var capaian = (realisasi / target) * 100;
                        $('#modal_capaian').val(capaian.toFixed(2));
                    } else {
                        $('#modal_capaian').val('');
                    }
                });
            }
        });
    });

    $('#form_edit_capaian').submit(function(e) {
        e.preventDefault();
        const btn = $('#btn_save_capaian');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);
        $.ajax({
            url: "{{ route('frontend.capaian.realisasi-sasaran.triwulan.store') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: $('[name="id"]').val(),
                triwulan_realisasi: $('[name="triwulan_realisasi"]').val(),
                triwulan_capaian: $('[name="triwulan_capaian"]').val(),
                triwulan_keterangan: $('[name="triwulan_keterangan"]').val(),
                triwulan_analisis: $('[name="triwulan_analisis"]').val()
            },
            success: function(res) {
                $('#modal_edit_capaian').modal('hide');
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                toastr.success(res.message);
                dt.ajax.reload(null, false);
            },
            error: function() {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
            }
        });
    });
});

function updateTriwulanUI(tw) {
    $('#label_triwulan').text('Triwulan ' + tw);
    $('.btn-tab-triwulan').removeClass('btn-primary').addClass('btn-light-primary');
    $('.btn-tab-triwulan[data-tw="' + tw + '"]').removeClass('btn-light-primary').addClass('btn-primary');
}

function initDataTable() {
    dt = $('#kt_datatable_capaian').DataTable({
        processing: false, serverSide: true, pageLength: 25,
        ajax: {
            url: "{{ route('frontend.capaian.realisasi-sasaran.triwulan.data') }}",
            data: function(d) {
                d.skpd_id    = @if($isSuperadmin) $('#filter_skpd_id').val() @else '{{ optional($current_skpd)->refskpd_id }}' @endif;
                d.periode_id = $('#filter_periode_id').val();
                d.triwulan_id = currentTriwulan;
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
            { data: 'uraian_sasaran', name: 'uraian_sasaran', visible: false },
            { data: 'indikator', name: 'indikator' },
            { data: 'satuan', name: 'satuan', className: 'text-center' },
            { data: 'status_iku', name: 'status_iku', className: 'text-center', orderable: false },
            { data: 'status_pk', name: 'status_pk', className: 'text-center', orderable: false },
            { data: 'target_pk_tahunan', name: 'target_pk_tahunan', className: 'text-center fw-bold text-primary' },
            { data: 'target_pk_triwulan', name: 'target_pk_triwulan', className: 'text-center' },
            { data: 'realisasi_display', name: 'realisasi_display', className: 'text-center fw-bold' },
            { data: 'capaian_display', name: 'capaian_display', className: 'text-center', orderable: false },
            { data: 'keterangan', name: 'keterangan', className: 'text-center fs-7' },
            { data: 'analisis', name: 'analisis', className: 'fs-7' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
        ],
        order: [[1, 'asc']],
        drawCallback: function() {
            var api = this.api(), rows = api.rows({ page: 'current' }).nodes(), last = null;
            api.column(1, { page: 'current' }).data().each(function(group, i) {
                if (last !== group) {
                    $(rows).eq(i).before('<tr class="group bg-light"><td colspan="13" class="fw-bolder text-gray-800 fs-6 py-3 ps-4">Sasaran: ' + group + '</td></tr>');
                    last = group;
                }
            });
        }
    });
}
</script>
@endpush
