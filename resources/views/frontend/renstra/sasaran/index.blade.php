@extends('frontend.layout.app')

@section('title', 'RENSTRA - Sasaran Renstra')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!--begin::Filters-->
        <div class="card mb-5 mb-xl-10 shadow-sm border-0">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Filter Data</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Sesuaikan periode dan unit kerja</span>
                </h3>
            </div>
            <div class="card-body py-5">
                <div class="row g-3">
                    @if($isSuperadmin)
                    <div class="col-md-5">
                        <label class="form-label fw-bold text-gray-700">Pilih SKPD</label>
                        <select id="filter_skpd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD...">
                            <option></option>
                            @foreach($skpds as $s)
                                <option value="{{ $s->refskpd_id }}">{{ $s->nama_skpd }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-gray-700">Pilih Periode (Tahun)</label>
                        <select id="filter_periode" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode...">
                            <option></option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="btn_tampilkan" class="btn btn-success w-100 shadow-sm" style="background-color: #50cd89 !important; border-color: #50cd89 !important;">
                            <i class="ki-outline ki-magnifier fs-2 me-1"></i> Tampilkan Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Filters-->

        <!--begin::Empty State-->
        <div id="empty_state" class="card shadow-sm border-0 py-20">
            <div class="card-body text-center">
                <img src="{{ asset('assets/media/illustrations/sigma-1/5.png') }}" class="h-150px mb-10" alt="Select Data" />
                <h2 class="fw-bold text-gray-800 mb-2">Pilih SKPD untuk melihat data</h2>
                <p class="text-gray-500 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
            </div>
        </div>
        <!--end::Empty State-->

        <div id="data_container" style="display: none;">
            <div class="card border-0 shadow-sm">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <h3 class="card-label">Daftar Sasaran Renstra</h3>
                    </div>
                    <!--begin::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="addData()">
                            <i class="ki-outline ki-plus fs-2"></i>Tambah Data</button>
                        </div>
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_sasaran">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">No</th>
                            <th class="min-w-125px">SKPD</th>
                            <th class="min-w-125px">Tahun Periode</th>
                            <th class="min-w-125px">Sasaran RPJMD</th>
                            <th class="min-w-125px">Sasaran Renstra</th>
                            <th class="text-center min-w-100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
    </div>
</div>

<!-- Modal Right (Drawer style) -->
<div class="modal fade modal-right" id="kt_modal_sasaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold" id="modal_title">Tambah Sasaran Renstra</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <form id="kt_modal_sasaran_form" class="form" action="#">
                    @csrf
                    <input type="hidden" name="id" id="sasaran_id">
                    
                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">SKPD</label>
                        @if($isSuperadmin)
                            <select name="refskpd_id" id="form_skpd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD..." data-dropdown-parent="#kt_modal_sasaran">
                                <option></option>
                                @foreach($skpds as $s)
                                    <option value="{{ $s->refskpd_id }}">{{ $s->nama_skpd }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" class="form-control form-control-solid" value="{{ $current_skpd->nama_skpd ?? 'SKPD Tidak Terdeteksi' }}" readonly />
                            <input type="hidden" name="refskpd_id" value="{{ $current_skpd->refskpd_id ?? '' }}" />
                        @endif
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-semibold mb-2">Tahun Periode</label>
                        <select name="refperiode_id" id="form_periode" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode..." data-dropdown-parent="#kt_modal_sasaran">
                            <option></option>
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group (Dynamic)-->
                    <div id="dynamic_fields" style="display: none;">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Sasaran RPJMD</label>
                            <select name="refsasaran_id" id="form_sasaran_rpjmd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Sasaran RPJMD..." data-dropdown-parent="#kt_modal_sasaran">
                                <option></option>
                            </select>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Uraian Sasaran Renstra</label>
                            <textarea name="uraian_sasaranrenstra" id="form_uraian" class="form-control form-control-solid" rows="3" placeholder="Masukkan uraian sasaran renstra..."></textarea>
                        </div>
                    </div>
                    <!--end::Input group-->

                </form>
            </div>
            <div class="modal-footer flex-center">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Batal</button>
                <button type="button" id="kt_modal_sasaran_submit" class="btn btn-primary" onclick="submitForm()">
                    <span class="indicator-label">Simpan</span>
                    <span class="indicator-progress">Mohon tunggu... 
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var table;
    var datatableInitialized = false;

    $(document).ready(function() {
        // Handle Tampilkan Data click
        $('#btn_tampilkan').on('click', function() {
            var periode_id = $('#filter_periode').val();
            
            if (!periode_id) {
                Swal.fire({
                    text: "Silakan pilih Periode (Tahun) terlebih dahulu.",
                    icon: "warning",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: { confirmButton: "btn btn-primary" }
                });
                return;
            }

            $('#empty_state').hide();
            $('#data_container').fadeIn();

            if (!datatableInitialized) {
                initDataTable();
                datatableInitialized = true;
            } else {
                table.draw();
            }
        });

        // Move this inside ready
        $('#form_periode').on('change', function() {
            var periode_id = $(this).val();
            if (periode_id) {
                $('#dynamic_fields').fadeIn();
                loadSasaranRpjmd(periode_id);
            } else {
                $('#dynamic_fields').fadeOut();
            }
        });
    });

    function initDataTable() {
        table = $('#kt_table_sasaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.sasaran.index') }}",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_skpd', name: 'nama_skpd'},
                {data: 'periode.periode', name: 'periode.periode'},
                {data: 'sasaran_rpjmd', name: 'sasaran_rpjmd'},
                {data: 'uraian_sasaranrenstra', name: 'uraian_sasaranrenstra'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    }

    function loadSasaranRpjmd(periode_id, selected_id = null) {
        $.ajax({
            url: "{{ url('frontend/renstra/get-sasaran-rpjmd') }}/" + periode_id,
            type: "GET",
            success: function(data) {
                var options = '<option></option>';
                $.each(data, function(key, value) {
                    options += '<option value="' + value.refsasaran_id + '" ' + (selected_id == value.refsasaran_id ? 'selected' : '') + '>' + value.uraian_sasaran + '</option>';
                });
                $('#form_sasaran_rpjmd').html(options).trigger('change');
            }
        });
    }

    function addData() {
        $('#kt_modal_sasaran_form')[0].reset();
        $('#sasaran_id').val('');
        $('#modal_title').text('Tambah Sasaran Renstra');
        $('#form_periode').val(null).trigger('change');
        
        @if($isSuperadmin)
            var filter_skpd = $('#filter_skpd').val();
            if (filter_skpd) {
                $('#form_skpd').val(filter_skpd).trigger('change');
            } else {
                $('#form_skpd').val(null).trigger('change');
            }
        @endif

        $('#kt_modal_sasaran').modal('show');
    }

    function editData(id) {
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + id + "/edit", function(data) {
            $('#modal_title').text('Edit Sasaran Renstra');
            $('#sasaran_id').val(data.refsasaranrenstra_id);
            $('#form_uraian').val(data.uraian_sasaranrenstra);
            
            @if($isSuperadmin)
                $('#form_skpd').val(data.refskpd_id).trigger('change');
            @endif

            $('#form_periode').val(data.refperiode_id).trigger('change');
            
            // Wait for dynamic fields to load
            setTimeout(() => {
                loadSasaranRpjmd(data.refperiode_id, data.refsasaran_id);
            }, 500);

            $('#kt_modal_sasaran').modal('show');
        });
    }

    function submitForm() {
        var id = $('#sasaran_id').val();
        var url = id ? "{{ route('frontend.renstra.sasaran.index') }}/" + id : "{{ route('frontend.renstra.sasaran.store') }}";
        var type = id ? "PUT" : "POST";

        var btn = $('#kt_modal_sasaran_submit');
        btn.attr('data-kt-indicator', 'on');
        btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: type,
            data: $('#kt_modal_sasaran_form').serialize(),
            success: function(data) {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
                $('#kt_modal_sasaran').modal('hide');
                table.draw();
                Swal.fire({
                    text: data.success,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, Lanjutkan",
                    customClass: { confirmButton: "btn btn-primary" }
                });
            },
            error: function(data) {
                btn.removeAttr('data-kt-indicator');
                btn.prop('disabled', false);
                var errors = data.responseJSON.errors;
                var errorList = '';
                if (errors) {
                    $.each(errors, function(key, value) {
                        errorList += value + '<br>';
                    });
                } else {
                    errorList = data.responseJSON.message || 'Terjadi kesalahan sistem.';
                }
                Swal.fire({
                    html: errorList,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Tutup",
                    customClass: { confirmButton: "btn btn-danger" }
                });
            }
        });
    }

    function deleteData(id) {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-light"
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('frontend.renstra.sasaran.index') }}/" + id,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(data) {
                        table.draw();
                        Swal.fire({
                            text: data.success,
                            icon: "success",
                            confirmButtonText: "Ok",
                            customClass: { confirmButton: "btn btn-primary" }
                        });
                    }
                });
            }
        });
    }

    function showData(id) {
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + id, function(data) {
            Swal.fire({
                title: "Detail Sasaran Renstra",
                html: `
                    <div class="text-start">
                        <table class="table table-borderless">
                            <tr><td class="fw-bold w-150px">SKPD</td><td>: ${data.skpd.nama_skpd}</td></tr>
                            <tr><td class="fw-bold">Periode</td><td>: ${data.periode.periode}</td></tr>
                            <tr><td class="fw-bold">Sasaran RPJMD</td><td>: ${data.sasaran_rpjmd ? data.sasaran_rpjmd.uraian_sasaran : '-'}</td></tr>
                            <tr><td class="fw-bold">Uraian Sasaran</td><td>: ${data.uraian_sasaranrenstra}</td></tr>
                        </table>
                    </div>
                `,
                icon: "info",
                confirmButtonText: "Tutup",
                customClass: { confirmButton: "btn btn-primary" }
            });
        });
    }
</script>
@endpush
