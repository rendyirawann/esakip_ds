@extends('backend.layout.app')
@section('title', 'Data Pimpinan')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Pimpinan</h1>
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
                    <li class="breadcrumb-item text-gray-900">Manajemen Pimpinan</li>
                </ul>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="card card-flush shadow-sm">
    <div class="card-header pt-7">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bold text-gray-900">Data Pimpinan</span>
            <span class="text-gray-500 mt-1 fw-semibold fs-7">Manajemen pimpinan SAKIP</span>
        </h3>
        <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-primary" onclick="addData()">
                <i class="ki-outline ki-plus fs-2"></i> Tambah Pimpinan
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_pimpinan">
            <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">No</th>
                    <th class="min-w-150px">Nama Pimpinan</th>
                    <th class="min-w-100px">Jabatan</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
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
                    <input type="hidden" name="id" id="data_id">
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Periode</label>
                        <select name="refperiode_id" id="refperiode_id" class="form-select form-select-solid">
                            <option value="">Pilih Periode</option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Nama Pimpinan</label>
                        <input type="text" name="nama_pimpinan" id="nama_pimpinan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Lengkap" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fw-semibold fs-6 mb-2">Jabatan Pimpinan</label>
                        <input type="text" name="jabatan_pimpinan" id="jabatan_pimpinan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Jabatan" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Nama Wakil Pimpinan</label>
                        <input type="text" name="nama_wpimpinan" id="nama_wpimpinan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Nama Wakil" />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fw-semibold fs-6 mb-2">Jabatan Wakil Pimpinan</label>
                        <input type="text" name="jabatan_wpimpinan" id="jabatan_wpimpinan" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Jabatan Wakil" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btn_submit" class="btn btn-primary">
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
    var table = $('#kt_table_pimpinan').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('sakip.pimpinan.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_pimpinan', name: 'nama_pimpinan'},
            {data: 'jabatan_pimpinan', name: 'jabatan_pimpinan'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end'},
        ]
    });

    $('[data-kt-user-table-filter="search"]').on('keyup', function() {
        table.search($(this).val()).draw();
    });

    function addData() {
        $('#kt_modal_form')[0].reset();
        $('#data_id').val('');
        $('#modal_title').text('Tambah Pimpinan');
        $('#kt_modal_data').modal('show');
    }

    function editData(id) {
        $.get("{{ url('admin/sakip/pimpinan') }}/" + id + "/edit", function(data) {
            $('#data_id').val(data.refpimpinan_id);
            $('#refperiode_id').val(data.refperiode_id);
            $('#nama_pimpinan').val(data.nama_pimpinan);
            $('#jabatan_pimpinan').val(data.jabatan_pimpinan);
            $('#nama_wpimpinan').val(data.nama_wpimpinan);
            $('#jabatan_wpimpinan').val(data.jabatan_wpimpinan);
            $('#modal_title').text('Edit Pimpinan');
            $('#kt_modal_data').modal('show');
        });
    }

    $('#kt_modal_form').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#btn_submit');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);
        $.ajax({
            url: "{{ route('sakip.pimpinan.store') }}",
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
                    url: "{{ url('admin/sakip/pimpinan') }}/" + id,
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
