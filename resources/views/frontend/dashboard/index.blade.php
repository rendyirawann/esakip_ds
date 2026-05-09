@extends('frontend.layout.app')

@section('title', 'Dashboard Frontend')

@section('content')
<div class="card">
    <div class="card-body p-lg-20">
        <div class="text-center mb-10">
            <h1 class="text-gray-900 fw-bolder mb-3">Selamat Datang di Portal Frontend SAKIP</h1>
            <div class="text-gray-500 fw-semibold fs-6">Halaman ini khusus untuk SKPD dan Admin untuk memantau data SAKIP.</div>
        </div>
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">Data SAKIP</span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Statistik Laporan</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                                <span class="fw-bold fs-6 text-gray-500">Progress</span>
                                <span class="fw-bold fs-6">75%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-light-success rounded">
                                <div class="bg-success rounded h-8px" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
