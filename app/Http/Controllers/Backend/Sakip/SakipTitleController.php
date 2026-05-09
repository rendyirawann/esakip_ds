<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipTitle;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipTitleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipTitle::query()->orderBy('nama_title');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->reftitle_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->reftitle_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.sakip.title.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_title' => 'required|string|max:255',
        ]);

        $data = ['nama_title' => $request->nama_title];

        if ($request->id) {
            SakipTitle::where('reftitle_id', $request->id)->update($data);
        } else {
            SakipTitle::create($data);
        }

        return response()->json(['success' => 'Data Title berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipTitle::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipTitle::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Title berhasil dihapus.']);
    }
}
