<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorcascadingprogram;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id ?? session('pk_program_skpd_id');
        $current_periode_id = $request->periode_id ?? session('pk_program_periode_id');

        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;
        $current_periode = $current_periode_id ? SakipPeriode::find($current_periode_id) : null;

        return view('frontend.pk.program.index', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 
            'current_periode'
        ));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id ?? session('pk_program_skpd_id');
        $periode_id = $request->periode_id ?? session('pk_program_periode_id');

        if ($request->has('skpd_id') && $request->has('periode_id') && $request->skpd_id != null) {
            session([
                'pk_program_skpd_id' => $request->skpd_id,
                'pk_program_periode_id' => $request->periode_id
            ]);
            $skpd_id = $request->skpd_id;
            $periode_id = $request->periode_id;
        }

        if (!$skpd_id || !$periode_id) {
            return DataTables::of(collect([]))->make(true);
        }

        $query = SakipIndikatorcascadingprogram::with(['cascading', 'program'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_program', function($row) {
                return $row->program->nama_program ?? '-';
            })
            ->addColumn('uraian_indikator', function($row) {
                $sasaran = $row->cascading->uraian_sasaranprogram ?? '';
                $indikator = $row->cascading->uraian_indikatorprogram ?? '';
                return ($sasaran ? $sasaran . ' / ' : '') . $indikator;
            })
            ->addColumn('satuan', function($row) {
                return $row->cascading->program_satuan ?? '-';
            })
            ->addColumn('target_renstra', function($row) {
                return $row->cascading->program_target ?? '-';
            })
            ->addColumn('target_rkt', function($row) {
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
                return '<button type="button" class="btn btn-icon btn-sm btn-light-primary" onclick="editPk('.$row->refindikatorprogram_id.')">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $indikator = SakipIndikatorcascadingprogram::with(['program', 'cascading'])->findOrFail($id);
        return response()->json($indikator);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'target_pk' => 'required',
            'keterangan_pk' => 'nullable'
        ]);

        $model = SakipIndikatorcascadingprogram::find($request->id);
        if ($model) {
            $model->target_pk = $request->target_pk;
            $model->keterangan_pk = $request->keterangan_pk;
            $model->save();
            return response()->json(['success' => true, 'message' => 'Data PK Program berhasil disimpan!']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }
}
