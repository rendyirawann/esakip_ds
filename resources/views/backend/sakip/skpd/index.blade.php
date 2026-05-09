@extends('backend.layout.app')
@section('title', 'Manajemen SKPD & Penjabat')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen SKPD & Penjabat</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="{{ route('dashboard') }}" class="text-hover-primary">
                            <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <i class="ki-outline ki-right fs-5 text-gray-700 mx-n1"></i>
                    </li>
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">SAKIP Data Master</li>
                    <li class="breadcrumb-item">
                        <i class="ki-outline ki-right fs-5 text-gray-700 mx-n1"></i>
                    </li>
                    <li class="breadcrumb-item text-gray-900">Manajemen SKPD</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="card card-flush shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari SKPD..." />
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="addData()">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah SKPD
                </button>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_skpd">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-50px">No</th>
                    <th class="min-w-100px">Kode</th>
                    <th class="min-w-200px">Nama SKPD</th>
                    <th class="min-w-150px">Kepala SKPD</th>
                    <th class="min-w-80px">Status</th>
                    <th class="text-end min-w-150px">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal SKPD -->
<div class="modal fade modal-right" id="modal_skpd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_skpd" class="ajax-form" action="javascript:void(0)">
                @csrf
                <input type="hidden" name="id" id="skpd_id">
                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah SKPD</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Kode SKPD</label>
                        <input type="text" class="form-control form-control-solid" name="kode_skpd" id="kode_skpd" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama SKPD</label>
                        <input type="text" class="form-control form-control-solid" name="nama_skpd" id="nama_skpd" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">Nama Kepala SKPD</label>
                        <input type="text" class="form-control form-control-solid" name="kepala_skpd" id="kepala_skpd" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold mb-2">NIP Kepala</label>
                        <input type="text" class="form-control form-control-solid" name="nip_kepala" id="nip_kepala" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Urusan</label>
                        <select name="refurusan_id" id="refurusan_id" class="form-select form-select-solid" data-control="select2" data-dropdown-parent="#modal_skpd">
                            @foreach($urusans as $u)
                                <option value="{{ $u->urusan_id }}">{{ $u->nama_urusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <div class="d-flex flex-stack">
                            <div class="me-5">
                                <label class="fs-6 fw-semibold">Status Aktif</label>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="skpd_isaktif" id="skpd_isaktif" checked="checked" />
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer flex-center">
                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn_submit">
                        <span class="indicator-label">Simpan</span>
                        <span class="indicator-progress">Mohon tunggu... 
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Drawer Penjabat -->
<div class="modal fade modal-right" id="modal_manage_penjabat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Kelola Penjabat: <span id="target_skpd_name" class="text-primary"></span></h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="fs-4 fw-bold mb-0">Daftar Penjabat</h3>
                    <button type="button" class="btn btn-sm btn-light-primary" onclick="addPenjabat()">
                        <i class="ki-outline ki-plus fs-3"></i> Tambah Penjabat
                    </button>
                </div>
                
                <div id="form_penjabat_container" class="mb-10 d-none bg-light p-5 rounded">
                    <form id="form_penjabat" class="ajax-form">
                        @csrf
                        <input type="hidden" name="penjabat_id" id="penjabat_id">
                        <input type="hidden" name="refskpd_id" id="penjabat_skpd_id">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="required fs-7 fw-bold mb-1">Nama Penjabat</label>
                                <input type="text" name="nama_penjabat" id="nama_penjabat" class="form-control form-control-sm form-control-solid" required>
                            </div>
                            <div class="col-md-6">
                                <label class="fs-7 fw-bold mb-1">NIP</label>
                                <input type="text" name="nip_penjabat" id="nip_penjabat" class="form-control form-control-sm form-control-solid">
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-7 fw-bold mb-1">Periode</label>
                                <select name="refperiode_id" id="penjabat_periode_id" class="form-select form-select-sm form-select-solid" required>
                                    @foreach($periodes as $p)
                                        <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="fs-7 fw-bold mb-1">Jabatan Eselon</label>
                                <input type="text" name="jabatan_eselon" id="jabatan_eselon" class="form-control form-control-sm form-control-solid">
                            </div>
                            <div class="col-12 text-end">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="$('#form_penjabat_container').addClass('d-none')">Batal</button>
                                <button type="submit" id="btn_submit_penjabat" class="btn btn-sm btn-primary">
                                    <span class="indicator-label">Simpan Penjabat</span>
                                    <span class="indicator-progress">Mohon tunggu... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-300 align-middle gs-0 gy-3" id="table_penjabat">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th>Nama Penjabat</th>
                                <th>NIP</th>
                                <th>Periode</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var table, tablePenjabat;
    var currentSkpdId = null;

    $(function () {
        table = $('#kt_table_skpd').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('sakip.skpd.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'kode_skpd', name: 'kode_skpd'},
                {data: 'nama_skpd', name: 'nama_skpd'},
                {data: 'kepala_skpd', name: 'kepala_skpd'},
                {data: 'skpd_isaktif', name: 'skpd_isaktif'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
            ]
        });

        $('[data-kt-user-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#form_skpd').submit(function (e) {
            e.preventDefault();
            const btn = $('#btn_submit_skpd');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);
            $.ajax({
                url: "{{ route('sakip.skpd.store') }}",
                type: "POST", data: $(this).serialize(),
                success: function (res) {
                    hideLoader();
                    $('#modal_skpd').modal('hide'); table.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function() {
                    hideLoader();
                    Swal.fire({ text: "Terjadi kesalahan sistem.", icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                }
            });
        });

        $('#form_penjabat').submit(function (e) {
            e.preventDefault();
            const btn = $('#btn_submit_penjabat');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);
            $.ajax({
                url: "{{ route('sakip.skpd.store-penjabat') }}",
                type: "POST", data: $(this).serialize(),
                success: function (res) {
                    hideLoader();
                    $('#form_penjabat_container').addClass('d-none');
                    tablePenjabat.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function() {
                    hideLoader();
                    Swal.fire({ text: "Terjadi kesalahan sistem.", icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                }
            });
        });
    });

    function addData() {
        $('#form_skpd')[0].reset(); $('#skpd_id').val('');
        $('#modal_title').text('Tambah SKPD'); $('#modal_skpd').modal('show');
    }

    function editData(id) {
        $.get("{{ url('/admin/sakip/skpd') }}/" + id + "/edit", function (data) {
            $('#skpd_id').val(data.refskpd_id);
            $('#kode_skpd').val(data.kode_skpd);
            $('#nama_skpd').val(data.nama_skpd);
            $('#kepala_skpd').val(data.kepala_skpd);
            $('#nip_kepala').val(data.nip_kepala);
            $('#refurusan_id').val(data.refurusan_id).trigger('change');
            $('#skpd_isaktif').prop('checked', data.skpd_isaktif == 1);
            $('#modal_title').text('Edit SKPD'); $('#modal_skpd').modal('show');
        });
    }

    function managePenjabat(id, name) {
        currentSkpdId = id;
        $('#target_skpd_name').text(name);
        $('#penjabat_skpd_id').val(id);
        $('#modal_manage_penjabat').modal('show');
        
        if ($.fn.DataTable.isDataTable('#table_penjabat')) {
            tablePenjabat.ajax.url("{{ url('/admin/sakip/skpd/penjabat') }}/" + id).load();
        } else {
            tablePenjabat = $('#table_penjabat').DataTable({
                processing: true, serverSide: true,
                ajax: "{{ url('/admin/sakip/skpd/penjabat') }}/" + id,
                columns: [
                    {data: 'nama_penjabat', name: 'nama_penjabat'},
                    {data: 'nip_penjabat', name: 'nip_penjabat'},
                    {data: 'periode.periode', name: 'periode.periode'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }
    }

    function addPenjabat() {
        $('#form_penjabat')[0].reset();
        $('#penjabat_id').val('');
        $('#penjabat_skpd_id').val(currentSkpdId);
        $('#form_penjabat_container').removeClass('d-none');
    }

    function editPenjabat(id) {
        $.get("{{ url('/admin/sakip/skpd/penjabat-edit') }}/" + id, function (data) {
            $('#penjabat_id').val(data.refpenjabatskpd_id);
            $('#nama_penjabat').val(data.nama_penjabat);
            $('#nip_penjabat').val(data.nip_penjabat);
            $('#penjabat_periode_id').val(data.refperiode_id);
            $('#jabatan_eselon').val(data.jabatan_eselon);
            $('#form_penjabat_container').removeClass('d-none');
        });
    }

    function deletePenjabat(id) {
        Swal.fire({
            text: "Apakah Anda yakin ingin menghapus penjabat ini?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batal",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: "{{ url('/admin/sakip/skpd/penjabat-delete') }}/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        tablePenjabat.ajax.reload();
                        Swal.fire({
                            text: res.success,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, mengerti!",
                            customClass: {
                    data: { _token: "{{ csrf_token() }}" }
                });
            },
            allowOutsideClick: () => !Swal.isLoading(),
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                tablePenjabat.ajax.reload();
                Swal.fire({
                    text: result.value.success,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, mengerti!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                });
            }
        });
    }

    function deleteData(id) {
        Swal.fire({
            text: "Apakah Anda yakin ingin menghapus data SKPD ini?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batal",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ url('/admin/sakip/skpd') }}/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" }
                });
            },
            allowOutsideClick: () => !Swal.isLoading(),
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                table.ajax.reload();
                Swal.fire({
                    text: result.value.success,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, mengerti!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    }
                });
            }
        });
    }
</script>
@endpush
