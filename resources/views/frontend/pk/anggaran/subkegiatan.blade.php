@extends('frontend.layout.app')

@section('title', 'Anggaran Sub Kegiatan PK')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Anggaran Sub Kegiatan PK</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">PK</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Anggaran Sub Kegiatan</li>
                </ul>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <!-- Filter Card -->
            <div class="card mb-5 shadow-sm">
                <div class="card-body">
                    <div class="row g-5 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label fw-bold fs-6">Pilih SKPD</label>
                            <select id="filter_skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD" {{ $isSuperadmin ? '' : 'disabled' }}>
                                <option></option>
                                @foreach($skpds as $s)
                                    <option value="{{ $s->refskpd_id }}" {{ ($current_skpd && $s->refskpd_id == $current_skpd->refskpd_id) ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold fs-6">Pilih Tahun</label>
                            <select id="filter_periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun">
                                <option></option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->refperiode_id }}" {{ ($current_periode && $p->refperiode_id == $current_periode->refperiode_id) ? 'selected' : '' }}>{{ $p->periode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold">
                                <i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}">
                <div class="card-body p-20 text-center">
                    <img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="Illustration" />
                    <h2 class="fw-bold text-gray-800 mb-3">Pilih SKPD untuk melihat data</h2>
                    <p class="text-gray-400 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
                </div>
            </div>

            <!-- Table Card -->
            <div id="table_card" class="card card-flush shadow-sm {{ $current_skpd && $current_periode ? '' : 'd-none' }}">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                            <input type="text" id="dt_search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data..." />
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <table id="kt_datatable_anggaran" class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="w-50px text-center">No</th>
                                <th class="d-none">Kegiatan</th>
                                <th class="min-w-250px">Sub Kegiatan</th>
                                <th class="text-end">Anggaran Renstra</th>
                                <th class="text-end min-w-200px">Anggaran PK</th>
                                <th class="text-center w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let dt;

    $(document).ready(function() {
        initDataTable();

        $('#btn_tampilkan').on('click', function() {
            const skpd = $('#filter_skpd_id').val();
            const periode = $('#filter_periode_id').val();

            if (!skpd || !periode) {
                Swal.fire({ text: "Silakan pilih SKPD dan Tahun terlebih dahulu.", icon: "warning" });
                return;
            }

            $('#empty_state').addClass('d-none');
            $('#table_card').removeClass('d-none');
            dt.ajax.reload();
        });

        $('#dt_search').on('keyup', function() {
            dt.search($(this).val()).draw();
        });

        // Toggle Edit Mode
        $(document).on('click', '.btn-edit-anggaran', function() {
            const btn = $(this);
            const id = btn.data('id');
            const input = $(`.input-anggaran-pk[data-id="${id}"]`);
            
            input.prop('readonly', false).removeClass('bg-light').focus();
            
            const wrapper = btn.parent();
            wrapper.html(`
                <button type="button" class="btn btn-icon btn-light-success btn-sm btn-save-anggaran me-1" data-id="${id}" title="Simpan">
                    <i class="ki-outline ki-check fs-2"></i>
                </button>
                <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-cancel-anggaran" data-id="${id}" title="Batal">
                    <i class="ki-outline ki-cross fs-2"></i>
                </button>
            `);
        });

        // Cancel Edit
        $(document).on('click', '.btn-cancel-anggaran', function() {
            const btn = $(this);
            const id = btn.data('id');
            const input = $(`.input-anggaran-pk[data-id="${id}"]`);
            
            input.val(input.data('original')).prop('readonly', true).addClass('bg-light');
            
            const wrapper = btn.parent();
            wrapper.html(`
                <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit-anggaran" data-id="${id}" title="Edit Anggaran">
                    <i class="ki-outline ki-pencil fs-2"></i>
                </button>
            `);
        });

        // Save Anggaran
        $(document).on('click', '.btn-save-anggaran', function() {
            const btn = $(this);
            const id = btn.data('id');
            const input = $(`.input-anggaran-pk[data-id="${id}"]`);
            const val = input.val();

            btn.attr('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: "{{ route('frontend.pk.anggaran-subkegiatan.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    anggaran: val
                },
                success: function(res) {
                    toastr.success(res.message);
                    input.data('original', val).prop('readonly', true).addClass('bg-light');
                    btn.parent().html(`<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit-anggaran" data-id="${id}" title="Edit Anggaran"><i class="ki-outline ki-pencil fs-2"></i></button>`);
                },
                error: function(err) {
                    btn.attr('disabled', false).html('<i class="ki-outline ki-check fs-2"></i>');
                    Swal.fire('Error!', 'Gagal menyimpan data.', 'error');
                }
            });
        });
    });

    function initDataTable() {
        dt = $('#kt_datatable_anggaran').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 25,
            ajax: {
                url: "{{ route('frontend.pk.anggaran-subkegiatan.data') }}",
                data: function(d) {
                    d.skpd_id = $('#filter_skpd_id').val();
                    d.periode_id = $('#filter_periode_id').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'nama_kegiatan', name: 'nama_kegiatan', visible: false },
                { data: 'nama_subkegiatan', name: 'nama_subkegiatan' },
                { data: 'anggaran_renstra', name: 'anggaran_renstra', className: 'text-end' },
                { data: 'anggaran_pk_input', name: 'anggaran_pk_input', className: 'text-end', orderable: false, searchable: false },
                { 
                    data: null, 
                    className: 'text-center',
                    orderable: false, 
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div class="btn-group-wrapper">
                                    <button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit-anggaran" data-id="${row.refindikatorsubkegiatan_id}" title="Edit Anggaran">
                                        <i class="ki-outline ki-pencil fs-2"></i>
                                    </button>
                                </div>`;
                    }
                }
            ],
            order: [[1, 'asc']],
            drawCallback: function(settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(1, { page: 'current' }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group bg-light"><td colspan="5" class="fw-bolder text-gray-800 fs-6 py-3">Kegiatan: ' + group + '</td></tr>'
                        );
                        last = group;
                    }
                });
            }
        });
    }

    function formatRupiahInput(obj) {
        let value = obj.value.replace(/[^0-9]/g, '');
        if (value === "") {
            obj.value = "0";
            return;
        }
        obj.value = new Intl.NumberFormat('id-ID').format(value);
    }
</script>
@endpush
