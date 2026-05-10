@extends('frontend.layout.app')

@section('title', 'Tujuan Renstra')

@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            
            <!-- Filter Card -->
            <div class="card card-flush mb-5 shadow-sm">
                <div class="card-header pt-7">
                    <div class="card-title flex-column">
                        <h3 class="fw-bold">Filter Data</h3>
                        <span class="text-muted mt-1 fw-semibold fs-7">Sesuaikan periode dan unit kerja</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row fv-row mb-0">
                        @if ($isSuperadmin)
                            <div class="col-md-5 mb-5">
                                <label class="fs-6 fw-bold mb-2">Pilih SKPD</label>
                                <select class="form-select form-select-solid" id="filter_skpd" data-control="select2" data-placeholder="Pilih SKPD...">
                                    <option></option>
                                    @foreach ($skpds as $s)
                                        <option value="{{ $s->refskpd_id }}">{{ $s->nama_skpd }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-{{ $isSuperadmin ? '4' : '9' }} mb-5">
                            <label class="fs-6 fw-bold mb-2">Pilih Periode (Tahun)</label>
                            <select class="form-select form-select-solid" id="filter_periode" data-control="select2" data-placeholder="Pilih Periode...">
                                <option></option>
                                @foreach ($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-5 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="btn_filter" onclick="loadTable()">
                                <i class="ki-outline ki-magnifier fs-2 me-2"></i> Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State Card -->
            <div class="card card-flush mb-5" id="kt_empty_state">
                <div class="card-body d-flex flex-column flex-center py-20">
                    <div class="mb-10 text-center">
                        <img src="https://preview.keenthemes.com/metronic8/demo1/assets/media/illustrations/sigma-1/15.png" class="mw-250px" alt=""/>
                        <h1 class="fw-bold text-gray-900 mt-5">Pilih SKPD untuk melihat data</h1>
                        <p class="fw-semibold fs-6 text-gray-500 mt-2">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
                    </div>
                </div>
            </div>

            <!-- List Card (Hidden by default) -->
            <div class="card card-flush" id="kt_list_card" style="display: none;">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <h3 class="fw-bold">Daftar Tujuan Renstra</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <!-- Legend Box -->
                    <div class="notice d-flex rounded border-primary border border-dashed mb-9 p-6" style="background-color: #f8f5ff; border-color: #7239ea !important;">
                        <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-semibold">
                                <div class="fs-6 text-gray-700">
                                    <div class="d-flex align-items-center gap-5 flex-wrap">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ki-outline ki-pencil fs-4 text-warning"></i>
                                            <span class="fw-bold text-gray-800">Edit</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ki-outline ki-trash fs-4 text-danger"></i>
                                            <span class="fw-bold text-gray-800">Hapus</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ki-outline ki-plus fs-4 text-success"></i>
                                            <span class="fw-bold text-gray-800">Tambah</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_tujuan_table">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-150px">Misi</th>
                                <th class="min-w-150px">Tujuan RPJMD</th>
                                <th class="min-w-150px">Sasaran Renstra</th>
                                <th class="min-w-200px">Uraian Tujuan Renstra</th>
                                <th class="text-center min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Modal (Drawer) -->
    <div id="kt_drawer_tujuan" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="tujuan" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '550px'}" data-kt-drawer-direction="end">
        <div class="card w-100 rounded-0">
            <div class="card-header pe-5">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 lh-1" id="drawer_title">Tambah Tujuan Renstra</a>
                    </div>
                </div>
                <div class="card-toolbar">
                    <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_drawer_tujuan_close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
            </div>
            <div class="card-body hover-scroll-overlay-y">
                <form id="kt_modal_tujuan_form" class="form">
                    <input type="hidden" name="reftujuanrenstra_id" id="tujuan_id">
                    <input type="hidden" name="refsasaranrenstra_id" id="form_refsasaranrenstra_id">
                    
                    <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                        <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                        <div class="d-flex flex-column">
                            <h4 class="text-gray-900 fw-bold">Informasi Sasaran</h4>
                            <div class="fs-6 text-gray-700 fw-semibold" id="info_sasaran_text">
                                Memuat informasi sasaran...
                            </div>
                        </div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Uraian Tujuan Renstra</label>
                        <textarea class="form-control form-control-solid" rows="8" name="uraian_tujuanrenstra" id="form_uraian" placeholder="Masukkan Uraian Tujuan Renstra"></textarea>
                    </div>
                </form>
            </div>
            <div class="card-footer py-5 d-flex justify-content-end">
                <button type="button" class="btn btn-light me-3" id="kt_drawer_tujuan_cancel">Batal</button>
                <button type="submit" id="kt_modal_tujuan_submit" class="btn btn-primary" onclick="saveData()">
                    <span class="indicator-label">Simpan Data</span>
                    <span class="indicator-progress">Mohon tunggu...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var table;
        var tableInitialized = false;
        var drawerElement = document.querySelector("#kt_drawer_tujuan");
        var drawerHandle;

        function loadTable() {
            var periode_id = $('#filter_periode').val();
            var skpd_id = $('#filter_skpd').val();

            if (!periode_id) {
                Swal.fire({ text: "Silahkan pilih periode terlebih dahulu!", icon: "warning", confirmButtonText: "Ok" });
                return;
            }

            @if ($isSuperadmin)
                if (!skpd_id) {
                    Swal.fire({ text: "Silahkan pilih SKPD terlebih dahulu!", icon: "warning", confirmButtonText: "Ok" });
                    return;
                }
            @endif

            $('#kt_empty_state').hide();
            $('#kt_list_card').fadeIn();

            if (tableInitialized) {
                table.ajax.reload();
            } else {
                initTable();
            }
        };

        function initTable() {
            table = $('#kt_tujuan_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('frontend.renstra.tujuan.get-data') }}",
                    type: 'POST',
                    data: function(d) {
                        d.periode_id = $('#filter_periode').val();
                        d.skpd_id = $('#filter_skpd').val();
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'row_no', name: 'row_no', orderable: false, searchable: false },
                    { 
                        data: 'misi', 
                        name: 'misi',
                        render: function(data) {
                            if (!data || data === '') return '';
                            return '<div class="d-flex flex-column">' +
                                   '<span class="fw-bold text-primary fs-8 text-uppercase mb-1"><i class="fas fa-flag-checkered me-1 text-primary"></i> MISI:</span>' +
                                   '<span class="text-gray-800 fw-semibold fs-7">' + data + '</span>' +
                                   '</div>';
                        }
                    },
                    { 
                        data: 'tujuan_rpjmd', 
                        name: 'tujuan_rpjmd',
                        render: function(data) {
                            if (!data || data === '') return '';
                            return '<div class="d-flex flex-column">' +
                                   '<span class="fw-bold text-info fs-8 text-uppercase mb-1"><i class="fas fa-crosshairs me-1 text-info"></i> TUJUAN:</span>' +
                                   '<span class="text-gray-700 fs-7">' + data + '</span>' +
                                   '</div>';
                        }
                    },
                    { 
                        data: 'sasaran_renstra', 
                        name: 'sasaran_renstra',
                        render: function(data) {
                            if (!data || data === '') return '';
                            return '<div class="d-flex flex-column">' +
                                   '<span class="fw-bold text-success fs-8 text-uppercase mb-1"><i class="fas fa-rocket me-1 text-success"></i> SASARAN:</span>' +
                                   '<span class="text-gray-900 fw-bold fs-7">' + data + '</span>' +
                                   '</div>';
                        }
                    },
                    { data: 'uraian_tujuanrenstra', name: 'uraian_tujuanrenstra' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center' },
                ]
            });

            tableInitialized = true;
        };

        function addData(sasaran_id) {
            $('#kt_modal_tujuan_form')[0].reset();
            $('#tujuan_id').val('');
            $('#form_refsasaranrenstra_id').val(sasaran_id);
            $('#drawer_title').text('Tambah Tujuan Renstra');
            
            var rowData = table.rows().data().toArray().find(r => r.refsasaranrenstra_id == sasaran_id);
            if(rowData) {
                $('#info_sasaran_text').html('Sasaran: <b>' + rowData.sasaran_renstra + '</b>');
            }

            drawerHandle.show();
        };

        function editData(id) {
            $.get("{{ route('frontend.renstra.tujuan.index') }}/" + id + "/edit", function(data) {
                $('#drawer_title').text('Edit Tujuan Renstra');
                $('#tujuan_id').val(data.reftujuanrenstra_id);
                $('#form_refsasaranrenstra_id').val(data.refsasaranrenstra_id);
                $('#form_uraian').val(data.uraian_tujuanrenstra);
                
                if(data.sasaran_renstra) {
                    $('#info_sasaran_text').html('Sasaran: <b>' + data.sasaran_renstra.uraian_sasaranrenstra + '</b>');
                }

                drawerHandle.show();
            });
        };

        function saveData() {
            var btn = document.querySelector("#kt_modal_tujuan_submit");
            var form = $('#kt_modal_tujuan_form');
            var url = "{{ route('frontend.renstra.tujuan.store') }}";

            if(!$('#form_uraian').val()) {
                Swal.fire({ text: "Uraian Tujuan Renstra tidak boleh kosong!", icon: "error", confirmButtonText: "Ok" });
                return;
            }

            btn.setAttribute("data-kt-indicator", "on");
            btn.disabled = true;

            $.ajax({
                url: url,
                type: "POST",
                data: form.serialize() + "&_token={{ csrf_token() }}",
                success: function(data) {
                    btn.removeAttribute("data-kt-indicator");
                    btn.disabled = false;
                    drawerHandle.hide();
                    if (tableInitialized) table.draw();
                    Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                },
                error: function(xhr) {
                    btn.removeAttribute("data-kt-indicator");
                    btn.disabled = false;
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';
                    $.each(errors, function(key, value) { errorMessages += value + '<br>'; });
                    Swal.fire({ html: errorMessages, icon: "error", confirmButtonText: "Ok" });
                }
            });
        };

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
                        url: "{{ route('frontend.renstra.tujuan.index') }}/" + id,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function(data) {
                            if (tableInitialized) table.draw();
                            Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok", customClass: { confirmButton: "btn btn-primary" } });
                        }
                    });
                }
            });
        };

        $(document).ready(function() {
            // Initialize Metronic Drawer
            drawerHandle = KTDrawer.getInstance(drawerElement);
            
            $('#kt_drawer_tujuan_close, #kt_drawer_tujuan_cancel').on('click', function() {
                drawerHandle.hide();
            });

            $('body').tooltip({ selector: '[data-bs-toggle="tooltip"]' });
            
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });
    </script>
@endpush
