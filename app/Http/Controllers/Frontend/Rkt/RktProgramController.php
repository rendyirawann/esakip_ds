<?php

namespace App\Http\Controllers\Frontend\Rkt;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorcascadingprogram;
use App\Models\SakipProgram;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RktProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id ?? session('rkt_program_skpd_id');
        $current_periode_id = $request->periode_id ?? session('rkt_program_periode_id');

        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;
        $current_periode = $current_periode_id ? SakipPeriode::find($current_periode_id) : null;

        return view('frontend.rkt.program.index', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 
            'current_periode'
        ));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id ?? session('rkt_program_skpd_id');
        $periode_id = $request->periode_id ?? session('rkt_program_periode_id');

        $query = SakipIndikatorcascadingprogram::with(['program', 'cascading'])
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
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-icon btn-sm btn-light-success btn-active-success h-30px w-30px" onclick="editRkt('.$row->refindikatorprogram_id.')">
                            <i class="ki-outline ki-pencil fs-4"></i>
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_rkt' => 'required',
        ]);

        $indikator = SakipIndikatorcascadingprogram::findOrFail($id);
        $indikator->update([
            'target_rkt' => $request->target_rkt,
            'keterangan' => $request->keterangan
        ]);

        return response()->json(['success' => 'Data RKT berhasil diperbarui!']);
    }

    public function storeFilter(Request $request)
    {
        session([
            'rkt_program_skpd_id' => $request->skpd_id,
            'rkt_program_periode_id' => $request->periode_id
        ]);

        return redirect()->route('frontend.rkt.program.index');
    }
}
