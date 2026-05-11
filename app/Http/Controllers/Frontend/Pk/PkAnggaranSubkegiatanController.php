<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorcascadingsubkegiatan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkAnggaranSubkegiatanController extends Controller
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

        return view('frontend.pk.anggaran.subkegiatan', compact(
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

        $query = SakipIndikatorcascadingsubkegiatan::with(['subkegiatan', 'kegiatan', 'cascading'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_kegiatan', function($row) {
                return $row->kegiatan->nama_kegiatan ?? '-';
            })
            ->addColumn('nama_subkegiatan', function($row) {
                return $row->subkegiatan->nama_subkegiatan ?? '-';
            })
            ->addColumn('anggaran_renstra', function($row) {
                $anggaran = $row->cascading->subkegiatan_anggaran ?? 0;
                return 'Rp. ' . number_format($anggaran, 0, ',', '.');
            })
            ->addColumn('anggaran_pk_input', function($row) {
                $val = $row->anggaran_pk ?? 0;
                return '<div class="input-group input-group-sm w-150px ms-auto">
                            <span class="input-group-text bg-light">Rp</span>
                            <input type="text" class="form-control text-end input-anggaran-pk bg-light" 
                                data-id="'.$row->refindikatorsubkegiatan_id.'" 
                                data-original="'.number_format($val, 0, ',', '.').'"
                                value="'.number_format($val, 0, ',', '.').'" 
                                readonly
                                onkeyup="formatRupiahInput(this)">
                        </div>';
            })
            ->rawColumns(['anggaran_pk_input'])
            ->make(true);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'anggaran' => 'required'
        ]);

        $id = $request->id;
        $anggaran = str_replace('.', '', $request->anggaran);

        $model = SakipIndikatorcascadingsubkegiatan::find($id);
        if ($model) {
            $model->anggaran_pk = $anggaran;
            $model->save();
            return response()->json(['success' => true, 'message' => 'Anggaran PK berhasil disimpan!']);
        }

        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }
}
