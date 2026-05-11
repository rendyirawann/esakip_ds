<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorcascadingkegiatanTriwulan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkTriwulanKegiatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id;
        $current_periode_id = $request->periode_id;
        $current_triwulan = $request->triwulan ?? 1;

        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;
        $current_periode = $current_periode_id ? SakipPeriode::find($current_periode_id) : null;

        return view('frontend.pk.triwulan.kegiatan', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 
            'current_periode', 'current_triwulan'
        ));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;
        $triwulan_id = $request->triwulan_id;

        if (!$skpd_id || !$periode_id || !$triwulan_id) {
            return DataTables::of(collect([]))->make(true);
        }

        $query = SakipIndikatorcascadingkegiatanTriwulan::select(
                'sakip_indikatorcascadingkegiatan_triwulan.*', 
                'sakip_indikatorcascadingkegiatan.target_pk', 
                'sakip_cascadingkegiatan.uraian_indikatorkegiatan', 
                'sakip_cascadingkegiatan.kegiatan_satuan', 
                'sakip_kegiatan.nama_kegiatan'
            )
            ->join('sakip_indikatorcascadingkegiatan', 'sakip_indikatorcascadingkegiatan_triwulan.refindikatorkegiatan_id', '=', 'sakip_indikatorcascadingkegiatan.refindikatorkegiatan_id')
            ->join('sakip_cascadingkegiatan', 'sakip_indikatorcascadingkegiatan.refcascadingkegiatan_id', '=', 'sakip_cascadingkegiatan.refcascadingkegiatan_id')
            ->join('sakip_kegiatan', 'sakip_cascadingkegiatan.refkegiatan_id', '=', 'sakip_kegiatan.refkegiatan_id')
            ->where('sakip_indikatorcascadingkegiatan_triwulan.refskpd_id', $skpd_id)
            ->where('sakip_indikatorcascadingkegiatan_triwulan.refperiode_id', $periode_id)
            ->where('sakip_indikatorcascadingkegiatan_triwulan.reftriwulan_id', $triwulan_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_kegiatan', function($row) {
                return $row->nama_kegiatan ?? '-';
            })
            ->addColumn('indikator', function($row) {
                return $row->uraian_indikatorkegiatan ?? '-';
            })
            ->addColumn('satuan', function($row) {
                return $row->kegiatan_satuan ?? '-';
            })
            ->addColumn('target_pk_tahunan', function($row) {
                return $row->target_pk ?? '-';
            })
            ->addColumn('target_pk_triwulan', function($row) {
                return $row->triwulan_target_pk ?? '-';
            })
            ->addColumn('keterangan', function($row) {
                return $row->triwulan_keterangan ?? '-';
            })
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit" data-id="'.$row->refindikatorkegiatantriwulan_id.'" title="Edit PK Triwulan">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = SakipIndikatorcascadingkegiatanTriwulan::findOrFail($id);
        
        $html = '
        <div class="fv-row mb-5">
            <label class="required fw-semibold fs-6 mb-2">Target PK Triwulan '.$data->reftriwulan_id.'</label>
            <input type="text" class="form-control form-control-solid" name="triwulan_target_pk" value="'.$data->triwulan_target_pk.'" required>
        </div>
        <div class="fv-row mb-5">
            <label class="fw-semibold fs-6 mb-2">Sebab Perubahan (Keterangan)</label>
            <textarea class="form-control form-control-solid" name="triwulan_keterangan" rows="3">'.$data->triwulan_keterangan.'</textarea>
        </div>
        <input type="hidden" name="id" value="'.$data->refindikatorkegiatantriwulan_id.'">
        ';

        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'triwulan_target_pk' => 'required',
        ]);

        $model = SakipIndikatorcascadingkegiatanTriwulan::findOrFail($request->id);
        $model->triwulan_target_pk = $request->triwulan_target_pk;
        $model->triwulan_keterangan = $request->triwulan_keterangan;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Data PK Triwulan Kegiatan berhasil disimpan!'
        ]);
    }
}
