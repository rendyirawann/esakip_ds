<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipUnitkerja;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipUnitkerjaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipUnitkerja::query()->orderBy('kode');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.sakip.unitkerja.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'kode' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->except(['id', '_token']);

        if ($request->id) {
            SakipUnitkerja::where('id', $request->id)->update($data);
        } else {
            SakipUnitkerja::create($data);
        }

        return response()->json(['success' => 'Data Unit Kerja berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipUnitkerja::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipUnitkerja::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Unit Kerja berhasil dihapus.']);
    }
}
