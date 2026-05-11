@extends('frontend.layout.app')

@section('title', 'RKT Sasaran Renstra')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">RKT Sasaran Renstra</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">RKT</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Sasaran Renstra</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!-- Filter Card -->
            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <form id="filter_form" class="no-loader">
                        <div class="row g-5 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-bold fs-6">Pilih SKPD</label>
                                <select name="skpd_id" id="skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD" {{ $isSuperadmin ? '' : 'disabled' }}>
                                    <option></option>
                                    @foreach($skpds as $s)
                                        <option value="{{ $s->refskpd_id }}" {{ ($current_skpd && $s->refskpd_id == $current_skpd->refskpd_id) ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold fs-6">Pilih Tahun</label>
                                <select name="periode_id" id="periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun">
                                    <option></option>
                                    @foreach($periodes as $p)
                                        <option value="{{ $p->refperiode_id }}" {{ ($current_periode && $p->refperiode_id == $current_periode->refperiode_id) ? 'selected' : '' }}>{{ $p->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="btn_filter" class="btn btn-primary w-100 fw-bold">
                                    <i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data
                                </button>
                            </div>
                        </div>
                    </form>
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
                    <div class="card-toolbar">
                        <!-- Space for buttons like Export etc if needed -->
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table id="kt_datatable_rkt" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px">No</th>
                                <th class="d-none">Sasaran</th>
                                <th class="min-w-200px">Indikator</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">IKU</th>
                                <th class="text-center">PK</th>
                                <th class="text-center">Target</th>
                                <th class="text-center">RKT</th>
                                <th>Keterangan</th>
                                <th class="text-end min-w-70px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update RKT -->
<div class="modal fade" id="modal_edit_rkt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Update Target RKT</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <form id="form_edit_rkt" class="no-loader">
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
                            <label class="required fs-6 fw-semibold mb-2">Target RKT</label>
                            <input type="text" id="edit_target_rkt" name="target_rkt" class="form-control form-control-solid" placeholder="Input Target RKT" required />
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Sebab Perubahan</label>
                        <textarea id="edit_keterangan" name="keterangan" class="form-control form-control-solid" rows="3" placeholder="Input Keterangan"></textarea>
                    </div>

                    <div class="text-center pt-10">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn_save_rkt">
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

        $('#btn_filter').click(function() {
            const skpd_id = $('#skpd_id').val();
            const periode_id = $('#periode_id').val();

            if (!skpd_id || !periode_id) {
                Swal.fire({
                    text: "Harap pilih SKPD dan Tahun.",
                    icon: "info",
                    buttonsStyling: false,
                    confirmButtonText: "OK",
                    customClass: { confirmButton: "btn btn-primary" }
                });
                return;
            }

            $('#empty_state').addClass('d-none');
            $('#table_card').removeClass('d-none');
            dt.ajax.reload();
        });

        $('#dt_search').on('keyup', function() {
            dt.search($(this).val()).draw();
        });
    });

    function initDataTable() {
        dt = $('#kt_datatable_rkt').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "{{ route('frontend.rkt.sasaran.data') }}",
                data: function(d) {
                    d.skpd_id = $('#skpd_id').val();
                    d.periode_id = $('#periode_id').val();
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
                { 
                    data: 'target_rkt', 
                    name: 'target_rkt', 
                    className: 'text-center fw-bold text-dark',
                    render: function(data) { return data || '-'; }
                },
                { 
                    data: 'keterangan', 
                    name: 'keterangan',
                    render: function(data) { return data || '-'; }
                },
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
                            '<tr class="group bg-light"><td colspan="9" class="fw-bolder text-gray-800 fs-6 py-3">Sasaran: ' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            },
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });
    }

    function editRkt(id) {
        $.ajax({
            url: "{{ url('frontend/rkt/sasaran') }}/" + id + "/edit",
            type: "GET",
            success: function(data) {
                $('#edit_id').val(data.refindikatorsasaranrenstra_id);
                $('#edit_indikator').val(data.uraian_indikatorsasaranrenstra);
                $('#edit_sasaran').val(data.sasaran ? data.sasaran.uraian_sasaranrenstra : '-');
                $('#edit_target_rkt').val(data.target_rkt);
                $('#edit_keterangan').val(data.keterangan);
                $('#modal_edit_rkt').modal('show');
            }
        });
    }

    $('#form_edit_rkt').submit(function(e) {
        e.preventDefault();
        const id = $('#edit_id').val();
        const btn = $('#btn_save_rkt');
        
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);

        $.ajax({
            url: "{{ url('frontend/rkt/sasaran') }}/" + id,
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                target_rkt: $('#edit_target_rkt').val(),
                keterangan: $('#edit_keterangan').val()
            },
            success: function(response) {
                $('#modal_edit_rkt').modal('hide');
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                Swal.fire({
                    text: "Data berhasil diperbarui!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Tutup",
                    customClass: { confirmButton: "btn btn-primary" }
                }).then(() => {
                    dt.ajax.reload(null, false);
                });
            },
            error: function(xhr) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                Swal.fire({
                    text: "Gagal memperbarui data.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Tutup",
                    customClass: { confirmButton: "btn btn-danger" }
                });
            }
        });
    });
</script>
@endpush
