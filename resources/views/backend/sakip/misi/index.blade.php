@extends('backend.layout.app')
@section('title', 'Manajemen Misi SAKIP')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
        <!--begin::Toolbar container-->
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <!--begin::Page title-->
            <div class="page-title d-flex flex-column justify-content-center me-3">
                <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                    Manajemen Misi</h1>
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
                    <li class="breadcrumb-item text-gray-900">Manajemen Misi</li>
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
                <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Misi..." />
            </div>
        </div>
        <div class="card-toolbar">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" onclick="addData()">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Misi
                </button>
            </div>
        </div>
    </div>
    <div class="card-body py-4">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_misi">
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="min-w-50px">No</th>
                    <th class="min-w-100px">Periode</th>
                    <th class="min-w-200px">Visi</th>
                    <th class="min-w-250px">Uraian Misi</th>
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
<div class="modal fade" id="modal_misi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="max-height: 90vh; display: flex; flex-direction: column; overflow: hidden;">
            <form id="form_misi" class="ajax-form" action="javascript:void(0)" style="display: flex; flex-direction: column; height: 100%; overflow: hidden;">
                @csrf
                <input type="hidden" name="id" id="misi_id">
                <div class="modal-header" style="flex-shrink: 0;">
                    <h2 class="fw-bold" id="modal_title">Tambah Misi</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body py-5 px-lg-10" style="overflow-y: auto; flex: 1 1 auto;">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Periode</label>
                        <select name="refperiode_id" id="refperiode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode..." data-dropdown-parent="#modal_misi" required>
                            <option></option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Pilih Visi</label>
                        <select name="refvisi_id" id="refvisi_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Visi..." data-dropdown-parent="#modal_misi" required>
                            <option></option>
                            @foreach($visis as $v)
                                <option value="{{ $v->refvisi_id }}">{{ $v->uraian_visi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Uraian Misi</label>
                        <textarea class="form-control form-control-solid" name="uraian_misi" id="uraian_misi" rows="3" required></textarea>
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

@push('stylesheets')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border: 1px solid #eff2f5;
        background-color: #f5f8fa;
        border-radius: 0.75rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    var table;
    $(function () {
        $('#uraian_misi').summernote({
            placeholder: 'Tuliskan misi di sini...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        table = $('#kt_table_misi').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('sakip.misi.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_periode', name: 'nama_periode'},
                {data: 'uraian_visi', name: 'uraian_visi'},
                {data: 'uraian_misi', name: 'uraian_misi'},
                {data: 'misi_isaktif', name: 'misi_isaktif'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        // Search Binding
        $('[data-kt-user-table-filter="search"]').on('keyup', function() {
            table.search($(this).val()).draw();
        });

        // Dependent Dropdown Logic
        $('#refperiode_id').on('change', function() {
            const periodeId = $(this).val();
            const visiSelect = $('#refvisi_id');
            
            visiSelect.html('<option></option>').prop('disabled', true);
            
            if (periodeId) {
                $.get("{{ url('/admin/sakip/visi/get-by-periode') }}/" + periodeId, function(res) {
                    let options = '<option></option>';
                    res.forEach(item => {
                        const cleanText = item.uraian_visi.replace(/<\/?[^>]+(>|$)/g, "");
                        options += `<option value="${item.refvisi_id}">${cleanText}</option>`;
                    });
                    visiSelect.html(options).prop('disabled', false);
                    
                    const currentVisiId = visiSelect.data('current-id');
                    if (currentVisiId) {
                        visiSelect.val(currentVisiId).trigger('change');
                        visiSelect.data('current-id', ''); 
                    }
                });
            }
        });

        $('#form_misi').submit(function (e) {
            e.preventDefault();
            const btn = $('#btn_submit');
            btn.attr('data-kt-indicator', 'on').prop('disabled', true);

            $.ajax({
                url: "{{ route('sakip.misi.store') }}",
                type: "POST", data: $(this).serialize(),
                success: function (res) {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    $('#modal_misi').modal('hide'); table.ajax.reload();
                    Swal.fire({ text: res.success, icon: "success", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function() {
                    btn.removeAttr('data-kt-indicator').prop('disabled', false);
                    Swal.fire({ text: "Terjadi kesalahan sistem.", icon: "error", buttonsStyling: false, confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-danger" } });
                }
            });
        });
    });

    function addData() {
        $('#form_misi')[0].reset(); $('#misi_id').val(''); 
        $('#refperiode_id').val('').trigger('change');
        $('#refvisi_id').val('').trigger('change');
        $('#uraian_misi').summernote('code', '');
        $('#modal_title').text('Tambah Misi'); $('#modal_misi').modal('show');
    }

    function editData(id) {
        $.get("{{ url('/admin/sakip/misi') }}/" + id + "/edit", function (data) {
            $('#misi_id').val(data.refmisi_id);
            $('#uraian_misi').val(data.uraian_misi);
            $('#uraian_misi').summernote('code', data.uraian_misi || '');
            
            $('#refvisi_id').data('current-id', data.refvisi_id);
            $('#refperiode_id').val(data.refperiode_id).trigger('change');
            $('#is_aktif').prop('checked', data.misi_isaktif == 'T');
            $('#modal_title').text('Edit Misi'); $('#modal_misi').modal('show');
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
                    url: "{{ url('/admin/sakip/misi') }}/" + id,
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
