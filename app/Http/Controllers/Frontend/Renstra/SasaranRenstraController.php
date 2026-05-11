<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSasaran;
use App\Models\SakipPeriode;
use App\Models\SakipSkpd;
use App\Models\SakipVisi;
use App\Models\SakipMisi;
use App\Models\SakipTujuan;
use App\Models\SakipTujuanrenstra;
use App\Models\SakipIndikatorsasaranrenstra;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SasaranRenstraController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id = null;
        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
            if (!$skpd_id) {
                $username = strtolower($user->username);
                $matchedSkpd = SakipSkpd::whereRaw('LOWER(nama_skpd) LIKE ?', ["%{$username}%"])->first();
                if ($matchedSkpd) {
                    $skpd_id = $matchedSkpd->refskpd_id;
                }
            }
        } else {
            $skpd_id = $request->skpd_id;
        }

        if ($request->ajax()) {
            // Eager load everything needed
            $query = SakipSasaranrenstra::with(['sasaranRpjmd', 'periode', 'skpd', 'tujuanRpjmd', 'linkedTujuanRenstra']);
            
            if ($skpd_id) {
                $query->where('refskpd_id', $skpd_id);
            }

            if ($request->periode_id) {
                $query->where('refperiode_id', $request->periode_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_skpd', function($row) {
                    return $row->skpd->nama_skpd ?? '-';
                })
                ->addColumn('sasaran_rpjmd', function($row) {
                    return strip_tags($row->sasaranRpjmd->uraian_sasaran ?? '-');
                })
                ->addColumn('tujuan_renstra', function($row) {
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="text-gray-800 fw-bold mb-1"><span class="badge badge-light-primary me-2">RPJMD</span>' . strip_tags($row->tujuanRpjmd->uraian_tujuan ?? '-') . '</span>';
                    
                    if ($row->linkedTujuanRenstra) {
                        $html .= '<span class="text-gray-600 fs-7"><i class="ki-outline ki-cloud-change fs-6 text-success me-1"></i>' . strip_tags($row->linkedTujuanRenstra->uraian_tujuanrenstra) . '</span>';
                    } else {
                        $html .= '<span class="text-muted fs-7 italic">Belum ditautkan ke Tujuan Renstra</span>';
                    }
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="d-flex justify-content-center flex-shrink-0">
                            <a href="javascript:void(0)" onclick="linkTujuan('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tautkan Tujuan Renstra">
                                <i class="ki-outline ki-cloud-change fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="manageIndikator('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Kelola Indikator">
                                <i class="ki-outline ki-plus-square fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="showData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Detail">
                                <i class="ki-outline ki-eye fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Sasaran">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Sasaran">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['action', 'sasaran_rpjmd', 'tujuan_renstra'])
                ->make(true);
        }

        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $skpds = $isSuperadmin ? SakipSkpd::orderBy('nama_skpd')->get() : null;
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;

        return view('frontend.renstra.sasaran.index', compact('periodes', 'skpds', 'isSuperadmin', 'current_skpd'));
    }

    public function show($id)
    {
        $sasaran = SakipSasaranrenstra::with(['skpd', 'periode', 'tujuanRpjmd', 'sasaranRpjmd', 'tujuanRenstra'])->findOrFail($id);
        return response()->json($sasaran);
    }

    public function edit($id)
    {
        $sasaran = SakipSasaranrenstra::with(['skpd', 'periode', 'tujuanRpjmd'])->findOrFail($id);
        return response()->json($sasaran);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_sasaranrenstra' => 'required|string',
            'refskpd_id' => 'required',
            'refperiode_id' => 'required',
            'refsasaran_id' => 'required',
        ]);

        $sasaranRpjmd = SakipSasaran::find($request->refsasaran_id);
        $data = $request->all();
        $data['refvisi_id'] = $sasaranRpjmd->refvisi_id ?? null;
        $data['refmisi_id'] = $sasaranRpjmd->refmisi_id ?? null;
        $data['reftujuan_id'] = $sasaranRpjmd->reftujuan_id ?? null;
        $data['sasaranrenstra_isaktif'] = 'T';

        SakipSasaranrenstra::create($data);

        return response()->json(['success' => 'Data Sasaran Renstra berhasil disimpan.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian_sasaranrenstra' => 'required|string',
            'refperiode_id' => 'required',
            'refsasaran_id' => 'required',
        ]);

        $sasaranRpjmd = SakipSasaran::find($request->refsasaran_id);
        $data = $request->except('id');
        $data['refvisi_id'] = $sasaranRpjmd->refvisi_id ?? null;
        $data['refmisi_id'] = $sasaranRpjmd->refmisi_id ?? null;
        $data['reftujuan_id'] = $sasaranRpjmd->reftujuan_id ?? null;

        SakipSasaranrenstra::findOrFail($id)->update($data);

        return response()->json(['success' => 'Data Sasaran Renstra berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        SakipSasaranrenstra::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Sasaran Renstra berhasil dihapus.']);
    }

    public function linkTujuanPost(Request $request, $id)
    {
        $request->validate([
            'reftujuanrenstra_id' => 'required',
        ]);

        SakipSasaranrenstra::findOrFail($id)->update([
            'reftujuanrenstra_id' => $request->reftujuanrenstra_id
        ]);

        return response()->json(['success' => 'Berhasil mentautkan Tujuan Renstra ke Sasaran Renstra.']);
    }

    public function getSasaranRpjmd($periode_id)
    {
        $data = SakipSasaran::where('refperiode_id', $periode_id)->where('sasaran_isaktif', 'T')->get();
        return response()->json($data);
    }

    public function getTujuanRenstra($skpd_id, $periode_id)
    {
        $data = SakipTujuanrenstra::where('refskpd_id', $skpd_id)
                                 ->where('refperiode_id', $periode_id)
                                 ->get();
        return response()->json($data);
    }

    // --- Indikator Sasaran Renstra Methods ---

    public function getIndikators($sasaran_id)
    {
        $indikators = SakipIndikatorsasaranrenstra::where('refsasaranrenstra_id', $sasaran_id)->get();
        return response()->json($indikators);
    }

    public function storeIndikator(Request $request)
    {
        $request->validate([
            'refsasaranrenstra_id' => 'required',
            'uraian_indikatorsasaranrenstra' => 'required',
            'indikatorsasaranrenstra_target' => 'required',
            'indikatorsasaranrenstra_satuan' => 'required',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $sasaran = SakipSasaranrenstra::find($request->refsasaranrenstra_id);
            
            // Sync sequences
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorsasaranrenstra', 'refindikatorsasaranrenstra_id'), coalesce(max(refindikatorsasaranrenstra_id), 0) + 1, false) FROM sakip_indikatorsasaranrenstra");
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorsasaranrenstra_triwulan', 'refindikatorsasaranrenstratriwulan_id'), coalesce(max(refindikatorsasaranrenstratriwulan_id), 0) + 1, false) FROM sakip_indikatorsasaranrenstra_triwulan");

            $data = $request->except(['id', 'indikator_id']);
            $data['refskpd_id'] = $sasaran->refskpd_id;
            $data['refperiode_id'] = $sasaran->refperiode_id;
            $data['indikatorsasaranrenstra_isaktif'] = $request->indikatorsasaranrenstra_isaktif ?? 'T';
            $data['iku_isaktif'] = $request->iku_isaktif ?? 'F';
            $data['pk_isaktif'] = $request->pk_isaktif ?? 'F';

            $indikator = SakipIndikatorsasaranrenstra::create($data);

            // Create 4 Triwulan rows
            for ($i = 1; $i <= 4; $i++) {
                \App\Models\SakipIndikatorsasaranrenstraTriwulan::create([
                    'refindikatorsasaranrenstra_id' => $indikator->refindikatorsasaranrenstra_id,
                    'refsasaranrenstra_id' => $sasaran->refsasaranrenstra_id,
                    'refskpd_id' => $sasaran->refskpd_id,
                    'refperiode_id' => $sasaran->refperiode_id,
                    'reftriwulan_id' => $i,
                    'triwulan_target_rkt' => $indikator->indikatorsasaranrenstra_target,
                    'triwulan_target_pk' => $indikator->indikatorsasaranrenstra_target,
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => 'Indikator berhasil disimpan.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editIndikator($id)
    {
        $indikator = SakipIndikatorsasaranrenstra::findOrFail($id);
        return response()->json($indikator);
    }

    public function updateIndikator(Request $request, $id)
    {
        $request->validate([
            'uraian_indikatorsasaranrenstra' => 'required',
            'indikatorsasaranrenstra_target' => 'required',
            'indikatorsasaranrenstra_satuan' => 'required',
        ]);

        $data = $request->except(['id', 'indikator_id']);
        SakipIndikatorsasaranrenstra::findOrFail($id)->update($data);

        return response()->json(['success' => 'Indikator berhasil diperbarui.']);
    }

    public function deleteIndikator($id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $indikator = SakipIndikatorsasaranrenstra::findOrFail($id);
            // Delete associated triwulan
            \App\Models\SakipIndikatorsasaranrenstraTriwulan::where('refindikatorsasaranrenstra_id', $id)->delete();
            // Delete the indicator
            $indikator->delete();

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['success' => 'Indikator berhasil dihapus.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
