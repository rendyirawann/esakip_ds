@extends('frontend.layout.app')

@section('title', 'Edit Sasaran Renstra')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h2>Edit Sasaran Renstra</h2>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('frontend.renstra.sasaran.update', $sasaran->refsasaranrenstra_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">SKPD</label>
                <input type="text" class="form-control form-control-solid" value="{{ $sasaran->skpd->nama_skpd }}" readonly />
                <input type="hidden" name="refskpd_id" value="{{ $sasaran->refskpd_id }}" />
            </div>

            <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Tahun Periode</label>
                <select name="refperiode_id" id="edit_periode" class="form-select form-select-solid" data-control="select2">
                    @foreach($periodes as $p)
                        <option value="{{ $p->refperiode_id }}" {{ $sasaran->refperiode_id == $p->refperiode_id ? 'selected' : '' }}>{{ $p->periode }}</option>
                    @endforeach
                </select>
            </div>

            <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Sasaran RPJMD</label>
                <select name="refsasaran_id" id="edit_sasaran_rpjmd" class="form-select form-select-solid" data-control="select2">
                    @foreach($sasaran_rpjmds as $s)
                        <option value="{{ $s->refsasaran_id }}" {{ $sasaran->refsasaran_id == $s->refsasaran_id ? 'selected' : '' }}>{{ $s->uraian_sasaran }}</option>
                    @endforeach
                </select>
            </div>

            <div class="fv-row mb-7">
                <label class="required fs-6 fw-semibold mb-2">Uraian Sasaran Renstra</label>
                <textarea name="uraian_sasaranrenstra" class="form-control form-control-solid" rows="4">{{ $sasaran->uraian_sasaranrenstra }}</textarea>
            </div>

            <div class="d-flex justify-content-end">
                <a href="{{ route('frontend.renstra.sasaran.index') }}" class="btn btn-light me-3">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#edit_periode').on('change', function() {
        var periode_id = $(this).val();
        $.ajax({
            url: "{{ url('frontend/renstra/get-sasaran-rpjmd') }}/" + periode_id,
            type: "GET",
            success: function(data) {
                var options = '<option></option>';
                $.each(data, function(key, value) {
                    options += '<option value="' + value.refsasaran_id + '">' + value.uraian_sasaran + '</option>';
                });
                $('#edit_sasaran_rpjmd').html(options).trigger('change');
            }
        });
    });
</script>
@endpush
