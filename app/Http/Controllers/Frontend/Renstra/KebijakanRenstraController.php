<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipKebijakan;
use App\Models\SakipStrategi;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class KebijakanRenstraController extends Controller
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
            // Change base query to SakipStrategi
            $query = SakipStrategi::with([
                'misi', 
                'sasaranRenstra.tujuanRpjmd',
                'kebijakan'
            ])->where('refskpd_id', $skpd_id);

            if ($request->periode_id) {
                $query->where('refperiode_id', $request->periode_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('hierarki', function($row) {
                    $html = '<div class="d-flex flex-column">';
                    $html .= '<span class="badge badge-light-success fw-bold fs-8 mb-1" style="width:fit-content">MISI</span>';
                    $html .= '<div class="text-gray-800 fs-7 mb-2">' . strip_tags($row->misi->uraian_misi ?? '-') . '</div>';
                    $html .= '<span class="badge badge-light-primary fw-bold fs-8 mb-1" style="width:fit-content">TUJUAN RPJMD</span>';
                    $html .= '<div class="text-gray-700 fs-7">' . strip_tags($row->sasaranRenstra->tujuanRpjmd->uraian_tujuan ?? '-') . '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('sasaran_strategi', function($row) {
                    $html = '<div class="mb-3">';
                    $html .= '<span class="badge badge-light-info fw-bold fs-8 mb-1">SASARAN RENSTRA</span><br>';
                    $html .= '<span class="text-gray-900 fw-bold fs-7">' . strip_tags($row->sasaranRenstra->uraian_sasaranrenstra ?? '-') . '</span>';
                    $html .= '</div>';
                    
                    $html .= '<span class="badge badge-light-success fw-bold fs-8 mb-1">STRATEGI</span>';
                    $html .= '<div class="p-2 rounded bg-light-success border border-success border-dashed fs-7 text-gray-800">' . strip_tags($row->uraian_strategi) . '</div>';
                    
                    return $html;
                })
                ->addColumn('kebijakan_list', function($row) {
                    $html = '<div class="d-flex flex-column gap-2">';
                    
                    $html .= '<div class="d-flex justify-content-between align-items-center mb-1">';
                    $html .= '<span class="fw-bold text-gray-600 fs-8 text-uppercase">DAFTAR KEBIJAKAN</span>';
                    $html .= '<button type="button" class="btn btn-sm btn-light-primary px-3 py-1 fs-8" onclick="manageKebijakan(' . $row->refstrategi_id . ', ' . $row->refsasaranrenstra_id . ')"><i class="ki-outline ki-plus fs-9 me-1"></i> Kebijakan</button>';
                    $html .= '</div>';

                    if ($row->kebijakan->isEmpty()) {
                        $html .= '<span class="text-muted fs-8 italic bg-light p-2 rounded border border-dashed">Belum ada kebijakan</span>';
                    } else {
                        foreach ($row->kebijakan as $kbj) {
                            $html .= '<div class="d-flex justify-content-between align-items-center p-2 bg-white rounded border border-gray-200 shadow-xs">';
                            $html .= '<span class="fs-7 text-gray-700">' . strip_tags($kbj->uraian_kebijakan) . '</span>';
                            $html .= '<div class="d-flex gap-1 ms-2">';
                            $html .= '<a href="javascript:void(0)" onclick="editKebijakan(' . $kbj->refkebijakan_id . ')" class="btn btn-icon btn-sm btn-active-light-warning h-20px w-20px"><i class="ki-outline ki-pencil fs-7"></i></a>';
                            $html .= '<a href="javascript:void(0)" onclick="deleteKebijakan(' . $kbj->refkebijakan_id . ')" class="btn btn-icon btn-sm btn-active-light-danger h-20px w-20px"><i class="ki-outline ki-trash fs-7"></i></a>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['hierarki', 'sasaran_strategi', 'kebijakan_list'])
                ->make(true);
        }

        return view('frontend.renstra.kebijakan.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function show($id)
    {
        $data = SakipKebijakan::findOrFail($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_kebijakan' => 'required|string',
            'refstrategi_id' => 'required',
            'refsasaranrenstra_id' => 'required',
            'refskpd_id' => 'required',
            'refperiode_id' => 'required',
        ]);

        $sasaran = SakipSasaranrenstra::find($request->refsasaranrenstra_id);
        
        $data = $request->except('id');
        $data['refmisi_id'] = $sasaran->refmisi_id;
        $data['reftujuan_id'] = $sasaran->reftujuan_id;
        $data['refsasaran_id'] = $sasaran->refsasaran_id;

        SakipKebijakan::create($data);

        return response()->json(['success' => 'Kebijakan berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $data = SakipKebijakan::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian_kebijakan' => 'required|string',
        ]);

        $model = SakipKebijakan::findOrFail($id);
        $model->update($request->only('uraian_kebijakan'));

        return response()->json(['success' => 'Kebijakan berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        SakipKebijakan::destroy($id);
        return response()->json(['success' => 'Kebijakan berhasil dihapus!']);
    }
}
