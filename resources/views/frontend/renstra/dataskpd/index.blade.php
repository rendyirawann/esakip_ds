@extends('frontend.layout.app')

@section('title', 'RENSTRA - Data SKPD')

@section('content')
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        
        <!--begin::Hero Section-->
        <div class="card border-0 mb-5 mb-xl-10 shadow-sm" style="background: linear-gradient(112.14deg, #1e1e2d 0%, #3a3b5a 100%);">
            <div class="card-body pt-9 pb-0">
                <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                    <div class="me-7 mb-4">
                        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative border border-4 border-white shadow-sm">
                            <img src="{{ asset('assets/media/svg/brand-logos/volicity-9.svg') }}" alt="SKPD Logo" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex align-items-center mb-2">
                                    <h1 class="text-white fs-2 fw-bold me-1">{{ $data_skpd->nama_skpd ?? 'DATA SKPD' }}</h1>
                                    <span class="badge badge-light-success fw-bold ms-2 fs-8 py-1 px-3">AKTIF</span>
                                </div>
                                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                    <span class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                    <i class="ki-outline ki-geolocation fs-4 me-1 text-primary"></i>Deli Serdang, Sumatera Utara</span>
                                    <span class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <i class="ki-outline ki-sms fs-4 me-1 text-primary"></i>skpd@deliserdang.go.id</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-stack">
                            <div class="d-flex flex-column flex-grow-1 pe-8">
                                <div class="d-flex flex-wrap">
                                    <div class="border border-gray-600 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold text-white tabular-nums">{{ $data_skpd->kode_skpd ?? '-' }}</div>
                                        </div>
                                        <div class="fw-semibold fs-7 text-gray-500">Kode SKPD</div>
                                    </div>
                                    <div class="border border-gray-600 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-2 fw-bold text-white">{{ $data_skpd->kepala_skpd ?? '-' }}</div>
                                        </div>
                                        <div class="fw-semibold fs-7 text-gray-500">Kepala SKPD</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Hero Section-->

        <!--begin::Filters-->
        <div class="card mb-5 mb-xl-10 shadow-sm border-0">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Filter Data</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Sesuaikan periode dan unit kerja</span>
                </h3>
            </div>
            <div class="card-body py-5">
                <form action="{{ route('frontend.renstra.dataskpd.index') }}" method="GET" class="row g-3">
                    @if($isSuperadmin)
                    <div class="col-md-5">
                        <label class="form-label fw-bold text-gray-700">Pilih SKPD</label>
                        <select name="skpd_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih SKPD...">
                            <option></option>
                            @foreach($skpds as $s)
                                <option value="{{ $s->refskpd_id }}" {{ $skpd_id == $s->refskpd_id ? 'selected' : '' }}>{{ $s->nama_skpd }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-gray-700">Pilih Periode (Tahun)</label>
                        <select name="periode_id" class="form-select form-select-solid" data-control="select2" data-placeholder="Pilih Periode...">
                            @foreach($periodes as $p)
                                <option value="{{ $p->refperiode_id }}" {{ $periode_id == $p->refperiode_id ? 'selected' : '' }}>{{ $p->periode }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 shadow-sm">
                            <i class="ki-outline ki-magnifier fs-2 me-1"></i> Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Filters-->

        @if($data_skpd)
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <!--begin::Visi Section-->
            <div class="col-xl-4">
                <div class="card card-flush h-md-100 border-0 shadow-sm overflow-hidden" style="background-color: #f8f9fa;">
                    <div class="card-header pt-7 position-relative">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Visi SKPD</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Tujuan jangka panjang</span>
                        </h3>
                        <div class="position-absolute top-0 end-0 mt-n10 me-n10 opacity-10">
                            <i class="ki-outline ki-eye fs-5hx text-dark"></i>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        @if($visi)
                            <div class="p-6 rounded-3 bg-white border border-dashed border-primary">
                                <div class="fs-5 fw-semibold text-gray-800 italic mb-0">{!! $visi->uraian_visi !!}</div>
                            </div>
                            <div class="mt-5 text-muted fs-7">
                                <strong>Penjabaran:</strong><br>
                                {!! $visi->penjabaran_visi ?? 'Tidak ada penjabaran.' !!}
                            </div>
                        @else
                            <div class="alert alert-dismissible bg-light-warning d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-outline ki-information-5 fs-2hx text-warning me-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h4 class="fw-bold">Data Belum Ada</h4>
                                    <span>Visi untuk periode ini belum diinputkan ke dalam sistem.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Visi Section-->

            <!--begin::Misi Section-->
            <div class="col-xl-8">
                <div class="card card-flush h-md-100 border-0 shadow-sm">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Misi SKPD</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Langkah-langkah strategis pencapaian visi</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        @if($misi->count() > 0)
                            <div class="timeline-label">
                                @foreach($misi as $index => $m)
                                <div class="timeline-item">
                                    <div class="timeline-label fw-bold text-gray-800 fs-6">Misi {{ $index + 1 }}</div>
                                    <div class="timeline-badge">
                                        <i class="fa fa-genderless text-primary fs-1"></i>
                                    </div>
                                    <div class="timeline-content fw-mormal text-muted ps-3">{!! $m->uraian_misi !!}</div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-dismissible bg-light-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-outline ki-information-5 fs-2hx text-danger me-4 mb-5 mb-sm-0"></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h4 class="fw-bold">Data Belum Ada</h4>
                                    <span>Misi untuk periode ini belum diinputkan ke dalam sistem.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Misi Section-->
        </div>
        @else
        <div class="card shadow-sm border-0 py-20">
            <div class="card-body text-center">
                <img src="{{ asset('assets/media/illustrations/sigma-1/5.png') }}" class="h-150px mb-10" alt="Select Data" />
                <h2 class="fw-bold text-gray-800 mb-2">Pilih SKPD untuk melihat data</h2>
                <p class="text-gray-500 fs-5">Silakan pilih unit kerja dan tahun periode melalui form filter di atas.</p>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection

@push('stylesheets')
<style>
    .italic { font-style: italic; }
    .timeline-label { position: relative; }
    .timeline-label:before {
        content: "";
        position: absolute;
        left: 88px;
        width: 3px;
        top: 0;
        bottom: 0;
        background-color: #f1f1f2;
    }
    .timeline-item { display: flex; align-items: flex-start; margin-bottom: 2rem; position: relative; }
    .timeline-label { width: 90px; flex-shrink: 0; position: relative; }
    .timeline-badge { padding: 7px 0; z-index: 1; margin-left: -7px; background: #fff; }
    .timeline-content { flex-grow: 1; }
</style>
@endpush
