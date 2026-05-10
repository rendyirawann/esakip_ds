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
                        <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 shadow-sm">
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
                <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/illustrations/sigma-1/15.png" class="h-150px mb-10" alt="Select Data" />
                <h2 class="fw-bold text-gray-800 mb-2">Pilih SKPD untuk melihat data</h2>
                <p class="text-gray-500 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
            </div>
        </div>
        <!--end::Empty State-->

        <div id="data_container" style="display: none;">
            <div class="card border-0 shadow-sm">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3 class="card-label fw-bold">Daftar Sasaran Renstra</h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-success" onclick="addData()">
                        <i class="ki-outline ki-plus fs-2"></i> Tambah Data</button>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Legend-->
                    <div class="notice d-flex rounded border-primary border border-dashed mb-9 p-6" style="background-color: #f8f5ff; border-color: #7239ea !important;">
                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <div class="fs-6 text-gray-700">
                                    <div class="d-flex align-items-center gap-5 flex-wrap">
                                        <div class="d-flex align-items-center gap-2"><i class="ki-outline ki-cloud-change fs-4 text-info"></i><span class="fw-bold text-gray-800">Tautkan Tujuan</span></div>
                                        <div class="d-flex align-items-center gap-2"><i class="ki-outline ki-plus-square fs-4 text-success"></i><span class="fw-bold text-gray-800">Kelola Indikator</span></div>
                                        <div class="d-flex align-items-center gap-2"><i class="ki-outline ki-eye fs-4 text-primary"></i><span class="fw-bold text-gray-800">Detail</span></div>
                                        <div class="d-flex align-items-center gap-2"><i class="ki-outline ki-pencil fs-4 text-warning"></i><span class="fw-bold text-gray-800">Edit</span></div>
                                        <div class="d-flex align-items-center gap-2"><i class="ki-outline ki-trash fs-4 text-danger"></i><span class="fw-bold text-gray-800">Hapus</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_sasaran">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-150px">SKPD</th>
                                <th class="min-w-100px">Periode</th>
                                <th class="min-w-150px">Tujuan RPJMD</th>
                                <th class="min-w-150px">Sasaran RPJMD</th>
                                <th class="min-w-150px">Sasaran Renstra</th>
                                <th class="text-center min-w-150px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Right Drawer: Tambah/Edit Sasaran -->
<div id="kt_drawer_sasaran" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="sasaran" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '550px'}" data-kt-drawer-direction="end">
    <div class="card w-100 rounded-0 shadow-none">
        <div class="card-header pe-5">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900" id="drawer_sasaran_title">Tambah Sasaran Renstra</h3>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_sasaran_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
        </div>
        <div class="card-body hover-scroll-overlay-y">
            <form id="kt_modal_sasaran_form" class="form">
                @csrf
                <input type="hidden" name="id" id="sasaran_id">
                
                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-bold mb-2">SKPD</label>
                    @if($isSuperadmin)
                        <select name="refskpd_id" id="form_skpd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD...">
                            <option></option>
                            @foreach($skpds as $s)
                                <option value="{{ $s->refskpd_id }}">{{ $s->nama_skpd }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control form-control-solid bg-light" value="{{ $current_skpd->nama_skpd ?? '-' }}" readonly />
                        <input type="hidden" name="refskpd_id" value="{{ $current_skpd->refskpd_id ?? '' }}" />
                    @endif
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-bold mb-2">Tahun Periode</label>
                    <select name="refperiode_id" id="form_periode" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode...">
                        <option></option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="dynamic_fields" style="display: none;">
                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Sasaran RPJMD</label>
                        <select name="refsasaran_id" id="form_sasaran_rpjmd" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Sasaran RPJMD...">
                            <option></option>
                        </select>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Uraian Sasaran Renstra</label>
                        <textarea name="uraian_sasaranrenstra" id="form_uraian" class="form-control form-control-solid" rows="4" placeholder="Masukkan uraian sasaran renstra..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer py-5 d-flex justify-content-end">
            <button type="button" class="btn btn-light me-3" id="kt_drawer_sasaran_cancel">Batal</button>
            <button type="button" id="kt_modal_sasaran_submit" class="btn btn-primary" onclick="submitForm()">
                <span class="indicator-label">Simpan</span>
                <span class="indicator-progress">Tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </div>
</div>

<!-- Right Drawer: Tautkan Tujuan Renstra -->
<div id="kt_drawer_link_tujuan" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="link_tujuan" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '550px'}" data-kt-drawer-direction="end">
    <div class="card w-100 rounded-0 shadow-none">
        <div class="card-header pe-5">
            <div class="card-title">
                <h3 class="fw-bold text-gray-900">Tautkan Tujuan Renstra</h3>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_link_tujuan_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="kt_modal_link_tujuan_form" class="form">
                @csrf
                <input type="hidden" id="link_sasaran_id">
                
                <div class="notice d-flex bg-light-info rounded border-info border border-dashed mb-9 p-6">
                    <i class="ki-outline ki-information-5 fs-2tx text-info me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="text-gray-900 fw-bold">Sasaran Renstra:</h4>
                        <div class="fs-6 text-gray-700 fw-semibold" id="link_sasaran_text">Memuat...</div>
                    </div>
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-bold mb-2 text-danger">Pilih Tujuan Renstra *</label>
                    <select name="reftujuanrenstra_id" id="link_tujuan_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tujuan Renstra...">
                        <option></option>
                    </select>
                    <div class="text-muted fs-7 mt-2">Daftar ini diambil dari data yang Anda input di halaman <b>Tujuan Renstra</b>.</div>
                </div>
            </form>
        </div>
        <div class="card-footer py-5 d-flex justify-content-end">
            <button type="button" class="btn btn-light me-3" id="kt_drawer_link_tujuan_cancel">Batal</button>
            <button type="button" id="btn_submit_link" class="btn btn-primary" onclick="submitLinkTujuan()">
                <span class="indicator-label">Simpan Penautan</span>
                <span class="indicator-progress">Tunggu... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
        </div>
    </div>
