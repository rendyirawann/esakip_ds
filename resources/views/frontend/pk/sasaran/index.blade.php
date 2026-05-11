@extends('frontend.layout.app')

@section('title', 'Target PK Indikator Sasaran (Tahunan)')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Target PK Indikator Sasaran (Tahunan)</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">PK</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Indikator Sasaran (Tahunan)</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!-- Filter Card -->
            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <div class="row g-5 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold fs-6">Pilih SKPD</label>
                            <select id="filter_skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD" {{ $isSuperadmin ? '' : 'disabled' }}>
                                <option></option>
                                @foreach($skpds as $s)
                                    <option value="{{ $s->refskpd_id }}" {{ ($current_skpd && $s->refskpd_id == $current_skpd->refskpd_id) ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-6">Pilih Tahun</label>
                            <select id="filter_periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun">
                                <option></option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}" {{ ($current_periode && $p->refperiode_id == $current_periode->refperiode_id) ? 'selected' : '' }}>{{ $p->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold no-loader">
                                <span class="indicator-label">
                                    <i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data
                                </span>
                                <span class="indicator-progress">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}">
                <div class="card-body p-20 text-center">
                    <img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="Illustration" />
                    <h2 class="fw-bold text-gray-800 mb-3">Pilih SKPD untuk melihat data</h2>
                    <p class="text-gray-400 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
                </div>
            </div>

            <!-- Table Card -->
            <div id="table_card" class="card card-flush shadow-sm {{ $current_skpd && $current_periode ? '' : 'd-none' }}">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                            <input type="text" id="dt_search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data..." />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table id="kt_datatable_pk" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px text-center">No</th>
                                <th class="d-none">Sasaran</th>
                                <th class="min-w-200px">Indikator</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">IKU</th>
                                <th class="text-center">PK</th>
                                <th class="text-center">Target</th>
                                <th class="text-center">RKT</th>
                                <th class="text-center">Ket RKT</th>
                                <th class="text-center">PK</th>
                                <th class="text-center">Ket PK</th>
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

<!-- Modal Edit PK -->
<div class="modal fade" id="modal_edit_pk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Update Target PK</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <form id="form_edit_pk" class="no-loader">
                    <input type="hidden" id="edit_id">
                    
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Indikator</label>
                        <input type="text" id="edit_indikator" class="form-control form-control-solid" readonly />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Sasaran</label>
                        <textarea id="edit_sasaran" class="form-control form-control-solid" rows="2" readonly></textarea>
                    </div>

                    <div class="row g-9 mb-7">
                        <div class="col-md-12 fv-row">
                            <label class="required fs-6 fw-semibold mb-2">Target PK</label>
                            <input type="text" id="edit_target_pk" name="target_pk" class="form-control form-control-solid" placeholder="Input Target PK" required />
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Keterangan PK</label>
                        <textarea id="edit_keterangan_pk" name="keterangan_pk" class="form-control form-control-solid" rows="3" placeholder="Input Keterangan PK"></textarea>
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_pk">
                            <span class="indicator-label">Simpan Perubahan</span>
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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

    $(document).ready(function() {
        initDataTable();

        $('#btn_tampilkan').on('click', function() {
            const skpd = $('#filter_skpd_id').val();
            const periode = $('#filter_periode_id').val();
            const btn = $(this);

            if (!skpd || !periode) {
                Swal.fire({ text: "Silakan pilih SKPD dan Tahun terlebih dahulu.", icon: "warning" });
                return;
            }

            // Show indicator
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $('#empty_state').addClass('d-none');
            $('#table_card').removeClass('d-none');
            
            dt.ajax.reload(function() {
                // Hide indicator after reload
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
            });
        });

        $('#dt_search').on('keyup', function() {
            dt.search($(this).val()).draw();
        });

        $('#form_edit_pk').submit(function(e) {
            e.preventDefault();
            const id = $('#edit_id').val();
            const btn = $('#btn_save_pk');
            
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                url: "{{ route('frontend.pk.sasaran.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    target_pk: $('#edit_target_pk').val(),
                    keterangan_pk: $('#edit_keterangan_pk').val()
                },
                success: function(response) {
                    $('#modal_edit_pk').modal('hide');
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    toastr.success(response.message);
                    dt.ajax.reload(null, false);
                },
                error: function(xhr) {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                }
            });
        });
    });

    function initDataTable() {
        dt = $('#kt_datatable_pk').DataTable({
            processing: false, // Use button indicator instead
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "{{ route('frontend.pk.sasaran.data') }}",
                data: function(d) {
                    d.skpd_id = $('#filter_skpd_id').val();
                    d.periode_id = $('#filter_periode_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'uraian_sasaran', name: 'uraian_sasaran', visible: false },
                { data: 'uraian_indikatorsasaranrenstra', name: 'uraian_indikatorsasaranrenstra' },
                { data: 'indikatorsasaranrenstra_satuan', name: 'indikatorsasaranrenstra_satuan', className: 'text-center' },
                { 
                    data: 'iku_isaktif', 
                    name: 'iku_isaktif', 
                    className: 'text-center',
                    render: function(data) {
                        return data === 'T' ? '<span class="badge badge-light-success px-3">IKU</span>' : '';
                    }
                },
                { 
                    data: 'pk_isaktif', 
                    name: 'pk_isaktif', 
                    className: 'text-center',
                    render: function(data) {
                        return data === 'T' ? '<span class="badge badge-light-info px-3">PK</span>' : '';
                    }
                },
                { data: 'indikatorsasaranrenstra_target', name: 'indikatorsasaranrenstra_target', className: 'text-center fw-bold' },
                { data: 'target_rkt_display', name: 'target_rkt_display', className: 'text-center text-dark' },
                { data: 'keterangan_rkt', name: 'keterangan_rkt', className: 'text-center fs-7' },
                { data: 'target_pk_display', name: 'target_pk_display', className: 'text-center fw-bold text-primary' },
                { data: 'keterangan_pk_display', name: 'keterangan_pk_display', className: 'text-center fs-7' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            order: [[1, 'asc']],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(1, { page: 'current' }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group bg-light"><td colspan="11" class="fw-bolder text-gray-800 fs-6 py-3">Sasaran: ' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    }

    function editPk(id) {
        $.ajax({
            url: "{{ url('frontend/pk/sasaran') }}/" + id + "/edit",
            type: "GET",
            success: function(data) {
                $('#edit_id').val(data.refindikatorsasaranrenstra_id);
                $('#edit_indikator').val(data.uraian_indikatorsasaranrenstra);
                $('#edit_sasaran').val(data.sasaran ? data.sasaran.uraian_sasaranrenstra : '-');
                $('#edit_target_pk').val(data.target_pk);
                $('#edit_keterangan_pk').val(data.keterangan_pk);
                $('#modal_edit_pk').modal('show');
            }
        });
    }
</script>
@endpush
