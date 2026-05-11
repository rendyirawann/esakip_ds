<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingprogram;
use App\Models\SakipIndikatorcascadingprogram;
use App\Models\SakipCascadingsubkegiatan;
use App\Models\SakipPenjabatskpdCascadingprogram;
use App\Models\SakipProgram;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CascadingProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id;
        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;

        if ($request->ajax()) {
            $query = SakipCascadingprogram::with([
                'misi', 
                'tujuanRpjmd',
                'sasaranRenstra',
                'program',
                'penjabat.penjabatMaster'
            ])->where('sakip_cascadingprogram.refskpd_id', $skpd_id);

            if ($request->periode_id) {
                $query->where('sakip_cascadingprogram.refperiode_id', $request->periode_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('misi_text', function($row) {
                    return $row->misi ? strip_tags($row->misi->uraian_misi) : '-';
                })
                ->addColumn('tujuan_text', function($row) {
                    return $row->tujuanRpjmd ? strip_tags($row->tujuanRpjmd->uraian_tujuan) : '-';
                })
                ->addColumn('sasaran_text', function($row) {
                    return $row->sasaranRenstra ? strip_tags($row->sasaranRenstra->uraian_sasaranrenstra) : '-';
                })
                ->addColumn('program_kode', function($row) {
                    return $row->program->kode_program ?? '-';
                })
                ->addColumn('program_nama', function($row) {
                    return $row->program->nama_program ?? '-';
                })
                ->addColumn('anggaran', function($row) {
                    $sum = SakipCascadingsubkegiatan::where('refskpd_id', $row->refskpd_id)
                        ->where('refprogram_id', $row->refprogram_id)
                        ->where('refperiode_id', $row->refperiode_id)
                        ->sum(DB::raw('CAST(subkegiatan_anggaran AS NUMERIC)'));
                    return $sum ?? 0;
                })
                ->addColumn('penjabat_list', function($row) {
                    return array_values($row->penjabat->map(function($p) {
                        return [
                            'nama' => $p->penjabatMaster->nama_penjabat ?? '-',
                            'nip' => $p->penjabatMaster->nip_penjabat ?? '-',
                            'jabatan' => $p->penjabatMaster->jabatan_eselon ?? '-',
                            'id' => $p->refpenjabatcascadingprogram_id
                        ];
                    })->toArray());
                })
                ->rawColumns(['penjabat_list'])
                ->make(true);
        }

        return view('frontend.renstra.cascadingprogram.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function show($id)
    {
        $data = SakipCascadingprogram::with(['misi', 'tujuanRpjmd', 'sasaranRenstra', 'program'])->findOrFail($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        // CRUD implementation for store
        // ... (can be completed based on the form structure later)
    }

    public function destroy($id)
    {
        SakipCascadingprogram::destroy($id);
        return response()->json(['success' => 'Cascading Program berhasil dihapus!']);
    }
}
