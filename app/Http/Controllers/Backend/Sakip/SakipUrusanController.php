<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipUrusan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipUrusanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipUrusan::query()->orderBy('kode_urusan');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->urusan_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->urusan_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->editColumn('urusan_isaktif', function($row){
                    $class = $row->urusan_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->urusan_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'urusan_isaktif'])
                ->make(true);
        }
        return view('backend.sakip.urusan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_urusan' => 'required|string|max:50',
            'nama_urusan' => 'required|string|max:255',
        ]);

        $data = [
            'kode_urusan' => $request->kode_urusan,
            'nama_urusan' => $request->nama_urusan,
            'urusan_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipUrusan::where('urusan_id', $request->id)->update($data);
        } else {
            SakipUrusan::create($data);
        }

        return response()->json(['success' => 'Data Urusan berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipUrusan::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipUrusan::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Urusan berhasil dihapus.']);
    }
}
