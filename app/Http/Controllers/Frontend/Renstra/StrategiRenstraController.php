<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipStrategi;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class StrategiRenstraController extends Controller
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
            $query = SakipSasaranrenstra::with([
                'misi', 
                'tujuanRpjmd', 
                'strategiRenstra'
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
                    $html .= '<div class="text-gray-700 fs-7">' . strip_tags($row->tujuanRpjmd->uraian_tujuan ?? '-') . '</div>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('sasaran_renstra', function($row) {
                    return '<span class="badge badge-light-info fw-bold fs-8 mb-1">SASARAN RENSTRA</span><br>' . 
                           '<span class="text-gray-900 fw-bold fs-7">' . strip_tags($row->uraian_sasaranrenstra) . '</span>';
                })
                ->addColumn('strategi_list', function($row) {
                    $html = '<div class="d-flex flex-column gap-2">';
                    
                    if ($row->strategiRenstra->isEmpty()) {
                        $html .= '<span class="text-muted fs-7 italic">Belum ada strategi</span>';
                    } else {
                        foreach ($row->strategiRenstra as $str) {
                            $html .= '<div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border border-gray-200">';
                            $html .= '<span class="fs-7 text-gray-700">' . strip_tags($str->uraian_strategi) . '</span>';
                            $html .= '<div class="d-flex gap-1 ms-2">';
                            $html .= '<a href="javascript:void(0)" onclick="editStrategi(' . $str->refstrategi_id . ')" class="btn btn-icon btn-sm btn-active-light-warning h-20px w-20px"><i class="ki-outline ki-pencil fs-7"></i></a>';
                            $html .= '<a href="javascript:void(0)" onclick="deleteStrategi(' . $str->refstrategi_id . ')" class="btn btn-icon btn-sm btn-active-light-danger h-20px w-20px"><i class="ki-outline ki-trash fs-7"></i></a>';
                            $html .= '</div>';
                            $html .= '</div>';
                        }
                    }
                    
                    $html .= '<button type="button" class="btn btn-sm btn-light-success btn-dashed mt-2 w-100" onclick="manageStrategi(' . $row->refsasaranrenstra_id . ')">';
                    $html .= '<i class="ki-outline ki-plus fs-4 me-1"></i> Tambah Strategi</button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['hierarki', 'sasaran_renstra', 'strategi_list'])
                ->make(true);
        }

        return view('frontend.renstra.strategi.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function show($id)
    {
        $data = SakipStrategi::findOrFail($id);
        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_strategi' => 'required|string',
            'refsasaranrenstra_id' => 'required',
            'refskpd_id' => 'required',
            'refperiode_id' => 'required',
        ]);

        $sasaran = SakipSasaranrenstra::find($request->refsasaranrenstra_id);
        
        $data = $request->except('id');
        $data['refmisi_id'] = $sasaran->refmisi_id;
        $data['reftujuan_id'] = $sasaran->reftujuan_id;
        $data['refsasaran_id'] = $sasaran->refsasaran_id;

        SakipStrategi::create($data);

        return response()->json(['success' => 'Strategi berhasil ditambahkan!']);
    }

    public function edit($id)
    {
        $data = SakipStrategi::findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian_strategi' => 'required|string',
        ]);

        $model = SakipStrategi::findOrFail($id);
        $model->update($request->only('uraian_strategi'));

        return response()->json(['success' => 'Strategi berhasil diperbarui!']);
    }

    public function destroy($id)
    {
        SakipStrategi::destroy($id);
        return response()->json(['success' => 'Strategi berhasil dihapus!']);
    }
}
