<?php

namespace App\Http\Controllers\Frontend\Rkt;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstra;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RktSasaranController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        // Read from session or request
        $skpd_id = $request->skpd_id ?? session('rkt_sasaran_skpd_id');
        $current_periode_id = $request->periode_id ?? session('rkt_sasaran_periode_id');

        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;
        $current_periode = $current_periode_id ? SakipPeriode::find($current_periode_id) : null;

        // Fetch data grouped by Sasaran ONLY if filters are present
        $sasarans = collect();
        if ($skpd_id && $current_periode_id) {
            $sasarans = SakipSasaranrenstra::with(['indikators' => function($query) use ($current_periode_id) {
                $query->where('refperiode_id', $current_periode_id);
            }])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $current_periode_id)
            ->get();
        }

        return view('frontend.rkt.sasaran.index', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 
            'current_periode', 'sasarans'
        ));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id ?? session('rkt_sasaran_skpd_id');
        $periode_id = $request->periode_id ?? session('rkt_sasaran_periode_id');

        $query = SakipIndikatorsasaranrenstra::with('sasaran')
            ->whereHas('sasaran', function($q) use ($skpd_id) {
                $q->where('refskpd_id', $skpd_id);
            })
            ->where('refperiode_id', $periode_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', function($row) {
                return $row->sasaran ? strip_tags($row->sasaran->uraian_sasaranrenstra) : '-';
            })
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-icon btn-sm btn-light-success btn-active-success h-30px w-30px" onclick="editRkt('.$row->refindikatorsasaranrenstra_id.')">
                            <i class="ki-outline ki-pencil fs-4"></i>
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'target_rkt' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        try {
            $indikator = SakipIndikatorsasaranrenstra::findOrFail($id);
            $indikator->update([
                'target_rkt' => $request->target_rkt,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json(['success' => 'Data RKT berhasil diperbarui!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
