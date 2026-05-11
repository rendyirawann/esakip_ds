<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipIndikatorsasaranrenstra;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class FormulasiRenstraController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        
        $skpd_id = $request->skpd_id;
        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
            if (!$skpd_id) {
                $username = strtolower($user->username);
                $matchedSkpd = SakipSkpd::whereRaw('LOWER(nama_skpd) LIKE ?', ["%{$username}%"])->first();
                if ($matchedSkpd) {
                    $skpd_id = $matchedSkpd->refskpd_id;
                }
            }
        }

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;

        if ($request->ajax()) {
            $query = SakipIndikatorsasaranrenstra::with([
                'skpd',
                'periode'
            ])->where('sakip_indikatorsasaranrenstra.refskpd_id', $skpd_id);

            if ($request->periode_id) {
                $query->where('sakip_indikatorsasaranrenstra.refperiode_id', $request->periode_id);
            }

            // Join with Sasaran to get Sasaran name for grouping
            $query->join('sakip_sasaranrenstra', 'sakip_indikatorsasaranrenstra.refsasaranrenstra_id', '=', 'sakip_sasaranrenstra.refsasaranrenstra_id')
                  ->select('sakip_indikatorsasaranrenstra.*', 'sakip_sasaranrenstra.uraian_sasaranrenstra');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('indikator_detail', function($row) {
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="text-gray-900 fw-bold fs-6">' . strip_tags($row->uraian_indikatorsasaranrenstra) . '</span>';
                    $html .= '<div class="mt-3 ps-4 border-start border-gray-300 border-3">';
                    $html .= '<div class="mb-1"><span class="fw-bold text-gray-600 fs-8 text-uppercase">Alasan:</span> <span class="fs-7 text-gray-700">' . ($row->alasan_sasaranrenstra ?? '-') . '</span></div>';
                    $html .= '<div class="mb-1"><span class="fw-bold text-gray-600 fs-8 text-uppercase">Cara Pengukuran:</span> <span class="fs-7 text-gray-700">' . ($row->formulasi_sasaranrenstra ?? '-') . '</span></div>';
                    $html .= '<div class="mb-1"><span class="fw-bold text-gray-600 fs-8 text-uppercase">Kriteria:</span> <span class="fs-7 text-gray-700">' . ($row->kriteria_sasaranrenstra ?? '-') . '</span></div>';
                    $html .= '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('status_aktif', function($row) {
                    $iku = $row->iku_isaktif == 'T' ? '<span class="badge badge-light-success fs-9 px-2 py-1">AKTIF</span>' : '<span class="badge badge-light-danger fs-9 px-2 py-1">NON-AKTIF</span>';
                    $pk = $row->pk_isaktif == 'T' ? '<span class="badge badge-light-success fs-9 px-2 py-1">AKTIF</span>' : '<span class="badge badge-light-danger fs-9 px-2 py-1">NON-AKTIF</span>';
                    
                    return '<div class="d-flex flex-column gap-2">' .
                           '<div><span class="fw-bold text-gray-600 fs-9 text-uppercase">IKU:</span> ' . $iku . '</div>' .
                           '<div><span class="fw-bold text-gray-600 fs-9 text-uppercase">PK:</span> ' . $pk . '</div>' .
                           '</div>';
                })
                ->addColumn('action', function($row) {
                    return '<button type="button" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="editFormulasi(' . $row->refindikatorsasaranrenstra_id . ')">
                                <i class="ki-outline ki-pencil fs-3"></i>
                            </button>';
                })
                ->rawColumns(['indikator_detail', 'status_aktif', 'action'])
                ->make(true);
        }

        return view('frontend.renstra.formulasi.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function edit($id)
    {
        $data = SakipIndikatorsasaranrenstra::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'indikatorsasaranrenstra_satuan' => 'required',
            'alasan_sasaranrenstra' => 'nullable',
            'formulasi_sasaranrenstra' => 'nullable',
            'kriteria_sasaranrenstra' => 'nullable',
            'iku_isaktif' => 'required|in:T,F',
            'pk_isaktif' => 'required|in:T,F',
        ]);

        $model = SakipIndikatorsasaranrenstra::findOrFail($id);
        $model->update($request->only([
            'indikatorsasaranrenstra_satuan',
            'alasan_sasaranrenstra',
            'formulasi_sasaranrenstra',
            'kriteria_sasaranrenstra',
            'iku_isaktif',
            'pk_isaktif'
        ]));

        return response()->json(['success' => 'Formulasi berhasil diperbarui!']);
    }
}
