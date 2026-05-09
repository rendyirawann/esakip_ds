<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipProgram;
use App\Models\SakipUrusan;
use App\Models\SakipBidang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipProgramController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipProgram::with(['urusan', 'bidang'])->orderBy('kode_program');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refprogram_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refprogram_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_urusan', function($row){
                    return $row->urusan->nama_urusan ?? '-';
                })
                ->addColumn('nama_bidang', function($row){
                    return $row->bidang->nama_bidang ?? '-';
                })
                ->editColumn('program_isaktif', function($row){
                    $class = $row->program_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->program_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'program_isaktif'])
                ->make(true);
        }
        $urusans = SakipUrusan::where('urusan_isaktif', 'T')->orderBy('nama_urusan')->get();
        $bidangs = SakipBidang::where('bidang_isaktif', 'T')->orderBy('nama_bidang')->get();
        return view('backend.sakip.program.index', compact('urusans', 'bidangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program' => 'required|string|max:50',
            'nama_program' => 'required|string|max:255',
            'refurusan_id' => 'required|exists:sakip_urusan,urusan_id',
            'refbidang_id' => 'required|exists:sakip_bidang,refbidang_id',
        ]);

        $data = [
            'kode_program' => $request->kode_program,
            'nama_program' => $request->nama_program,
            'refurusan_id' => $request->refurusan_id,
            'refbidang_id' => $request->refbidang_id,
            'program_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipProgram::where('refprogram_id', $request->id)->update($data);
        } else {
            SakipProgram::create($data);
        }

        return response()->json(['success' => 'Data Program berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipProgram::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipProgram::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Program berhasil dihapus.']);
    }

    public function getByBidang($bidang_id)
    {
        $data = SakipProgram::where('refbidang_id', $bidang_id)
            ->where('program_isaktif', 'T')
            ->orderBy('kode_program')
            ->get();
        return response()->json($data);
    }
}
