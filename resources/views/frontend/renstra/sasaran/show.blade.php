@extends('frontend.layout.app')

@section('title', 'Detail Sasaran Renstra')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h2>Detail Sasaran Renstra</h2>
        </div>
        <div class="card-toolbar">
            <a href="{{ route('frontend.renstra.sasaran.index') }}" class="btn btn-light-primary">
                <i class="ki-outline ki-arrow-left fs-2"></i>Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-bordered gy-5">
                <tr>
                    <th class="fw-bold text-gray-800 w-200px">SKPD</th>
                    <td>{{ $sasaran->skpd->nama_skpd }}</td>
                </tr>
                <tr>
                    <th class="fw-bold text-gray-800">Tahun Periode</th>
                    <td>{{ $sasaran->periode->periode }}</td>
                </tr>
                <tr>
                    <th class="fw-bold text-gray-800">Sasaran RPJMD</th>
                    <td>{{ $sasaran->sasaranRpjmd->uraian_sasaran ?? '-' }}</td>
                </tr>
                <tr>
                    <th class="fw-bold text-gray-800">Uraian Sasaran Renstra</th>
                    <td>{{ $sasaran->uraian_sasaranrenstra }}</td>
                </tr>
                <tr>
                    <th class="fw-bold text-gray-800">Status Aktif</th>
                    <td>
                        <span class="badge {{ $sasaran->sasaranrenstra_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger' }}">
                            {{ $sasaran->sasaranrenstra_isaktif == 'T' ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
