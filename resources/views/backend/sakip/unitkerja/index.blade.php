@extends('backend.layout.app')
@section('title', 'Data Unit Kerja')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Unit Kerja</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                        <a href="{{ route('dashboard') }}" class="text-white text-hover-primary">
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
                    <li class="breadcrumb-item text-gray-900">Manajemen Unit Kerja</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="card card-flush shadow-sm">
    <div class="card-header pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Data Unit Kerja</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-7">Manajemen unit kerja SAKIP</span>
        </h3>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-primary" onclick="addData()">
                <i class="ki-outline ki-plus fs-2"></i> Tambah Unit Kerja
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_unitkerja">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">No</th>
                    <th class="min-w-200px">Nama Unit Kerja</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-right" id="kt_modal_data" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="kt_modal_form" class="ajax-form">
                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah Data</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Kode</label>
                        <input type="text" name="kode" id="kode" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Kode Unit" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Nama Unit Kerja</label>
                        <input type="text" name="nama" id="nama" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Unit Kerja" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Keterangan"></textarea>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    var table = $('#kt_table_unitkerja').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('sakip.unitkerja.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'keterangan', name: 'keterangan'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
        ]
    });

    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search($(this).val()).draw();
    });

    function addData() {
        $('#kt_modal_form')[0].reset();
        $('#data_id').val('');
        $('#modal_title').text('Tambah Unit Kerja');
        $('#kt_modal_data').modal('show');
    }

    function editData(id) {
        $.get("{{ url('admin/sakip/unitkerja') }}/" + id + "/edit", function(data) {
            $('#data_id').val(data.id);
            $('#kode').val(data.kode);
            $('#nama').val(data.nama);
            $('#keterangan').val(data.keterangan);
            $('#modal_title').text('Edit Unit Kerja');
            $('#kt_modal_data').modal('show');
        });
    }

    $('#kt_modal_form').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btn_submit');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);
        $.ajax({
            url: "{{ route('sakip.unitkerja.store') }}",
            type: "POST",
            data: $(this).serialize() + "&_token={{ csrf_token() }}",
            success: function(data) {
                hideLoader();
                $('#kt_modal_data').modal('hide');
                table.ajax.reload();
                Swal.fire({ text: data.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
            },
            error: function(xhr) {
                hideLoader();
                Swal.fire({ text: "Gagal menyimpan data", icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
            }
        });
    });

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
                    url: "{{ url('admin/sakip/unitkerja') }}/" + id,
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
