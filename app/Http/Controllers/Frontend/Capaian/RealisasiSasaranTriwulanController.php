<?php

namespace App\Http\Controllers\Frontend\Capaian;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstraTriwulan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RealisasiSasaranTriwulanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id    = $isSuperadmin ? $request->session()->get('capaian_sasaran_tw_skpd_id') : ($user->refskpd_id ?? null);
        $periode_id = $request->session()->get('capaian_sasaran_tw_periode_id');
        $current_triwulan = $request->session()->get('capaian_sasaran_tw_triwulan', 1);

        $skpds   = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();

        $current_skpd   = $skpd_id   ? SakipSkpd::find($skpd_id)   : null;
        $current_periode = $periode_id ? SakipPeriode::find($periode_id) : null;

        return view('frontend.capaian.realisasi-sasaran.triwulan', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 'current_periode', 'current_triwulan'
        ));
    }

    public function data(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id    = $request->skpd_id;
        $periode_id = $request->periode_id;
        $triwulan_id = $request->triwulan_id;

        if (!$skpd_id || !$periode_id || !$triwulan_id) {
            return DataTables::of(collect([]))->make(true);
        }

        // Simpan session
        if ($isSuperadmin) {
            $request->session()->put('capaian_sasaran_tw_skpd_id', $skpd_id);
        }
        $request->session()->put('capaian_sasaran_tw_periode_id', $periode_id);
        $request->session()->put('capaian_sasaran_tw_triwulan', $triwulan_id);

        $query = SakipIndikatorsasaranrenstraTriwulan::select(
                'sakip_indikatorsasaranrenstra_triwulan.*',
                'sakip_indikatorsasaranrenstra.uraian_indikatorsasaranrenstra',
                'sakip_indikatorsasaranrenstra.indikatorsasaranrenstra_satuan',
                'sakip_indikatorsasaranrenstra.iku_isaktif',
                'sakip_indikatorsasaranrenstra.pk_isaktif',
                'sakip_indikatorsasaranrenstra.target_pk',
                'sakip_sasaranrenstra.uraian_sasaranrenstra'
            )
            ->join('sakip_indikatorsasaranrenstra', 'sakip_indikatorsasaranrenstra_triwulan.refindikatorsasaranrenstra_id', '=', 'sakip_indikatorsasaranrenstra.refindikatorsasaranrenstra_id')
            ->join('sakip_sasaranrenstra', 'sakip_indikatorsasaranrenstra.refsasaranrenstra_id', '=', 'sakip_sasaranrenstra.refsasaranrenstra_id')
            ->where('sakip_indikatorsasaranrenstra_triwulan.refskpd_id', $skpd_id)
            ->where('sakip_indikatorsasaranrenstra_triwulan.refperiode_id', $periode_id)
            ->where('sakip_indikatorsasaranrenstra_triwulan.reftriwulan_id', $triwulan_id)
            ->orderBy('sakip_indikatorsasaranrenstra_triwulan.refsasaranrenstra_id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', fn($r) => $r->uraian_sasaranrenstra ?? '-')
            ->addColumn('indikator', fn($r) => $r->uraian_indikatorsasaranrenstra ?? '-')
            ->addColumn('satuan', fn($r) => $r->indikatorsasaranrenstra_satuan ?? '-')
            ->addColumn('status_iku', fn($r) => $r->iku_isaktif == 'T'
                ? '<span class="badge badge-light-success">IKU</span>' : '')
            ->addColumn('status_pk', fn($r) => $r->pk_isaktif == 'T'
                ? '<span class="badge badge-light-info">PK</span>' : '')
            ->addColumn('target_pk_tahunan', fn($r) => $r->target_pk ?? '-')
            ->addColumn('target_pk_triwulan', fn($r) => $r->triwulan_target_pk ?? '-')
            ->addColumn('realisasi_display', fn($r) => $r->triwulan_realisasi ?? '-')
            ->addColumn('capaian_display', function($r) {
                $val = $r->triwulan_capaian;
                if ($val === null || $val === '') return '-';
                $pct = floatval($val);
                $color = $pct >= 100 ? 'success' : ($pct >= 75 ? 'warning' : 'danger');
                return '<span class="badge badge-light-'.$color.' fw-bold">'.$val.'%</span>';
            })
            ->addColumn('keterangan', fn($r) => $r->triwulan_keterangan ?? '-')
            ->addColumn('analisis', fn($r) => $r->triwulan_analisis ?? '-')
            ->addColumn('action', function($r) {
                return '<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit"
                            data-id="'.$r->refindikatorsasaranrenstratriwulan_id.'" title="Input Realisasi">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['status_iku', 'status_pk', 'capaian_display', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = SakipIndikatorsasaranrenstraTriwulan::findOrFail($id);
        // Load target_pk tahunan dari tabel indikator
        $indikator = \App\Models\SakipIndikatorsasaranrenstra::find($data->refindikatorsasaranrenstra_id);
        $target_pk_tahunan = $indikator->target_pk ?? 0;
        $target_pk_triwulan = $data->triwulan_target_pk ?? 0;

        $html = '
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Target PK Tahunan</label>
            <input type="text" class="form-control form-control-solid bg-light" value="'.e($target_pk_tahunan).'" readonly>
        </div>
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Target PK Triwulan '.$data->reftriwulan_id.' (Referensi)</label>
            <input type="text" id="modal_target_pk" class="form-control form-control-solid bg-light-primary" value="'.e($target_pk_triwulan).'" readonly>
        </div>
        <div class="row g-5 mb-5">
            <div class="col-md-6 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Realisasi Triwulan '.$data->reftriwulan_id.'</label>
                <input type="text" class="form-control form-control-solid" name="triwulan_realisasi" id="modal_realisasi" value="'.e($data->triwulan_realisasi).'" placeholder="Input realisasi" required>
            </div>
            <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Capaian (%) <span class="text-muted fs-7">(otomatis)</span></label>
                <input type="text" class="form-control form-control-solid bg-light-success fw-bold" name="triwulan_capaian" id="modal_capaian" value="'.e($data->triwulan_capaian).'" placeholder="Terisi otomatis" readonly>
            </div>
        </div>
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Keterangan</label>
            <input type="text" class="form-control form-control-solid" name="triwulan_keterangan" value="'.e($data->triwulan_keterangan).'" placeholder="Keterangan">
        </div>
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Analisis</label>
            <textarea class="form-control form-control-solid" name="triwulan_analisis" rows="3" placeholder="Analisis capaian">'.e($data->triwulan_analisis).'</textarea>
        </div>
        <input type="hidden" name="id" value="'.$data->refindikatorsasaranrenstratriwulan_id.'">';
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $request->validate(['id' => 'required', 'triwulan_realisasi' => 'required']);

        $model = SakipIndikatorsasaranrenstraTriwulan::findOrFail($request->id);
        $model->triwulan_realisasi  = $request->triwulan_realisasi;
        $model->triwulan_capaian    = $request->triwulan_capaian;
        $model->triwulan_keterangan = $request->triwulan_keterangan;
        $model->triwulan_analisis   = $request->triwulan_analisis;
        $model->save();

        return response()->json(['success' => true, 'message' => 'Data realisasi triwulan berhasil disimpan!']);
    }
}
