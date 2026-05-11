@extends('frontend.layout.app')

@section('title', 'Indikator Tujuan Renstra')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Indikator Tujuan Renstra</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Renstra</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Indikator Tujuan Renstra</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Filter Data</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Sesuaikan periode dan unit kerja</span>
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="row g-8">
                        <div class="col-lg-5">
                            <label class="fs-6 fw-semibold mb-2">Pilih SKPD</label>
                            <select class="form-select form-select-solid" id="filter_skpd" data-control="select2" data-placeholder="Pilih SKPD" {{ !$isSuperadmin ? 'disabled' : '' }}>
                                <option></option>
                                @foreach($skpds as $skpd)
                                    <option value="{{ $skpd->refskpd_id }}" {{ (isset($current_skpd) && $current_skpd->refskpd_id == $skpd->refskpd_id) ? 'selected' : '' }}>
                                        {{ $skpd->nama_skpd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="fs-6 fw-semibold mb-2">Pilih Periode (Tahun)</label>
                            <select class="form-select form-select-solid" id="filter_periode" data-control="select2" data-placeholder="Pilih Periode">
                                <option></option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}">{{ $p->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="btn_tampilkan">
                                <i class="ki-outline ki-magnifier fs-2"></i> Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="kt_indikator_empty" class="card card-flush py-10">
                <div class="card-body d-flex flex-column flex-center">
                    <div class="mb-10 text-center">
                        <img src="{{ asset('assets/media/illustrations/sketchy-1/17.png') }}" class="mw-100 mh-200px mb-7" alt="" />
                        <h1 class="fw-bold text-gray-800 mb-3">Pilih SKPD untuk melihat data</h1>
                        <div class="text-gray-400 fw-semibold fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</div>
                    </div>
                </div>
            </div>

            <div id="kt_indikator_card" class="card card-flush d-none">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                            <input type="text" data-kt-indikator-table-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari data..." />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_indikator_table">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-10px pe-2">No</th>
                                <th class="min-w-200px">Hierarki</th>
                                <th class="min-w-150px">Sasaran Renstra</th>
                                <th class="min-w-250px">Tujuan Renstra</th>
                                <th class="min-w-300px">Indikator</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Drawer: Manage Indikator -->
<div id="kt_drawer_indikator" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="indikator" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_indikator_toggle" data-kt-drawer-close="#kt_drawer_indikator_close">
    <div class="card shadow-none rounded-0 w-100">
        <div class="card-header" id="kt_drawer_indikator_header">
            <h3 class="card-title fw-bold text-dark" id="drawer_title">Kelola Indikator Tujuan</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_drawer_indikator_close">
                    <i class="ki-outline ki-cross fs-1"></i>
                </button>
            </div>
        </div>
        <div class="card-body position-relative" id="kt_drawer_indikator_body">
            <form id="kt_modal_indikator_form" class="form ajax-form">
                @csrf
                <input type="hidden" name="id" id="ind_id">
                <input type="hidden" name="reftujuanrenstra_id" id="form_tujuan_id">
                <input type="hidden" name="refsasaranrenstra_id" id="form_sasaran_id">
                <input type="hidden" name="refskpd_id" id="form_skpd_id">
                <input type="hidden" name="refperiode_id" id="form_periode_id">

                <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                    <i class="ki-outline ki-information-5 fs-2tx text-warning me-4"></i>
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-semibold">
                            <h4 class="text-gray-900 fw-bold">Tujuan Renstra:</h4>
                            <div class="fs-6 text-gray-700" id="tujuan_text">...</div>
                        </div>
                    </div>
                </div>

                <div class="fv-row mb-7">
                    <label class="required fs-6 fw-semibold mb-2">Uraian Indikator Tujuan</label>
                    <textarea name="uraian_indikatortujuanrenstra" id="form_uraian" class="form-control form-control-solid" rows="4" placeholder="Masukkan uraian indikator..."></textarea>
                </div>

                <div class="text-end pt-10">
                    <button type="button" class="btn btn-light me-3" id="kt_drawer_indikator_close_v2">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kt_modal_indikator_submit">
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
    var datatableInitialized = false;
    var drawerElement = document.querySelector("#kt_drawer_indikator");
    var drawerIndikator = KTDrawer.getInstance(drawerElement);

    function initDataTable() {
        table = $('#kt_indikator_table').DataTable({
            searchDelay: 500,
            processing: false,
            serverSide: true,
            ajax: {
                url: "{{ route('frontend.renstra.indikator-tujuan.index') }}",
                type: "GET",
                data: function (d) {
                    d.periode_id = $('#filter_periode').val();
                    d.skpd_id = $('#filter_skpd').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'hierarki', name: 'hierarki', orderable: false},
                {data: 'sasaran_renstra', name: 'sasaran_renstra'},
                {data: 'tujuan_renstra', name: 'tujuan_renstra'},
                {data: 'indikator_list', name: 'indikator_list', orderable: false, searchable: false}
            ],
            language: {
                emptyTable: "Pilih Periode dan klik Tampilkan Data untuk memuat tabel."
            },
            drawCallback: function(settings) {
                KTMenu.createInstances();
            }
        });

        const filterSearch = document.querySelector('[data-kt-indikator-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            table.search(e.target.value).draw();
        });
    }

    $(document).ready(function() {
        $('#btn_tampilkan').on('click', function() {
            var periode_id = $('#filter_periode').val();
            var skpd_id = $('#filter_skpd').val();

            if (!periode_id || !skpd_id) {
                Swal.fire({ text: "Silakan pilih SKPD dan periode terlebih dahulu.", icon: "warning", confirmButtonText: "Ok" });
                return;
            }

            // Toggle visibility
            $('#kt_indikator_empty').addClass('d-none');
            $('#kt_indikator_card').removeClass('d-none');

            if (!datatableInitialized) {
                initDataTable();
                datatableInitialized = true;
            } else {
                table.draw();
            }
        });

        $('#kt_modal_indikator_form').on('submit', function(e) {
            e.preventDefault();
            submitForm();
        });

        $('#kt_drawer_indikator_close_v2, #kt_drawer_indikator_close').on('click', function() {
            var drawerElement = document.querySelector("#kt_drawer_indikator");
            var drawerIndikator = KTDrawer.getInstance(drawerElement);
            if (drawerIndikator) {
                drawerIndikator.hide();
            }
        });
    });

    function manageIndikator(tujuan_id, sasaran_id) {
        $('#kt_modal_indikator_form')[0].reset();
        $('#ind_id').val('');
        $('#form_tujuan_id').val(tujuan_id);
        $('#form_sasaran_id').val(sasaran_id);
        $('#form_skpd_id').val($('#filter_skpd').val());
        $('#form_periode_id').val($('#filter_periode').val());
        $('#drawer_title').text('Tambah Indikator Tujuan');

        $.get("{{ route('frontend.renstra.tujuan.index') }}/" + tujuan_id, function(data) {
            $('#tujuan_text').text(data.uraian_tujuanrenstra);
            
            var drawerElement = document.querySelector("#kt_drawer_indikator");
            var drawerIndikator = KTDrawer.getInstance(drawerElement);
            if (!drawerIndikator) {
                drawerIndikator = new KTDrawer(drawerElement);
            }
            drawerIndikator.show();
        });
    }

    function editIndikator(id) {
        $('#kt_modal_indikator_form')[0].reset();
        $('#drawer_title').text('Edit Indikator Tujuan');
        
        $.get("{{ route('frontend.renstra.indikator-tujuan.index') }}/" + id + "/edit", function(data) {
            $('#ind_id').val(data.refindikatortujuanrenstra_id);
            $('#form_tujuan_id').val(data.reftujuanrenstra_id);
            $('#form_sasaran_id').val(data.refsasaranrenstra_id);
            $('#form_skpd_id').val(data.refskpd_id);
            $('#form_periode_id').val(data.refperiode_id);
            $('#form_uraian').val(data.uraian_indikatortujuanrenstra);
            
            $.get("{{ route('frontend.renstra.tujuan.index') }}/" + data.reftujuanrenstra_id, function(tujuan) {
                $('#tujuan_text').text(tujuan.uraian_tujuanrenstra);
                
                var drawerElement = document.querySelector("#kt_drawer_indikator");
                var drawerIndikator = KTDrawer.getInstance(drawerElement);
                if (!drawerIndikator) {
                    drawerIndikator = new KTDrawer(drawerElement);
                }
                drawerIndikator.show();
            });
        });
    }

    function submitForm() {
        var id = $('#ind_id').val();
        var url = id ? "{{ route('frontend.renstra.indikator-tujuan.index') }}/" + id : "{{ route('frontend.renstra.indikator-tujuan.store') }}";
        
        var formData = $('#kt_modal_indikator_form').serializeArray();
        if (id) {
            formData.push({ name: '_method', value: 'PUT' });
        }

        var btn = $('#kt_modal_indikator_submit');
        btn.attr('data-kt-indicator', 'on').prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                
                // Paksa tutup semua jenis loader/blockUI yang mungkin nyangkut
                if (typeof KTApp !== 'undefined' && KTApp.unblock) {
                    KTApp.unblock();
                }

                var drawerElement = document.querySelector("#kt_drawer_indikator");
                var drawerIndikator = KTDrawer.getInstance(drawerElement);
                if (drawerIndikator) {
                    drawerIndikator.hide();
                }

                if (table) {
                    table.ajax.reload(function() {
                        Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                    }, false);
                } else {
                    Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                }
            },
            error: function(data) {
                btn.removeAttr('data-kt-indicator').prop('disabled', false);
                
                if (typeof KTApp !== 'undefined' && KTApp.unblock) {
                    KTApp.unblock();
                }
                var errorList = '';
                var errors = data.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) { errorList += value + '<br>'; });
                } else {
                    errorList = data.responseJSON.message || 'Terjadi kesalahan sistem.';
                }
                Swal.fire({ html: errorList, icon: "error", confirmButtonText: "Tutup" });
            }
        });
    }

    function deleteIndikator(id) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data indikator ini akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
            customClass: { confirmButton: "btn btn-danger", cancelButton: "btn btn-light" }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('frontend.renstra.indikator-tujuan.index') }}/" + id,
                    type: "POST",
                    data: { _method: 'DELETE', _token: "{{ csrf_token() }}" },
                    success: function(data) {
                        if (table) {
                            table.ajax.reload(function() {
                                Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                            }, false);
                        } else {
                            Swal.fire({ text: data.success, icon: "success", confirmButtonText: "Ok" });
                        }
                    }
                });
            }
        });
    }
</script>
@endpush
