<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipPimpinan;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipPimpinanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipPimpinan::with('periode')->orderBy('refpimpinan_id');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refpimpinan_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refpimpinan_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_periode', function($row){
                    return $row->periode->periode ?? '-';
                })
                ->addColumn('jabatan_pimpinan', function($row){
                    return $row->jabatan_pimpinan ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $periodes = SakipPeriode::where('periode_isaktif', 'T')->get();
        return view('backend.sakip.pimpinan.index', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pimpinan' => 'required|string',
            'refperiode_id' => 'required|exists:sakip_periode,refperiode_id',
        ]);

        $data = [
            'nama_pimpinan' => $request->nama_pimpinan,
            'jabatan_pimpinan' => $request->jabatan_pimpinan,
            'refperiode_id' => $request->refperiode_id,
            'nama_wpimpinan' => $request->nama_wpimpinan ?? '',
            'jabatan_wpimpinan' => $request->jabatan_wpimpinan ?? '',
        ];

        if ($request->id) {
            SakipPimpinan::where('refpimpinan_id', $request->id)->update($data);
        } else {
            SakipPimpinan::create($data);
        }

        return response()->json(['success' => 'Data Pimpinan berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipPimpinan::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipPimpinan::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Pimpinan berhasil dihapus.']);
    }
}
