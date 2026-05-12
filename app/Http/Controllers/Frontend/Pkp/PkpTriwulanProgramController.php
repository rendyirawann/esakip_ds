<?php

namespace App\Http\Controllers\Frontend\Pkp;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorcascadingprogramTriwulan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PkpTriwulanProgramController extends Controller
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

        return view('frontend.pkp.triwulan.program', compact(
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

        $query = SakipIndikatorcascadingprogramTriwulan::select(
                'sakip_indikatorcascadingprogram_triwulan.*', 
                'sakip_indikatorcascadingprogram.target_pk_p', 
                'sakip_cascadingprogram.uraian_indikatorprogram', 
                'sakip_cascadingprogram.program_satuan', 
                'sakip_program.nama_program'
            )
            ->join('sakip_indikatorcascadingprogram', 'sakip_indikatorcascadingprogram_triwulan.refindikatorprogram_id', '=', 'sakip_indikatorcascadingprogram.refindikatorprogram_id')
            ->join('sakip_cascadingprogram', 'sakip_indikatorcascadingprogram.refcascadingprogram_id', '=', 'sakip_cascadingprogram.refcascadingprogram_id')
            ->join('sakip_program', 'sakip_cascadingprogram.refprogram_id', '=', 'sakip_program.refprogram_id')
            ->where('sakip_indikatorcascadingprogram_triwulan.refskpd_id', $skpd_id)
            ->where('sakip_indikatorcascadingprogram_triwulan.refperiode_id', $periode_id)
            ->where('sakip_indikatorcascadingprogram_triwulan.reftriwulan_id', $triwulan_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_program', function($row) { return $row->nama_program ?? '-'; })
            ->addColumn('indikator', function($row) { return $row->uraian_indikatorprogram ?? '-'; })
            ->addColumn('satuan', function($row) { return $row->program_satuan ?? '-'; })
            ->addColumn('target_pkp_tahunan', function($row) { return $row->target_pk_p ?? '-'; })
            ->addColumn('target_pkp_triwulan', function($row) { return $row->triwulan_target_pk_p ?? '-'; })
            ->addColumn('keterangan', function($row) { return $row->triwulan_keterangan_pk_p ?? '-'; })
            ->addColumn('action', function($row) {
                return '<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit" data-id="'.$row->refindikatorprogramtriwulan_id.'"><i class="ki-outline ki-pencil fs-2"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = SakipIndikatorcascadingprogramTriwulan::findOrFail($id);
        $html = '
        <div class="fv-row mb-5">
            <label class="required fw-semibold fs-6 mb-2">Target PK Perubahan Triwulan '.$data->reftriwulan_id.'</label>
            <input type="text" class="form-control form-control-solid" name="triwulan_target_pk_p" value="'.$data->triwulan_target_pk_p.'" required>
        </div>
        <div class="fv-row mb-5">
            <label class="fw-semibold fs-6 mb-2">Sebab Perubahan</label>
            <textarea class="form-control form-control-solid" name="triwulan_keterangan_pk_p" rows="3">'.$data->triwulan_keterangan_pk_p.'</textarea>
        </div>
        <input type="hidden" name="id" value="'.$data->refindikatorprogramtriwulan_id.'">';
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $model = SakipIndikatorcascadingprogramTriwulan::findOrFail($request->id);
        $model->triwulan_target_pk_p = $request->triwulan_target_pk_p;
        $model->triwulan_keterangan_pk_p = $request->triwulan_keterangan_pk_p;
        $model->save();
        return response()->json(['success' => true, 'message' => 'Data PK Perubahan Triwulan Program berhasil disimpan!']);
    }
}
