<?php

namespace App\Http\Controllers\Frontend\Pk;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstraTriwulan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkTriwulanSasaranController extends Controller
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

        return view('frontend.pk.triwulan.sasaran', compact(
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

        $query = SakipIndikatorsasaranrenstraTriwulan::select('sakip_indikatorsasaranrenstra_triwulan.*', 'sakip_indikatorsasaranrenstra.target_pk', 'sakip_indikatorsasaranrenstra.uraian_indikatorsasaranrenstra', 'sakip_indikatorsasaranrenstra.indikatorsasaranrenstra_satuan', 'sakip_indikatorsasaranrenstra.iku_isaktif', 'sakip_indikatorsasaranrenstra.pk_isaktif', 'sakip_sasaranrenstra.uraian_sasaranrenstra')
            ->join('sakip_indikatorsasaranrenstra', 'sakip_indikatorsasaranrenstra_triwulan.refindikatorsasaranrenstra_id', '=', 'sakip_indikatorsasaranrenstra.refindikatorsasaranrenstra_id')
            ->join('sakip_sasaranrenstra', 'sakip_indikatorsasaranrenstra.refsasaranrenstra_id', '=', 'sakip_sasaranrenstra.refsasaranrenstra_id')
            ->with(['skpd', 'periode'])
            ->where('sakip_indikatorsasaranrenstra_triwulan.refskpd_id', $skpd_id)
            ->where('sakip_indikatorsasaranrenstra_triwulan.refperiode_id', $periode_id)
            ->where('sakip_indikatorsasaranrenstra_triwulan.reftriwulan_id', $triwulan_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', function($row) {
                return $row->uraian_sasaranrenstra ?? '-';
            })
            ->addColumn('indikator', function($row) {
                return $row->uraian_indikatorsasaranrenstra ?? '-';
            })
            ->addColumn('satuan', function($row) {
                return $row->indikatorsasaranrenstra_satuan ?? '-';
            })
            ->addColumn('status_iku', function($row) {
                if($row->iku_isaktif == 'T') return '<span class="badge badge-light-success">Aktif</span>';
                return '<span class="badge badge-light-danger">Non-Aktif</span>';
            })
            ->addColumn('status_pk', function($row) {
                if($row->pk_isaktif == 'T') return '<span class="badge badge-light-success">Aktif</span>';
                return '<span class="badge badge-light-danger">Non-Aktif</span>';
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
                return '<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit" data-id="'.$row->refindikatorsasaranrenstratriwulan_id.'" title="Edit PK Triwulan">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['status_iku', 'status_pk', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = SakipIndikatorsasaranrenstraTriwulan::findOrFail($id);
        
        $html = '
        <div class="fv-row mb-5">
            <label class="required fw-semibold fs-6 mb-2">Target PK Triwulan '.$data->reftriwulan_id.'</label>
            <input type="text" class="form-control form-control-solid" name="triwulan_target_pk" value="'.$data->triwulan_target_pk.'" required>
        </div>
        <div class="fv-row mb-5">
            <label class="fw-semibold fs-6 mb-2">Sebab Perubahan (Keterangan)</label>
            <textarea class="form-control form-control-solid" name="triwulan_keterangan" rows="3">'.$data->triwulan_keterangan.'</textarea>
        </div>
        <input type="hidden" name="id" value="'.$data->refindikatorsasaranrenstratriwulan_id.'">
        ';

        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'triwulan_target_pk' => 'required',
        ]);

        $model = SakipIndikatorsasaranrenstraTriwulan::findOrFail($request->id);
        $model->triwulan_target_pk = $request->triwulan_target_pk;
        $model->triwulan_keterangan = $request->triwulan_keterangan;
        $model->save();

        return response()->json([
            'success' => true,
            'message' => 'Data PK Triwulan berhasil disimpan!'
        ]);
    }
}
