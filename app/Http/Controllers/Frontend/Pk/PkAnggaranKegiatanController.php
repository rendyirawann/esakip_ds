<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingkegiatan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkAnggaranKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id;
        $current_periode_id = $request->periode_id;

        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;
        $current_periode = $current_periode_id ? SakipPeriode::find($current_periode_id) : null;

        return view('frontend.pk.anggaran.kegiatan', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 
            'current_periode'
        ));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;

        if (!$skpd_id || !$periode_id) {
            return DataTables::of(collect([]))->make(true);
        }

        $query = SakipCascadingkegiatan::select('refprogram_id', 'refkegiatan_id', 'refskpd_id', 'refperiode_id')
            ->with(['kegiatan', 'program'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id)
            ->groupBy('refprogram_id', 'refkegiatan_id', 'refskpd_id', 'refperiode_id')
            ->withSum(['indikatorSubkegiatans as total_anggaran_pk' => function($q) use ($skpd_id, $periode_id) {
                $q->where('refskpd_id', $skpd_id)
                  ->where('refperiode_id', $periode_id);
            }], \DB::raw('CAST(anggaran_pk AS BIGINT)'));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_program', function($row) {
                return $row->program->nama_program ?? '-';
            })
            ->addColumn('nama_kegiatan', function($row) {
                return $row->kegiatan->nama_kegiatan ?? '-';
            })
            ->addColumn('anggaran_pk', function($row) {
                return 'Rp. ' . number_format($row->total_anggaran_pk ?? 0, 0, ',', '.');
            })
            ->make(true);
    }
}
