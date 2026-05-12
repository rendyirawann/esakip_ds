<?php

namespace App\Http\Controllers\Frontend\Pkp;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingprogram;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkpAnggaranProgramController extends Controller
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

        return view('frontend.pkp.anggaran.program', compact(
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

        $query = SakipCascadingprogram::select('refsasaranrenstra_id', 'refprogram_id', 'refskpd_id', 'refperiode_id')
            ->with(['program', 'sasaranRenstra'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id)
            ->groupBy('refsasaranrenstra_id', 'refprogram_id', 'refskpd_id', 'refperiode_id')
            ->withSum(['cascadingSubkegiatans as total_anggaran_renstra' => function($q) use ($skpd_id, $periode_id) {
                $q->select(\DB::raw('SUM(CAST(subkegiatan_anggaran AS BIGINT))'))
                  ->where('refskpd_id', $skpd_id)
                  ->where('refperiode_id', $periode_id);
            }], 'subkegiatan_anggaran')
            ->withSum(['indikatorSubkegiatans as total_anggaran_rkt' => function($q) use ($skpd_id, $periode_id) {
                $q->select(\DB::raw('SUM(CAST(anggaran_rkt AS BIGINT))'))
                  ->where('refskpd_id', $skpd_id)
                  ->where('refperiode_id', $periode_id);
            }], 'anggaran_rkt')
            ->withSum(['indikatorSubkegiatans as total_anggaran_pk' => function($q) use ($skpd_id, $periode_id) {
                $q->select(\DB::raw('SUM(CAST(anggaran_pk AS BIGINT))'))
                  ->where('refskpd_id', $skpd_id)
                  ->where('refperiode_id', $periode_id);
            }], 'anggaran_pk')
            ->withSum(['indikatorSubkegiatans as total_anggaran_pk_p' => function($q) use ($skpd_id, $periode_id) {
                $q->select(\DB::raw('SUM(CAST(anggaran_pk_p AS BIGINT))'))
                  ->where('refskpd_id', $skpd_id)
                  ->where('refperiode_id', $periode_id);
            }], 'anggaran_pk_p');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', function($row) {
                return $row->sasaranRenstra->uraian_sasaranrenstra ?? '-';
            })
            ->addColumn('nama_program', function($row) {
                return $row->program->nama_program ?? '-';
            })
            ->addColumn('total_anggaran_renstra', function($row) {
                return 'Rp. ' . number_format($row->total_anggaran_renstra ?? 0, 0, ',', '.');
            })
            ->addColumn('total_anggaran_rkt', function($row) {
                return 'Rp. ' . number_format($row->total_anggaran_rkt ?? 0, 0, ',', '.');
            })
            ->addColumn('total_anggaran_pk', function($row) {
                return 'Rp. ' . number_format($row->total_anggaran_pk ?? 0, 0, ',', '.');
            })
            ->addColumn('total_anggaran_pk_p', function($row) {
                return 'Rp. ' . number_format($row->total_anggaran_pk_p ?? 0, 0, ',', '.');
            })
            ->make(true);
    }
}
