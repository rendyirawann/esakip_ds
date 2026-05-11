@extends('frontend.layout.app')

@section('title', 'Target PK Triwulan Indikator Sub Kegiatan')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Target PK Triwulan Indikator Sub Kegiatan</h1>
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
                    <li class="breadcrumb-item text-dark">Triwulan Sub Kegiatan</li>
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
                            <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}">
                <div class="card-body p-20 text-center">
                    <img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="Illustration" />
                    <h2 class="fw-bold text-gray-800 mb-3">Pilih SKPD dan Tahun untuk melihat data</h2>
                    <p class="text-gray-400 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
                </div>
            </div>

            <!-- Content Container -->
            <div id="content_card" class="{{ $current_skpd && $current_periode ? '' : 'd-none' }}">
                <div class="row gx-5">
                    
                    <!-- Sidebar Triwulan -->
                    <div class="col-md-2">
                        <div class="card card-flush shadow-sm">
                            <div class="card-body p-0">
                                <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link w-100 btn btn-active-light-primary text-start fw-bold py-4 px-6 mb-2 rounded-0 rounded-top border-bottom {{ $current_triwulan == 1 ? 'active' : '' }}" data-tw="1" data-bs-toggle="pill" type="button" role="tab">Triwulan 1</button>
                                    <button class="nav-link w-100 btn btn-active-light-primary text-start fw-bold py-4 px-6 mb-2 rounded-0 border-bottom {{ $current_triwulan == 2 ? 'active' : '' }}" data-tw="2" data-bs-toggle="pill" type="button" role="tab">Triwulan 2</button>
                                    <button class="nav-link w-100 btn btn-active-light-primary text-start fw-bold py-4 px-6 mb-2 rounded-0 border-bottom {{ $current_triwulan == 3 ? 'active' : '' }}" data-tw="3" data-bs-toggle="pill" type="button" role="tab">Triwulan 3</button>
                                    <button class="nav-link w-100 btn btn-active-light-primary text-start fw-bold py-4 px-6 rounded-0 rounded-bottom {{ $current_triwulan == 4 ? 'active' : '' }}" data-tw="4" data-bs-toggle="pill" type="button" role="tab">Triwulan 4</button>
                                </div>
                                <input type="hidden" id="active_triwulan" value="{{ $current_triwulan }}">
                            </div>
                        </div>
                    </div>

                    <!-- Table Triwulan -->
                    <div class="col-md-10">
                        <div class="card card-flush shadow-sm">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <h3 class="card-title fw-bold text-gray-800 fs-4 m-0" id="table_title">
                                        Data PK Triwulan Indikator Sub Kegiatan
                                    </h3>
                                </div>
                            </div>
                            <div class="card-body pt-5">
                                <table id="kt_datatable" class="table align-middle table-row-dashed fs-6 gy-5 table-bordered">
                                    <thead>
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="w-50px text-center px-3">No</th>
                                            <th class="d-none">Sub Kegiatan</th>
                                            <th class="min-w-200px px-3">Indikator</th>
                                            <th class="px-3">Satuan</th>
                                            <th class="text-center px-3">Target PK<br/>Tahunan</th>
                                            <th class="text-center px-3" id="col_target_triwulan">Target PK<br/>Triwulan 1</th>
                                            <th class="px-3">Sebab Perubahan</th>
                                            <th class="text-center w-100px px-3">Aksi</th>
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

<!-- Modal Edit -->
<div class="modal fade" id="modal_edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-600px">
        <div class="modal-content">
            <div class="modal-header bg-light-primary py-4">
                <h2 class="fw-bold m-0" id="modal_title">Edit PK Triwulan Sub Kegiatan</h2>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <form id="form_edit" class="form no-loader" action="javascript:void(0);">
                @csrf
                <div class="modal-body py-10 px-lg-17" id="modal_edit_content">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center py-4">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btn_save" class="btn btn-primary">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Tunggu <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
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

            if (!skpd || !periode) {
                Swal.fire({ text: "Silakan pilih SKPD dan Tahun terlebih dahulu.", icon: "warning" });
                return;
            }

            $('#empty_state').addClass('d-none');
            $('#content_card').removeClass('d-none');
            updateTableTitle();
            dt.ajax.reload();
        });

        $('.nav-link[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
            const targetTriwulan = $(e.target).data('tw');
            $('#active_triwulan').val(targetTriwulan);
            $('#col_target_triwulan').html(`Target PK<br/>Triwulan ${targetTriwulan}`);
            
            if (!$('#content_card').hasClass('d-none')) {
                dt.ajax.reload();
                updateTableTitle();
            }
        });

        function updateTableTitle() {
            const periodeText = $('#filter_periode_id option:selected').text();
            const tw = $('#active_triwulan').val();
            $('#table_title').text(`Data PK Triwulan Indikator Sub Kegiatan (Periode ${periodeText}) - Triwulan ${tw}`);
        }

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const tw = $('#active_triwulan').val();
            $('#modal_title').text(`Edit Target PK Triwulan ${tw} Sub Kegiatan`);
            $('#modal_edit').modal('show');
            $('#modal_edit_content').html('<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            
            $.get("{{ url('frontend/pk/subkegiatan-triwulan') }}/" + id + "/edit", function(res) {
                $('#modal_edit_content').html(res.html);
            });
        });

        $('#form_edit').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#btn_save');
            btn.attr("data-kt-indicator", "on");
            btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('frontend.pk.subkegiatan.triwulan.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    $('#modal_edit').modal('hide');
                    toastr.success(res.message);
                    dt.ajax.reload(null, false);
                },
                error: function(err) {
                    Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                },
                complete: function() {
                    btn.removeAttr("data-kt-indicator");
                    btn.prop('disabled', false);
                }
            });
        });
    });

    function initDataTable() {
        dt = $('#kt_datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "{{ route('frontend.pk.subkegiatan.triwulan.data') }}",
                data: function(d) {
                    d.skpd_id = $('#filter_skpd_id').val();
                    d.periode_id = $('#filter_periode_id').val();
                    d.triwulan_id = $('#active_triwulan').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center px-3' },
                { data: 'nama_subkegiatan', name: 'nama_subkegiatan', visible: false },
                { data: 'indikator', name: 'indikator', className: 'px-3' },
                { data: 'satuan', name: 'satuan', className: 'px-3' },
                { data: 'target_pk_tahunan', name: 'target_pk_tahunan', className: 'text-center px-3' },
                { data: 'target_pk_triwulan', name: 'target_pk_triwulan', className: 'text-center px-3' },
                { data: 'keterangan', name: 'keterangan', className: 'px-3' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center px-3' }
            ],
            order: [[1, 'asc']],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(1, { page: 'current' }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="7" class="fw-bolder text-gray-800 fs-6 py-3 px-3 bg-light">Sub Kegiatan: ' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    }
</script>
@endpush
