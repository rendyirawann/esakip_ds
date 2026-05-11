<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkSasaranController extends Controller
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

        return view('frontend.pk.sasaran.index', compact(
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

        $query = SakipIndikatorsasaranrenstra::with(['sasaran'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', function($row) {
                return $row->sasaran ? strip_tags($row->sasaran->uraian_sasaranrenstra) : '-';
            })
            ->addColumn('target_rkt_display', function($row) {
                return $row->target_rkt ?? '-';
            })
            ->addColumn('keterangan_rkt', function($row) {
                return $row->keterangan ?? '-';
            })
            ->addColumn('target_pk_display', function($row) {
                return $row->target_pk ?? '-';
            })
            ->addColumn('keterangan_pk_display', function($row) {
                return $row->keterangan_pk ?? '-';
            })
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-icon btn-sm btn-light-primary" onclick="editPk('.$row->refindikatorsasaranrenstra_id.')">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $indikator = SakipIndikatorsasaranrenstra::with('sasaran')->findOrFail($id);
        return response()->json($indikator);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'target_pk' => 'required',
            'keterangan_pk' => 'nullable'
        ]);

        $model = SakipIndikatorsasaranrenstra::find($request->id);
        if ($model) {
            $model->target_pk = $request->target_pk;
            $model->keterangan_pk = $request->keterangan_pk;
            $model->save();
            return response()->json(['success' => true, 'message' => 'Data PK berhasil disimpan!']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }
}
