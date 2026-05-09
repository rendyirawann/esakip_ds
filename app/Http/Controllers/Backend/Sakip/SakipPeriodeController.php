<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipPeriodeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipPeriode::query()->orderBy('periode', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refperiode_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refperiode_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->editColumn('periode_isaktif', function($row){
                    $class = $row->periode_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->periode_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'periode_isaktif'])
                ->make(true);
        }
        return view('backend.sakip.periode.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required|string|max:100',
        ]);

        $data = [
            'periode' => $request->periode,
            'periode_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipPeriode::where('refperiode_id', $request->id)->update($data);
        } else {
            SakipPeriode::create($data);
        }

        return response()->json(['success' => 'Data Periode berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipPeriode::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipPeriode::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Periode berhasil dihapus.']);
    }
}
