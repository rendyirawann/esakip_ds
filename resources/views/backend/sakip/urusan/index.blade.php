@extends('backend.layout.app')
@section('title', 'Manajemen Urusan SAKIP')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <!--begin::Title-->
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Urusan</h1>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
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
                    <li class="breadcrumb-item text-gray-900">Manajemen Urusan</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page title-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <div class="card card-flush shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <div class="d-flex align-items-center position-relative my-1">
                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Urusan..." />
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="addData()">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Urusan
                </button>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_urusan">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-50px">No</th>
                    <th class="min-w-125px">Kode Urusan</th>
                    <th class="min-w-125px">Nama Urusan</th>
                    <th class="min-w-100px">Status</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
    </div>
</div>

        </div>
@endsection

@section('modals')
<!-- Modal -->
<div class="modal fade modal-right" id="modal_urusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_urusan" class="ajax-form" action="javascript:void(0)">
                @csrf
                <input type="hidden" name="id" id="urusan_id">
                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah Urusan</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Kode Urusan</label>
                        <input type="text" class="form-control form-control-solid" name="kode_urusan" id="kode_urusan" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Urusan</label>
                        <input type="text" class="form-control form-control-solid" name="nama_urusan" id="nama_urusan" required />
                    </div>
                    <div class="fv-row mb-7">
                        <div class="d-flex flex-stack">
                            <div class="me-5">
                                <label class="fs-6 fw-semibold">Status Aktif</label>
                                <div class="fs-7 fw-semibold text-muted">Aktifkan untuk menampilkan data ini di pilihan.</div>
                            </div>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" checked="checked" />
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
@endsection

@push('scripts')
<script>
    var table;
    $(function () {
        table = $('#kt_table_urusan').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('sakip.urusan.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'kode_urusan', name: 'kode_urusan'},
                {data: 'nama_urusan', name: 'nama_urusan'},
                {data: 'urusan_isaktif', name: 'urusan_isaktif'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
            ]
        });

        $('[data-kt-user-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        $('#form_urusan').submit(function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            const btn = $('#btn_submit');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);
            $.ajax({
                url: "{{ route('sakip.urusan.store') }}",
                type: "POST",
                data: formData,
                success: function (res) {
                    hideLoader();
                    $('#modal_urusan').modal('hide');
                    table.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok, Mengerti", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function (err) {
                    hideLoader();
                    $('#btn_submit').prop('disabled', false);
                    let errors = err.responseJSON.errors;
                    let errorList = '';
                    $.each(errors, function(key, val) {
                        errorList += `<div class="d-flex align-items-center mb-2"><i class="ki-outline ki-cross-circle fs-3 text-danger me-2"></i><span>${val[0]}</span></div>`;
                    });
                    Swal.fire({ html: `<div class="text-start">${errorList}</div>`, icon: 'error', title: 'Ada Kesalahan Input', buttonsStyling: false, confirmButtonText: 'Perbaiki Sekarang', customClass: { confirmButton: 'btn btn-danger' } });
                }
            });
        });
    });

    function addData() {
        $('#form_urusan')[0].reset();
        $('#urusan_id').val('');
        $('#modal_title').text('Tambah Urusan');
        $('#modal_urusan').modal('show');
    }

    function editData(id) {
        $.get("{{ url('/admin/sakip/urusan') }}/" + id + "/edit", function (data) {
            $('#urusan_id').val(data.urusan_id);
            $('#kode_urusan').val(data.kode_urusan);
            $('#nama_urusan').val(data.nama_urusan);
            $('#is_aktif').prop('checked', data.urusan_isaktif == 1);
            $('#modal_title').text('Edit Urusan');
            $('#modal_urusan').modal('show');
        });
    }

    function deleteData(id) {
        Swal.fire({
            text: "Apakah Anda yakin ingin menghapus data ini?",
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Tidak, Batal",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ url('/admin/sakip/urusan') }}/" + id,
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
