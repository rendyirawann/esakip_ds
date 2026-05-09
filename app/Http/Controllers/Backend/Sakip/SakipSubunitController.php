<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipSubunit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipSubunitController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipSubunit::query()->orderBy('kode_subunit');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refsubunit_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refsubunit_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->editColumn('subunit_isaktif', function($row){
                    $class = $row->subunit_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->subunit_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'subunit_isaktif'])
                ->make(true);
        }
        return view('backend.sakip.subunit.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_subunit' => 'required|string',
            'nama_subunit' => 'required|string',
        ]);

        $data = [
            'kode_subunit' => $request->kode_subunit,
            'nama_subunit' => $request->nama_subunit,
            'subunit_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipSubunit::where('refsubunit_id', $request->id)->update($data);
        } else {
            SakipSubunit::create($data);
        }

        return response()->json(['success' => 'Data Subunit berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipSubunit::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipSubunit::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Subunit berhasil dihapus.']);
    }
}
