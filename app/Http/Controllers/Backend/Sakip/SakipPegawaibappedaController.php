<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipPegawaibappeda;
use App\Models\SakipTitle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipPegawaibappedaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipPegawaibappeda::with('title')->orderBy('nama_pegawai');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refpegawai_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refpegawai_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_title', function($row){
                    return $row->title->nama_title ?? '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $titles = SakipTitle::all();
        return view('backend.sakip.pegawaibappeda.index', compact('titles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pegawai' => 'required|string',
            'nip' => 'required|string',
        ]);

        $data = [
            'nama_pegawai' => $request->nama_pegawai,
            'nip' => $request->nip,
            'reftitle_id' => $request->reftitle_id,
        ];

        if ($request->id) {
            SakipPegawaibappeda::where('refpegawai_id', $request->id)->update($data);
        } else {
            SakipPegawaibappeda::create($data);
        }

        return response()->json(['success' => 'Data Pegawai Bappeda berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipPegawaibappeda::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipPegawaibappeda::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Pegawai Bappeda berhasil dihapus.']);
    }
}
