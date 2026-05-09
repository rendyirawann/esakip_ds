<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipVisi;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipVisiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipVisi::with('periode')->orderBy('refvisi_id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refvisi_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refvisi_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_periode', function($row){
                    return $row->periode->periode ?? '-';
                })
                ->editColumn('uraian_visi', function($row){
                    return \Illuminate\Support\Str::limit(strip_tags($row->uraian_visi), 100);
                })
                ->editColumn('visi_isaktif', function($row){
                    $class = $row->visi_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->visi_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'visi_isaktif'])
                ->make(true);
        }
        $periodes = SakipPeriode::where('periode_isaktif', 'T')->orderBy('periode', 'desc')->get();
        return view('backend.sakip.visi.index', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_visi' => 'required|string',
            'refperiode_id' => 'required|exists:sakip_periode,refperiode_id',
        ]);

        $data = [
            'uraian_visi' => $request->uraian_visi,
            'penjabaran_visi' => $request->penjabaran_visi,
            'refperiode_id' => $request->refperiode_id,
            'visi_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipVisi::where('refvisi_id', $request->id)->update($data);
        } else {
            SakipVisi::create($data);
        }

        return response()->json(['success' => 'Data Visi berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipVisi::findOrFail($id);
        return response()->json($data);
    }

    public function getByPeriode($periode_id)
    {
        $data = SakipVisi::where('refperiode_id', $periode_id)
                ->where('visi_isaktif', 'T')
                ->get();
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipVisi::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Visi berhasil dihapus.']);
    }
}
