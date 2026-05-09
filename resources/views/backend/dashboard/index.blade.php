@extends('backend.layout.app')
@section('title', 'Monitoring Dashboard SAKIP')

@section('content')
<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-0">
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center me-3">
            <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                Dashboard Monitoring</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                    <a href="{{ route('dashboard') }}" class="text-hover-primary">
                        <i class="ki-outline ki-home text-gray-700 fs-6"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="ki-outline ki-right fs-5 text-gray-700 mx-n1"></i>
                </li>
                <li class="breadcrumb-item text-gray-700 fw-bold lh-1">Dashboard</li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                <div class="m-0">
                    <select name="skpd_id" class="form-select form-select-solid fw-bold w-250px" data-control="select2" data-placeholder="Pilih SKPD">
                        <option value="all">Semua SKPD</option>
                        @foreach($skpds as $skpd)
                            <option value="{{ $skpd->refskpd_id }}" {{ (isset($skpd_id) && $skpd_id == $skpd->refskpd_id) ? 'selected' : '' }}>{{ $skpd->nama_skpd }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="m-0">
                    <select name="periode_id" class="form-select form-select-solid fw-bold w-150px" data-control="select2" data-placeholder="Tahun">
                        @foreach($periodes as $p)
                            <option value="{{ $p->refperiode_id }}" {{ (isset($periode_id) && $periode_id == $p->refperiode_id) ? 'selected' : '' }}>{{ $p->periode }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm fw-bold">
                    <i class="ki-outline ki-filter fs-2"></i> Filter
                </button>
            </form>
        </div>
    </div>
</div>
<!--end::Toolbar-->

<!--begin::Stats Row-->
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card card-flush h-md-100 bgi-no-repeat bgi-size-contain bgi-position-x-end shadow-sm" style="background-color: #F1416C; background-image:url('{{ asset('assets/media/svg/shapes/wave-bg-red.svg') }}')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['skpd'] }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total SKPD</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card card-flush h-md-100 bgi-no-repeat bgi-size-contain bgi-position-x-end shadow-sm" style="background-color: #7239EA; background-image:url('{{ asset('assets/media/svg/shapes/wave-bg-purple.svg') }}')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['program'] }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Program</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card card-flush h-md-100 bgi-no-repeat bgi-size-contain bgi-position-x-end shadow-sm" style="background-color: #009EF7; background-image:url('{{ asset('assets/media/svg/shapes/wave-bg-blue.svg') }}')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['kegiatan'] }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Kegiatan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="card card-flush h-md-100 bgi-no-repeat bgi-size-contain bgi-position-x-end shadow-sm" style="background-color: #50CD89; background-image:url('{{ asset('assets/media/svg/shapes/wave-bg-green.svg') }}')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $stats['subkegiatan'] }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Sub Kegiatan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-xl-8">
                <div class="card card-flush shadow-sm h-xl-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Distribusi Program per Bidang</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-7">Statistik program kerja berdasarkan bidang urusan</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div id="kt_chart_distribusi" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card card-flush shadow-sm h-xl-100">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">Summary SAKIP Global</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-7">Kualitas data masukan sistem</span>
                        </h3>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack mb-5">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="ki-outline ki-abstract-26 fs-1 text-primary"></i>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Kualitas Visi & Misi</a>
                                    <div class="fs-7 text-muted fw-semibold">Data Master</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-stack mb-5">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-outline ki-abstract-24 fs-1 text-success"></i>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Keselarasan Program</a>
                                    <div class="fs-7 text-muted fw-semibold">Sinkronisasi Data</div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-stack mb-5">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-3">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="ki-outline ki-abstract-41 fs-1 text-warning"></i>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">Kelengkapan Penjabat</a>
                                    <div class="fs-7 text-muted fw-semibold">Administrasi</div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-5"></div>
                        <div class="text-center">
                            <a href="#" class="btn btn-sm btn-light">Lihat Detail Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--begin::Table Summary-->
        <div class="card card-flush shadow-sm">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">Monitoring Progress SKPD</span>
                    <span class="text-gray-500 mt-1 fw-semibold fs-7">Daftar lengkap capaian input data per SKPD</span>
                </h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-250px">Nama SKPD</th>
                                <th class="text-center min-w-100px">Program</th>
                                <th class="text-center min-w-100px">Kegiatan</th>
                                <th class="text-center min-w-100px">Sub Kegiatan</th>
                                <th class="text-center min-w-125px">Status Progress</th>
                                <th class="text-end min-w-100px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($skpd_summary as $s)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <div class="symbol-label fs-3 fw-bold bg-light-info text-info">
                                                {{ substr($s->nama_skpd, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $s->nama_skpd }}</a>
                                            <span class="text-muted fs-7">{{ $s->kode_skpd }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light fw-bold text-gray-800">{{ $s->programs_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light fw-bold text-gray-800">{{ $s->kegiatans_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light fw-bold text-gray-800">{{ $s->subkegiatans_count }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $progress = 0;
                                        if($s->programs_count > 0) $progress += 30;
                                        if($s->kegiatans_count > 0) $progress += 30;
                                        if($s->subkegiatans_count > 0) $progress += 40;
                                        
                                        $color = 'danger';
                                        if($progress > 30) $color = 'warning';
                                        if($progress > 60) $color = 'primary';
                                        if($progress == 100) $color = 'success';
                                    @endphp
                                    <div class="d-flex flex-column w-100 me-2">
                                        <div class="d-flex flex-stack mb-2">
                                            <span class="text-muted me-2 fs-7 fw-bold">{{ $progress }}%</span>
                                        </div>
                                        <div class="progress h-6px w-100">
                                            <div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary">
                                        <i class="ki-outline ki-arrow-right fs-2"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-stack flex-wrap pt-10">
                    <div class="fs-6 fw-semibold text-gray-700">
                        Menampilkan {{ $skpd_summary->firstItem() }} ke {{ $skpd_summary->lastItem() }} dari {{ $skpd_summary->total() }} SKPD
                    </div>
                    <div class="pagination-wrapper">
                        {{ $skpd_summary->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
<script>
    var element = document.getElementById('kt_chart_distribusi');
    if (element) {
        var options = {
            series: [{
                name: 'Total Program',
                data: @json($chart_values)
            }],
            chart: {
                fontFamily: 'inherit',
                type: 'bar',
                height: 350,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: ['30%'],
                    borderRadius: 5,
                    dataLabels: { position: 'top' }
                },
            },
            legend: { show: false },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: { fontSize: '12px', colors: ["#304758"] }
            },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: @json($chart_labels),
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
            },
            yaxis: {
                labels: { style: { colors: '#A1A5B7', fontSize: '12px' } }
            },
            fill: { opacity: 1 },
            states: {
                hover: { filter: { type: 'none', value: 0 } },
                active: { allowMultipleDataPointsSelection: false, filter: { type: 'none', value: 0 } }
            },
            tooltip: {
                style: { fontSize: '12px' },
                y: { formatter: function (val) { return val + " Program" } }
            },
            colors: ['#009EF7'],
            grid: {
                borderColor: '#F1F1F1',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            }
        };
        var chart = new ApexCharts(element, options);
        chart.render();
    }
</script>
@endpush
