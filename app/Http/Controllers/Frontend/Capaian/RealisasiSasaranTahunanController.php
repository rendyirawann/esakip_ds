<?php

namespace App\Http\Controllers\Frontend\Capaian;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class RealisasiSasaranTahunanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id = $isSuperadmin ? $request->session()->get('capaian_sasaran_skpd_id') : ($user->refskpd_id ?? null);
        $periode_id = $request->session()->get('capaian_sasaran_periode_id');

        $skpds   = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();

        $current_skpd   = $skpd_id   ? SakipSkpd::find($skpd_id)   : null;
        $current_periode = $periode_id ? SakipPeriode::find($periode_id) : null;

        return view('frontend.capaian.realisasi-sasaran.tahunan', compact(
            'skpds', 'periodes', 'isSuperadmin', 'current_skpd', 'current_periode'
        ));
    }

    public function data(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id   = $request->skpd_id;
        $periode_id = $request->periode_id;

        if (!$skpd_id || !$periode_id) {
            return DataTables::of(collect([]))->make(true);
        }

        // Simpan session
        if ($isSuperadmin) {
            $request->session()->put('capaian_sasaran_skpd_id', $skpd_id);
        }
        $request->session()->put('capaian_sasaran_periode_id', $periode_id);

        $query = SakipIndikatorsasaranrenstra::select(
                'sakip_indikatorsasaranrenstra.*',
                'sakip_sasaranrenstra.uraian_sasaranrenstra'
            )
            ->join('sakip_sasaranrenstra', 'sakip_indikatorsasaranrenstra.refsasaranrenstra_id', '=', 'sakip_sasaranrenstra.refsasaranrenstra_id')
            ->where('sakip_indikatorsasaranrenstra.refskpd_id', $skpd_id)
            ->where('sakip_indikatorsasaranrenstra.refperiode_id', $periode_id)
            ->orderBy('sakip_indikatorsasaranrenstra.refsasaranrenstra_id');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('uraian_sasaran', fn($r) => $r->uraian_sasaranrenstra ?? '-')
            ->addColumn('indikator', fn($r) => $r->uraian_indikatorsasaranrenstra ?? '-')
            ->addColumn('satuan', fn($r) => $r->indikatorsasaranrenstra_satuan ?? '-')
            ->addColumn('status_iku', fn($r) => $r->iku_isaktif == 'T'
                ? '<span class="badge badge-light-success">IKU</span>' : '')
            ->addColumn('status_pk', fn($r) => $r->pk_isaktif == 'T'
                ? '<span class="badge badge-light-info">PK</span>' : '')
            ->addColumn('target_tahunan', fn($r) => $r->indikatorsasaranrenstra_target ?? '-')
            ->addColumn('target_rkt', fn($r) => $r->target_rkt ?? '-')
            ->addColumn('target_pk', fn($r) => $r->target_pk ?? '-')
            ->addColumn('realisasi_display', fn($r) => $r->realisasi ?? '-')
            ->addColumn('capaian_display', function($r) {
                $val = $r->capaian;
                if ($val === null || $val === '') return '-';
                $pct = floatval($val);
                $color = $pct >= 100 ? 'success' : ($pct >= 75 ? 'warning' : 'danger');
                return '<span class="badge badge-light-'.$color.' fw-bold">'.$val.'%</span>';
            })
            ->addColumn('analisis', fn($r) => $r->analisis ?? '-')
            ->addColumn('action', function($r) {
                return '<button type="button" class="btn btn-icon btn-light-primary btn-sm btn-edit"
                            data-id="'.$r->refindikatorsasaranrenstra_id.'" title="Input Realisasi">
                            <i class="ki-outline ki-pencil fs-2"></i>
                        </button>';
            })
            ->rawColumns(['status_iku', 'status_pk', 'capaian_display', 'action'])
            ->make(true);
    }

    public function edit($id)
    {
        $data = SakipIndikatorsasaranrenstra::findOrFail($id);
        $target_pk = $data->target_pk ?? 0;
        $html = '
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Indikator</label>
            <input type="text" class="form-control form-control-solid" value="'.e($data->uraian_indikatorsasaranrenstra).'" readonly>
        </div>
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Target PK (Referensi)</label>
            <input type="text" id="modal_target_pk" class="form-control form-control-solid bg-light-primary" value="'.e($target_pk).'" readonly>
        </div>
        <div class="row g-5 mb-5">
            <div class="col-md-6 fv-row">
                <label class="required fs-6 fw-semibold mb-2">Realisasi</label>
                <input type="text" class="form-control form-control-solid" name="realisasi" id="modal_realisasi" value="'.e($data->realisasi).'" placeholder="Input realisasi" required>
            </div>
            <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Capaian (%) <span class="text-muted fs-7">(otomatis)</span></label>
                <input type="text" class="form-control form-control-solid bg-light-success fw-bold" name="capaian" id="modal_capaian" value="'.e($data->capaian).'" placeholder="Terisi otomatis" readonly>
            </div>
        </div>
        <div class="fv-row mb-5">
            <label class="fs-6 fw-semibold mb-2">Analisis</label>
            <textarea class="form-control form-control-solid" name="analisis" rows="3" placeholder="Analisis capaian">'.e($data->analisis).'</textarea>
        </div>
        <input type="hidden" name="id" value="'.$data->refindikatorsasaranrenstra_id.'">';
        return response()->json(['html' => $html]);
    }

    public function store(Request $request)
    {
        $request->validate(['id' => 'required', 'realisasi' => 'required']);

        $model = SakipIndikatorsasaranrenstra::findOrFail($request->id);
        $model->realisasi = $request->realisasi;
        $model->capaian   = $request->capaian;
        $model->analisis  = $request->analisis;
        $model->save();

        return response()->json(['success' => true, 'message' => 'Data realisasi tahunan berhasil disimpan!']);
    }
}
