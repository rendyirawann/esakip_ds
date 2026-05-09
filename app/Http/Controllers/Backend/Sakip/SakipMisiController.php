<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipMisi;
use App\Models\SakipVisi;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipMisiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipMisi::with(['visi', 'periode'])->orderBy('refmisi_id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refmisi_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refmisi_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('uraian_visi', function($row){
                    return \Illuminate\Support\Str::limit(strip_tags($row->visi->uraian_visi ?? '-'), 50);
                })
                ->addColumn('nama_periode', function($row){
                    return $row->periode->periode ?? '-';
                })
                ->editColumn('uraian_misi', function($row){
                    return \Illuminate\Support\Str::limit(strip_tags($row->uraian_misi), 100);
                })
                ->editColumn('misi_isaktif', function($row){
                    $class = $row->misi_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->misi_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'misi_isaktif'])
                ->make(true);
        }
        $visis = SakipVisi::where('visi_isaktif', 'T')->orderBy('refvisi_id')->get();
        $periodes = SakipPeriode::where('periode_isaktif', 'T')->orderBy('periode', 'desc')->get();
        return view('backend.sakip.misi.index', compact('visis', 'periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_misi' => 'required|string',
            'refvisi_id' => 'required|exists:sakip_visi,refvisi_id',
            'refperiode_id' => 'required|exists:sakip_periode,refperiode_id',
        ]);

        $data = [
            'uraian_misi' => $request->uraian_misi,
            'refvisi_id' => $request->refvisi_id,
            'refperiode_id' => $request->refperiode_id,
            'misi_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipMisi::where('refmisi_id', $request->id)->update($data);
        } else {
            SakipMisi::create($data);
        }

        return response()->json(['success' => 'Data Misi berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipMisi::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipMisi::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Misi berhasil dihapus.']);
    }
}
