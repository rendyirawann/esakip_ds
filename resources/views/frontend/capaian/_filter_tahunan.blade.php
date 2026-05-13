{{-- Shared Filter for Tahunan pages --}}
<div class="card mb-5 shadow-sm">
    <div class="card-body">
        <div class="row g-5 align-items-end">
            @if($isSuperadmin)
            <div class="col-md-5">
                <label class="form-label fw-bold fs-6">Pilih SKPD</label>
                <select id="filter_skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD">
                    <option></option>
                    @foreach($skpds as $s)
                        <option value="{{ $s->refskpd_id }}" {{ $current_skpd && $s->refskpd_id == $current_skpd->refskpd_id ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
            @else
            <div class="col-md-7">
            @endif
                <label class="form-label fw-bold fs-6">Pilih Tahun</label>
                <select id="filter_periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Tahun">
                    <option></option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->refperiode_id }}" {{ $current_periode && $p->refperiode_id == $current_periode->refperiode_id ? 'selected' : '' }}>{{ $p->periode }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="button" id="btn_tampilkan" class="btn btn-primary w-100 fw-bold no-loader">
                    <span class="indicator-label"><i class="ki-outline ki-magnifier fs-2 me-2"></i>Tampilkan Data</span>
                    <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>
