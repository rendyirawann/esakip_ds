@extends('frontend.layout.app')
@section('title', 'Target PK Perubahan Indikator Program (Tahunan)')
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6"><div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack"><div class="page-title d-flex flex-column justify-content-center flex-wrap me-3"><h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Target PK Perubahan Indikator Program (Tahunan)</h1><ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1"><li class="breadcrumb-item text-muted"><a href="{{ route('frontend.dashboard') }}" class="text-muted text-hover-primary">Dashboard</a></li><li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li><li class="breadcrumb-item text-muted">PK Perubahan</li><li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li><li class="breadcrumb-item text-dark">Indikator Program (Tahunan)</li></ul></div></div></div>
    <div id="kt_app_content" class="app-content flex-column-fluid"><div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card mb-5 shadow-sm"><div class="card-body"><div class="row g-5 align-items-end">
            <div class="col-md-5"><label class="form-label fw-bold fs-6">Pilih SKPD</label><select id="filter_skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD" {{ $isSuperadmin ? '' : 'disabled' }}><option></option>@foreach($skpds as $s)<option value="{{ $s->refskpd_id }}" {{ ($current_skpd && $s->refskpd_id == $current_skpd->refskpd_id) ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label fw-bold fs-6">Pilih Tahun</label><select id="filter_periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun"><option></option>@foreach($periodes as $p)<option value="{{ $p->refperiode_id }}" {{ ($current_periode && $p->refperiode_id == $current_periode->refperiode_id) ? 'selected' : '' }}>{{ $p->periode }}</option>@endforeach</select></div>
            <div class="col-md-3"><button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold no-loader"><span class="indicator-label"><i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data</span><span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span></button></div>
        </div></div></div>
        <div id="empty_state" class="card shadow-sm {{ $current_skpd && $current_periode ? 'd-none' : '' }}"><div class="card-body p-20 text-center"><img src="{{ asset('assets-front/media/illustrations/sigma-1/17.png') }}" class="mw-350px mb-10" alt="Illustration" /><h2 class="fw-bold text-gray-800 mb-3">Pilih SKPD untuk melihat data</h2><p class="text-gray-400 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p></div></div>
        <div id="table_card" class="card card-flush shadow-sm {{ $current_skpd && $current_periode ? '' : 'd-none' }}">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5"><div class="card-title"><div class="d-flex align-items-center position-relative my-1"><i class="ki-outline ki-magnifier fs-3 position-absolute ms-4 text-gray-500"></i><input type="text" id="dt_search" class="form-control form-control-solid w-250px ps-12" placeholder="Cari Data..." /></div></div></div>
            <div class="card-body pt-0"><table id="kt_datatable_pkp" class="table align-middle table-row-dashed fs-6 gy-5"><thead><tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-50px text-center">No</th><th class="d-none">Program</th><th class="min-w-300px">Indikator</th><th class="text-center">Satuan</th><th class="text-center">Target<br>Renstra</th><th class="text-center">Target<br>RKT</th><th class="text-center">Ket<br>RKT</th><th class="text-center">Target<br>PK</th><th class="text-center">Ket<br>PK</th><th class="text-center">Target<br>PK P</th><th class="text-center">Ket<br>PKP</th><th class="text-end min-w-70px">Aksi</th>
            </tr></thead><tbody class="fw-semibold text-gray-600"></tbody></table></div>
        </div>
    </div></div>
</div>
<div class="modal fade" id="modal_edit_pkp" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered mw-650px"><div class="modal-content">
    <div class="modal-header"><h2 class="fw-bold">Update Target PK Perubahan Program</h2><div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-1"></i></div></div>
    <div class="modal-body py-10 px-lg-17"><form id="form_edit_pkp" class="no-loader"><input type="hidden" id="edit_id">
        <div class="fv-row mb-7"><label class="fs-6 fw-semibold mb-2">Indikator Program</label><input type="text" id="edit_indikator" class="form-control form-control-solid" readonly /></div>
        <div class="fv-row mb-7"><label class="fs-6 fw-semibold mb-2">Program</label><input type="text" id="edit_program" class="form-control form-control-solid" readonly /></div>
        <div class="row g-9 mb-7"><div class="col-md-12 fv-row"><label class="required fs-6 fw-semibold mb-2">Target PK Perubahan</label><input type="text" id="edit_target_pk_p" name="target_pk_p" class="form-control form-control-solid" placeholder="Input Target PK Perubahan" required /></div></div>
        <div class="fv-row mb-7"><label class="fs-6 fw-semibold mb-2">Keterangan PK Perubahan</label><textarea id="edit_keterangan_pk_p" name="keterangan_pk_p" class="form-control form-control-solid" rows="3" placeholder="Input Keterangan PK Perubahan"></textarea></div>
        <div class="text-center pt-10"><button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary" id="btn_save_pkp"><span class="indicator-label">Simpan Perubahan</span><span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span></button></div>
    </form></div>
</div></div></div>
@endsection
@push('scripts')
<script>
let dt;
$(document).ready(function() {
    initDataTable();
    $('#btn_tampilkan').on('click', function() { const skpd=$('#filter_skpd_id').val(),periode=$('#filter_periode_id').val(),btn=$(this); if(!skpd||!periode){Swal.fire({text:"Silakan pilih SKPD dan Tahun terlebih dahulu.",icon:"warning"});return;} btn.attr('data-kt-indicator','on').prop('disabled',true); $('#empty_state').addClass('d-none');$('#table_card').removeClass('d-none'); dt.ajax.reload(function(){btn.removeAttr('data-kt-indicator').prop('disabled',false);}); });
    $('#dt_search').on('keyup', function(){dt.search($(this).val()).draw();});
    $('#form_edit_pkp').submit(function(e) { e.preventDefault(); const btn=$('#btn_save_pkp'); btn.attr('data-kt-indicator','on').prop('disabled',true);
        $.ajax({ url:"{{ route('frontend.pkp.program.store') }}",type:"POST",data:{_token:"{{ csrf_token() }}",id:$('#edit_id').val(),target_pk_p:$('#edit_target_pk_p').val(),keterangan_pk_p:$('#edit_keterangan_pk_p').val()},
            success:function(r){$('#modal_edit_pkp').modal('hide');btn.removeAttr('data-kt-indicator').prop('disabled',false);toastr.success(r.message);dt.ajax.reload(null,false);},
            error:function(){btn.removeAttr('data-kt-indicator').prop('disabled',false);Swal.fire('Error!','Gagal menyimpan data.','error');}
        });
    });
});
function initDataTable() {
    dt = $('#kt_datatable_pkp').DataTable({ processing:false,serverSide:true,pageLength:25,
        ajax:{url:"{{ route('frontend.pkp.program.data') }}",data:function(d){d.skpd_id=$('#filter_skpd_id').val();d.periode_id=$('#filter_periode_id').val();}},
        columns:[
            {data:'DT_RowIndex',name:'DT_RowIndex',orderable:false,searchable:false,className:'text-center'},
            {data:'nama_program',name:'nama_program',visible:false},
            {data:'uraian_indikator',name:'uraian_indikator'},
            {data:'satuan',name:'satuan',className:'text-center'},
            {data:'target_renstra',name:'target_renstra',className:'text-center'},
            {data:'target_rkt',name:'target_rkt',className:'text-center text-dark fw-bold'},
            {data:'keterangan_rkt',name:'keterangan_rkt',className:'text-center fs-7'},
            {data:'target_pk_display',name:'target_pk_display',className:'text-center fw-bold text-primary'},
            {data:'keterangan_pk_display',name:'keterangan_pk_display',className:'text-center fs-7'},
            {data:'target_pkp_display',name:'target_pkp_display',className:'text-center fw-bold text-success'},
            {data:'keterangan_pkp_display',name:'keterangan_pkp_display',className:'text-center fs-7'},
            {data:'action',name:'action',orderable:false,searchable:false,className:'text-end'}
        ],order:[[1,'asc']],
        drawCallback:function(){var api=this.api(),rows=api.rows({page:'current'}).nodes(),last=null;api.column(1,{page:'current'}).data().each(function(g,i){if(last!==g){$(rows).eq(i).before('<tr class="group bg-light"><td colspan="11" class="fw-bolder text-gray-800 fs-6 py-3">Program: '+g+'</td></tr>');last=g;}});}
    });
}
function editPkp(id) {
    $.ajax({url:"{{ url('frontend/pkp/program') }}/"+id+"/edit",type:"GET",success:function(d){
        $('#edit_id').val(d.refindikatorprogram_id);$('#edit_indikator').val(d.cascading?d.cascading.uraian_indikatorprogram:'-');$('#edit_program').val(d.program?d.program.nama_program:'-');$('#edit_target_pk_p').val(d.target_pk_p);$('#edit_keterangan_pk_p').val(d.keterangan_pk_p);$('#modal_edit_pkp').modal('show');
    }});
}
</script>
@endpush
