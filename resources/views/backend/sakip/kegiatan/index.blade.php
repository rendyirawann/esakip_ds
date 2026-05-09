@extends('backend.layout.app')
@section('title', 'Manajemen Kegiatan SAKIP')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Kegiatan</h1>
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
                    <li class="breadcrumb-item text-gray-900">Manajemen Kegiatan</li>
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
                <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Kegiatan..." />
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="addData()">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Kegiatan
                </button>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_kegiatan">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-50px">No</th>
                    <th class="min-w-150px">Program</th>
                    <th class="min-w-100px">Kode</th>
                    <th class="min-w-200px">Nama Kegiatan</th>
                    <th class="min-w-80px">Status</th>
                    <th class="text-end min-w-100px">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modals')
<!-- Modal -->
<div class="modal fade modal-right" id="modal_kegiatan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_kegiatan" class="ajax-form" action="javascript:void(0)">
                @csrf
                <input type="hidden" name="id" id="kegiatan_id">
                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_title">Tambah Kegiatan</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-10 px-lg-17">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Urusan</label>
                        <select name="refurusan_id" id="refurusan_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Urusan..." data-dropdown-parent="#modal_kegiatan" required>
                            <option></option>
                            @foreach($urusans as $u)
                                <option value="{{ $u->urusan_id }}">{{ $u->kode_urusan }} - {{ $u->nama_urusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Bidang</label>
                        <select name="refbidang_id" id="refbidang_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Bidang..." data-dropdown-parent="#modal_kegiatan" required disabled>
                            <option></option>
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Program</label>
                        <select name="refprogram_id" id="refprogram_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Program..." data-dropdown-parent="#modal_kegiatan" required disabled>
                            <option></option>
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Kode Kegiatan</label>
                        <input type="text" class="form-control form-control-solid" name="kode_kegiatan" id="kode_kegiatan" required />
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Nama Kegiatan</label>
                        <input type="text" class="form-control form-control-solid" name="nama_kegiatan" id="nama_kegiatan" required />
                    </div>
                    <div class="fv-row mb-7">
                        <div class="d-flex flex-stack">
                            <div class="me-5">
                                <label class="fs-6 fw-semibold">Status Aktif</label>
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
        table = $('#kt_table_kegiatan').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('sakip.kegiatan.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_program', name: 'nama_program'},
                {data: 'kode_kegiatan', name: 'kode_kegiatan'},
                {data: 'nama_kegiatan', name: 'nama_kegiatan'},
                {data: 'kegiatan_isaktif', name: 'kegiatan_isaktif'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('[data-kt-user-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Dependent Dropdown: Urusan -> Bidang
        $('#refurusan_id').on('change', function() {
            const urusanId = $(this).val();
            const bidangSelect = $('#refbidang_id');
            const programSelect = $('#refprogram_id');
            
            bidangSelect.html('<option></option>').prop('disabled', true);
            programSelect.html('<option></option>').prop('disabled', true);
            
            if (urusanId) {
                $.get("{{ url('/admin/sakip/bidang/get-by-urusan') }}/" + urusanId, function(res) {
                    let options = '<option></option>';
                    res.forEach(item => {
                        options += `<option value="${item.refbidang_id}">${item.kode_bidang} - ${item.nama_bidang}</option>`;
                    });
                    bidangSelect.html(options).prop('disabled', false);
                    
                    const currentBidangId = bidangSelect.data('current-id');
                    if (currentBidangId) {
                        bidangSelect.val(currentBidangId).trigger('change');
                        bidangSelect.data('current-id', '');
                    }
                });
            }
        });

        // Dependent Dropdown: Bidang -> Program
        $('#refbidang_id').on('change', function() {
            const bidangId = $(this).val();
            const programSelect = $('#refprogram_id');
            
            programSelect.html('<option></option>').prop('disabled', true);
            
            if (bidangId) {
                $.get("{{ url('/admin/sakip/program/get-by-bidang') }}/" + bidangId, function(res) {
                    let options = '<option></option>';
                    res.forEach(item => {
                        options += `<option value="${item.refprogram_id}">${item.kode_program} - ${item.nama_program}</option>`;
                    });
                    programSelect.html(options).prop('disabled', false);
                    
                    const currentProgramId = programSelect.data('current-id');
                    if (currentProgramId) {
                        programSelect.val(currentProgramId).trigger('change');
                        programSelect.data('current-id', '');
                    }
                });
            }
        });

        $('#form_kegiatan').submit(function (e) {
            e.preventDefault();
            const btn = $('#btn_submit');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);
            $.ajax({
                url: "{{ route('sakip.kegiatan.store') }}",
                type: "POST", data: $(this).serialize(),
                success: function (res) {
                    hideLoader();
                    $('#modal_kegiatan').modal('hide'); table.ajax.reload();
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
        $('#form_kegiatan')[0].reset(); $('#kegiatan_id').val(''); $('#refprogram_id').val('').trigger('change');
        $('#modal_title').text('Tambah Kegiatan'); $('#modal_kegiatan').modal('show');
    }

    function editData(id) {
        $.get("{{ url('/admin/sakip/kegiatan') }}/" + id + "/edit", function (data) {
            $('#kegiatan_id').val(data.refkegiatan_id);
            $('#kode_kegiatan').val(data.kode_kegiatan);
            $('#nama_kegiatan').val(data.nama_kegiatan);
            
            // Set current IDs for cascading trigger
            $('#refbidang_id').data('current-id', data.refbidang_id);
            $('#refprogram_id').data('current-id', data.refprogram_id);
            
            $('#refurusan_id').val(data.refurusan_id).trigger('change');
            $('#is_aktif').prop('checked', data.kegiatan_isaktif == 'T');
            $('#modal_title').text('Edit Kegiatan'); $('#modal_kegiatan').modal('show');
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
                    url: "{{ url('/admin/sakip/kegiatan') }}/" + id,
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
