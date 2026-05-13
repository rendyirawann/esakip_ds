@extends('frontend.layout.app')
@section('title', 'Capaian Kinerja - Realisasi Sub Kegiatan (Tahunan)')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Realisasi Sub Kegiatan (Tahunan)</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted"><a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-muted">Capaian Kinerja</li>
                    <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                    <li class="breadcrumb-item text-dark">Realisasi Sub Kegiatan Tahunan</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @include('frontend.capaian._filter_tahunan')
            <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}">
                <div class="card-body p-20 text-center"><img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="">
                    <h2 class="fw-bold text-gray-800 mb-3">Pilih Filter untuk Melihat Data</h2><p class="text-gray-400 fs-5">Silakan pilih SKPD dan tahun periode.</p></div></div>
            <div id="table_card" class="card card-flush shadow-sm {{ $current_skpd && $current_periode ? '' : 'd-none' }}">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5"><div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1"><i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i>
                        <input type="text" id="dt_search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data..." /></div></div></div>
                <div class="card-body pt-0"><table id="kt_datatable_capaian" class="table align-middle table-row-dashed fs-6 gy-5"><thead>
                    <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                        <th class="w-50px text-center">No</th><th class="d-none">Sub Kegiatan</th><th class="min-w-250px">Indikator</th><th class="text-center">Satuan</th>
                        <th class="text-center">Target RKT</th><th class="text-center">Target PK</th><th class="text-center">Realisasi</th>
                        <th class="text-center">Capaian</th><th class="text-center min-w-200px">Analisis</th><th class="text-end min-w-70px">Aksi</th>
                    </tr></thead><tbody class="fw-semibold text-gray-600"></tbody></table></div>
            </div>
        </div>
    </div>
</div>
@include('frontend.capaian._modal_tahunan')
@endsection
@push('scripts')
<script>
let dt;
$(document).ready(function() {
    initDataTable();
    $('#btn_tampilkan').on('click', function() {
        var skpd = @if($isSuperadmin) $('#filter_skpd_id').val() @else '{{ optional($current_skpd)->refskpd_id }}' @endif;
        var periode = $('#filter_periode_id').val(), btn = $(this);
        if (!skpd||!periode) { Swal.fire({text:"Silakan pilih filter terlebih dahulu.",icon:"warning"}); return; }
        btn.attr('data-kt-indicator','on').prop('disabled',true); $('#empty_state').addClass('d-none'); $('#table_card').removeClass('d-none');
        dt.ajax.reload(function(){ btn.removeAttr('data-kt-indicator').prop('disabled',false); });
    });
    $('#dt_search').on('keyup', function(){ dt.search($(this).val()).draw(); });
    $(document).on('click','.btn-edit', function(){ var id=$(this).data('id');
        $.ajax({ url:"{{ url('frontend/capaian/realisasi-subkegiatan/tahunan') }}/"+id+"/edit", success: function(res){
            $('#modal_form_content').html(res.html); $('#modal_edit_capaian').modal('show');
            $(document).off('input','#modal_realisasi').on('input','#modal_realisasi', function(){ var t=parseFloat($('#modal_target_pk').val())||0, r=parseFloat($(this).val())||0; $('#modal_capaian').val(t>0&&r>=0?((r/t)*100).toFixed(2):''); });
        }});
    });
    $('#form_edit_capaian').submit(function(e){ e.preventDefault(); var btn=$('#btn_save_capaian');
        btn.attr('data-kt-indicator','on').prop('disabled',true);
        $.ajax({ url:"{{ route('frontend.capaian.realisasi-subkegiatan.tahunan.store') }}", type:"POST",
            data:{_token:"{{ csrf_token() }}",id:$('[name="id"]').val(),realisasi:$('[name="realisasi"]').val(),capaian:$('[name="capaian"]').val(),analisis:$('[name="analisis"]').val()},
            success:function(res){$('#modal_edit_capaian').modal('hide');btn.removeAttr('data-kt-indicator').prop('disabled',false);toastr.success(res.message);dt.ajax.reload(null,false);},
            error:function(){btn.removeAttr('data-kt-indicator').prop('disabled',false);Swal.fire('Error!','Gagal menyimpan data.','error');}
        });
    });
});
function initDataTable(){
    dt=$('#kt_datatable_capaian').DataTable({processing:false,serverSide:true,pageLength:25,
        ajax:{url:"{{ route('frontend.capaian.realisasi-subkegiatan.tahunan.data') }}",data:function(d){
            d.skpd_id=@if($isSuperadmin) $('#filter_skpd_id').val() @else '{{ optional($current_skpd)->refskpd_id }}' @endif; d.periode_id=$('#filter_periode_id').val();
        }},
        columns:[
            {data:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},{data:'uraian_subkegiatan',visible:false},{data:'indikator'},{data:'satuan',className:'text-center'},
            {data:'target_tahunan',className:'text-center'},{data:'target_pk',className:'text-center fw-bold text-primary'},{data:'realisasi_display',className:'text-center fw-bold'},
            {data:'capaian_display',className:'text-center',orderable:false},{data:'analisis',className:'fs-7'},{data:'action',orderable:false,searchable:false,className:'text-end'}
        ],order:[[1,'asc']],
        drawCallback:function(){var api=this.api(),rows=api.rows({page:'current'}).nodes(),last=null;api.column(1,{page:'current'}).data().each(function(g,i){if(last!==g){$(rows).eq(i).before('<tr class="group bg-light"><td colspan="10" class="fw-bolder text-gray-800 fs-6 py-3 ps-4">Sub Kegiatan: '+g+'</td></tr>');last=g;}});}
    });
}
</script>
@endpush