</div>

<!-- Modal Indikator (Keep as Large Modal for Table space) -->
<div class="modal fade" id="kt_modal_indikator" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Kelola Indikator Sasaran Renstra</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="ki-outline ki-cross fs-1"></i>
                </div>
            </div>
            <div class="modal-body py-10 px-lg-17">
                <div class="alert alert-dismissible bg-light-primary border border-primary border-dashed d-flex flex-column flex-sm-row p-5 mb-10">
                    <i class="ki-outline ki-information-5 fs-2tx text-primary me-4 mb-5 mb-sm-0"></i>
                    <div class="d-flex flex-column pe-0 pe-sm-10">
                        <h5 class="mb-1">Sasaran:</h5>
                        <span id="indikator_sasaran_text" class="fw-bold"></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="card-title fw-bold text-dark">Daftar Indikator</h3>
                    <button type="button" class="btn btn-sm btn-success" onclick="addIndikator()">
                        <i class="ki-outline ki-plus fs-2"></i> Tambah Indikator
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-300 align-middle g-4 gs-0" id="table_indikator_list">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                                <th class="w-50px">No</th>
                                <th class="min-w-200px">Indikator</th>
                                <th class="min-w-100px text-center">Target</th>
                                <th class="min-w-100px text-center">Satuan</th>
                                <th class="min-w-100px text-center">Status</th>
                                <th class="min-w-100px text-center">IKU</th>
                                <th class="min-w-100px text-center">PK</th>
                                <th class="min-w-125px text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Form Indikator -->
                <div id="form_indikator_container" class="mt-10 p-8 border border-dashed rounded" style="display: none; background-color: #f8f9fa;">
                    <h4 class="fw-bold mb-5" id="indikator_form_title">Tambah Indikator</h4>
                    <form id="form_indikator_detail">
                        @csrf
                        <input type="hidden" name="refsasaranrenstra_id" id="ind_sasaran_id">
                        <input type="hidden" name="id" id="ind_id">
                        <div class="row g-9 mb-7">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Uraian Indikator Sasaran Renstra</label>
                                <textarea name="uraian_indikatorsasaranrenstra" id="ind_uraian" class="form-control form-control-solid" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Target (Gunakan titik untuk desimal)</label>
                                <input type="text" name="indikatorsasaranrenstra_target" id="ind_target" class="form-control form-control-solid" placeholder="Contoh: 85.5" />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                                <input type="text" name="indikatorsasaranrenstra_satuan" id="ind_satuan" class="form-control form-control-solid" placeholder="Contoh: %, Orang, Nilai" />
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-semibold mb-2">Keterangan</label>
                            <textarea name="keterangan" id="ind_keterangan" class="form-control form-control-solid" rows="2"></textarea>
                        </div>
                        <div class="row g-9 mb-7 text-center justify-content-center">
                            <div class="col-md-4">
                                <label class="fs-6 fw-semibold mb-2 d-block">Status Aktif</label>
                                <div class="d-flex align-items-center mt-3 justify-content-center">
                                    <label class="form-check form-check-custom form-check-solid me-5"><input class="form-check-input" type="radio" name="indikatorsasaranrenstra_isaktif" value="T" checked /><span class="form-check-label">Aktif</span></label>
                                    <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="radio" name="indikatorsasaranrenstra_isaktif" value="F" /><span class="form-check-label">Tidak</span></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fs-6 fw-semibold mb-2 d-block">Status IKU</label>
                                <div class="d-flex align-items-center mt-3 justify-content-center">
                                    <label class="form-check form-check-custom form-check-solid me-5"><input class="form-check-input" type="radio" name="iku_isaktif" value="T" /><span class="form-check-label">Aktif</span></label>
                                    <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="radio" name="iku_isaktif" value="F" checked /><span class="form-check-label">Tidak</span></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="fs-6 fw-semibold mb-2 d-block">Status PK</label>
                                <div class="d-flex align-items-center mt-3 justify-content-center">
                                    <label class="form-check form-check-custom form-check-solid me-5"><input class="form-check-input" type="radio" name="pk_isaktif" value="T" /><span class="form-check-label">Aktif</span></label>
                                    <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="radio" name="pk_isaktif" value="F" checked /><span class="form-check-label">Tidak</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-light me-3" onclick="$('#form_indikator_container').slideUp()">Batal</button>
                            <button type="button" id="btn_save_indikator" class="btn btn-primary" onclick="submitIndikator()">Simpan Indikator</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer flex-center">
                <button type="button" data-bs-dismiss="modal" class="btn btn-primary px-10">Selesai</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    var table;
    var datatableInitialized = false;
    var drawerSasaran, drawerLink;

    $(document).ready(function() {
        // Initialize Drawers
        drawerSasaran = KTDrawer.getInstance(document.querySelector("#kt_drawer_sasaran"));
        drawerLink = KTDrawer.getInstance(document.querySelector("#kt_drawer_link_tujuan"));

        $('#kt_drawer_sasaran_close, #kt_drawer_sasaran_cancel').on('click', function() { drawerSasaran.hide(); });
        $('#kt_drawer_link_tujuan_close, #kt_drawer_link_tujuan_cancel').on('click', function() { drawerLink.hide(); });

        $('#btn_tampilkan').on('click', function() {
            var periode_id = $('#filter_periode').val();
            if (!periode_id) {
                Swal.fire({ text: "Silakan pilih Periode (Tahun) terlebih dahulu.", icon: "warning", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                return;
            }
            $('#empty_state').hide();
            $('#data_container').fadeIn();
            if (!datatableInitialized) { initDataTable(); datatableInitialized = true; } else { table.draw(); }
        });

        $('#form_periode').on('change', function() {
            var periode_id = $(this).val();
            if (periode_id) { $('#dynamic_fields').fadeIn(); loadSasaranRpjmd(periode_id); } else { $('#dynamic_fields').fadeOut(); }
        });

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    });

    function initDataTable() {
        table = $('#kt_table_sasaran').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.sasaran.index') }}",
                type: "GET",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'nama_skpd', name: 'nama_skpd'},
                {data: 'periode.periode', name: 'periode.periode'},
                {data: 'tujuan_renstra', name: 'tujuan_renstra'},
                {data: 'sasaran_rpjmd', name: 'sasaran_rpjmd'},
                {data: 'uraian_sasaranrenstra', name: 'uraian_sasaranrenstra'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center'},
            ],
            drawCallback: function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
            }
        });
    }

    function addData() {
        $('#kt_modal_sasaran_form')[0].reset();
        $('#sasaran_id').val('');
        $('#drawer_sasaran_title').text('Tambah Sasaran Renstra');
        $('#form_periode').val(null).trigger('change');
        @if($isSuperadmin)
            var filter_skpd = $('#filter_skpd').val();
            if (filter_skpd) $('#form_skpd').val(filter_skpd).trigger('change');
            else $('#form_skpd').val(null).trigger('change');
        @endif
        drawerSasaran.show();
    }

    function editData(id) {
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + id + "/edit", function(data) {
            $('#drawer_sasaran_title').text('Edit Sasaran Renstra');
            $('#sasaran_id').val(data.refsasaranrenstra_id);
            $('#form_uraian').val(data.uraian_sasaranrenstra);
            @if($isSuperadmin) $('#form_skpd').val(data.refskpd_id).trigger('change'); @endif
            $('#form_periode').val(data.refperiode_id).trigger('change');
            setTimeout(() => { loadSasaranRpjmd(data.refperiode_id, data.refsasaran_id); }, 500);
            drawerSasaran.show();
        });
    }

    function submitForm() {
        var id = $('#sasaran_id').val();
        var url = id ? "{{ route('frontend.renstra.sasaran.index') }}/" + id : "{{ route('frontend.renstra.sasaran.store') }}";
        
        // Use POST + _method for stability in Laravel AJAX
        var formData = $('#kt_modal_sasaran_form').serializeArray();
        if (id) {
            formData.push({ name: '_method', value: 'PUT' });
        }

        var btn = $('#kt_modal_sasaran_submit');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                drawerSasaran.hide();
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
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                var errorList = '';
                var errors = data.responseJSON.errors;
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

    function linkTujuan(sasaran_id) {
        $('#link_sasaran_id').val(sasaran_id);
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + sasaran_id, function(data) {
            $('#link_sasaran_text').text(data.uraian_sasaranrenstra);
            
            // Set current selected value
            var current_id = data.reftujuanrenstra_id;

            $.ajax({
                url: "{{ url('frontend/renstra/sasaran/get-tujuan-renstra') }}/" + data.refskpd_id + "/" + data.refperiode_id,
                type: "GET",
                success: function(tujuan_data) {
                    var options = '<option></option>';
                    $.each(tujuan_data, function(k, v) {
                        options += '<option value="' + v.reftujuanrenstra_id + '" ' + (current_id == v.reftujuanrenstra_id ? 'selected' : '') + '>' + v.uraian_tujuanrenstra + '</option>';
                    });
                    $('#link_tujuan_id').html(options).trigger('change');
                }
            });
        });
        drawerLink.show();
    }

    function submitLinkTujuan() {
        var id = $('#link_sasaran_id').val();
        var btn = $('#btn_submit_link');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);
        $.ajax({
            url: "{{ url('frontend/renstra/sasaran/link-tujuan') }}/" + id,
            type: "POST", data: $('#kt_modal_link_tujuan_form').serialize(),
            success: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                drawerLink.hide(); table.draw();
                Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
            },
            error: function() {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                Swal.fire({ text: "Gagal mentautkan.", icon: "error", confirmButtonText: "Tutup", customClass: { confirmButton: "btn btn-danger" } });
            }
        });
    }

    function loadSasaranRpjmd(periode_id, selected_id = null) {
        $.ajax({
            url: "{{ url('frontend/renstra/sasaran/get-sasaran-rpjmd') }}/" + periode_id,
            type: "GET",
            success: function(data) {
                var options = '<option></option>';
                $.each(data, function(k, v) { options += '<option value="' + v.refsasaran_id + '" ' + (selected_id == v.refsasaran_id ? 'selected' : '') + '>' + v.uraian_sasaran + '</option>'; });
                $('#form_sasaran_rpjmd').html(options).trigger('change');
            }
        });
    }

    function deleteData(id) {
        Swal.fire({ title: "Hapus?", icon: "warning", showCancelButton: true, confirmButtonText: "Hapus" }).then(function(r) {
            if (r.value) $.ajax({ url: "{{ route('frontend.renstra.sasaran.index') }}/" + id, type: "DELETE", success: function(d) { table.draw(); Swal.fire({ text: d.success, icon: "success" }); } });
        });
    }

    function showData(id) {
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + id, function(data) {
            Swal.fire({
                title: "Detail Sasaran Renstra",
                html: `<div class="text-start"><table class="table table-borderless">
                    <tr><td class="fw-bold w-150px">SKPD</td><td>: ${data.skpd ? data.skpd.nama_skpd : '-'}</td></tr>
                    <tr><td class="fw-bold">Periode</td><td>: ${data.periode ? data.periode.periode : '-'}</td></tr>
                    <tr><td class="fw-bold">Tujuan RPJMD</td><td>: ${data.tujuan_rpjmd ? data.tujuan_rpjmd.uraian_tujuan : '-'}</td></tr>
                    <tr><td class="fw-bold">Tautan Renstra</td><td class="text-success fw-bold">: ${data.linked_tujuan_renstra ? data.linked_tujuan_renstra.uraian_tujuanrenstra : '<span class="text-muted">Belum ditautkan</span>'}</td></tr>
                    <tr><td class="fw-bold">Sasaran RPJMD</td><td>: ${data.sasaran_rpjmd ? data.sasaran_rpjmd.uraian_sasaran : '-'}</td></tr>
                    <tr><td class="fw-bold">Uraian Sasaran</td><td>: ${data.uraian_sasaranrenstra}</td></tr>
                </table></div>`,
                icon: "info", confirmButtonText: "Tutup", customClass: { confirmButton: "btn btn-primary" }
            });
        });
    }

    // --- Indikator Functions ---
    function manageIndikator(sid) {
        $('#ind_sasaran_id').val(sid); $('#form_indikator_container').hide();
        $.get("{{ route('frontend.renstra.sasaran.index') }}/" + sid, function(d) { $('#indikator_sasaran_text').text(d.uraian_sasaranrenstra); });
        loadIndikatorList(sid); $('#kt_modal_indikator').modal('show');
    }
    function loadIndikatorList(sid) {
        $.get("{{ url('frontend/renstra/sasaran') }}/" + sid + "/indikators", function(d) {
            var html = '';
            if (d.length == 0) html = '<tr><td colspan="8" class="text-center text-muted py-10">Belum ada indikator.</td></tr>';
            else $.each(d, function(i, item) {
                html += `<tr><td>${i + 1}</td><td>${item.uraian_indikatorsasaranrenstra}</td><td class="text-center fw-bold">${item.indikatorsasaranrenstra_target}</td><td class="text-center">${item.indikatorsasaranrenstra_satuan}</td><td class="text-center"><span class="badge badge-light-${item.indikatorsasaranrenstra_isaktif == 'T' ? 'success' : 'danger'}">${item.indikatorsasaranrenstra_isaktif == 'T' ? 'Aktif' : 'Nonaktif'}</span></td><td class="text-center"><span class="badge badge-light-${item.iku_isaktif == 'T' ? 'primary' : 'secondary'}">${item.iku_isaktif == 'T' ? 'IKU' : '-'}</span></td><td class="text-center"><span class="badge badge-light-${item.pk_isaktif == 'T' ? 'info' : 'secondary'}">${item.pk_isaktif == 'T' ? 'PK' : '-'}</span></td><td class="text-center"><button type="button" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" onclick="editIndikator(${item.refindikatorsasaranrenstra_id})"><i class="ki-outline ki-pencil fs-3"></i></button><button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" onclick="deleteIndikator(${item.refindikatorsasaranrenstra_id})"><i class="ki-outline ki-trash fs-3"></i></button></td></tr>`;
            });
            $('#table_indikator_list tbody').html(html);
        });
    }
    function addIndikator() { $('#form_indikator_detail')[0].reset(); $('#ind_id').val(''); $('#indikator_form_title').text('Tambah Indikator'); $('#form_indikator_container').slideDown(); }
    function editIndikator(id) {
        $.get("{{ url('frontend/renstra/sasaran/indikator') }}/" + id + "/edit", function(d) {
            $('#ind_id').val(d.refindikatorsasaranrenstra_id); $('#ind_uraian').val(d.uraian_indikatorsasaranrenstra); $('#ind_target').val(d.indikatorsasaranrenstra_target); $('#ind_satuan').val(d.indikatorsasaranrenstra_satuan); $('#ind_keterangan').val(d.keterangan);
            $(`input[name="indikatorsasaranrenstra_isaktif"][value="${d.indikatorsasaranrenstra_isaktif}"]`).prop('checked', true);
            $(`input[name="iku_isaktif"][value="${d.iku_isaktif}"]`).prop('checked', true);
            $(`input[name="pk_isaktif"][value="${d.pk_isaktif}"]`).prop('checked', true);
            $('#indikator_form_title').text('Edit Indikator'); $('#form_indikator_container').slideDown();
        });
    }
    function submitIndikator() {
        var sid = $('#ind_sasaran_id').val(); var id = $('#ind_id').val();
        $.ajax({
            url: id ? "{{ url('frontend/renstra/sasaran/indikator') }}/" + id : "{{ route('frontend.renstra.sasaran.indikator.store') }}",
            type: id ? "PUT" : "POST", data: $('#form_indikator_detail').serialize(),
            success: function(d) { $('#form_indikator_container').slideUp(); loadIndikatorList(sid); Swal.fire({ text: d.success, icon: "success" }); }
        });
    }
    function deleteIndikator(id) {
        Swal.fire({ title: "Hapus?", icon: "warning", showCancelButton: true }).then(function(r) {
            if (r.value) $.ajax({ url: "{{ url('frontend/renstra/sasaran/indikator') }}/" + id, type: "DELETE", data: { _token: "{{ csrf_token() }}" }, success: function(d) { loadIndikatorList($('#ind_sasaran_id').val()); Swal.fire({ text: d.success, icon: "success" }); } });
        });
    }
</script>
@endpush
