<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipKegiatan;
use App\Models\SakipUrusan;
use App\Models\SakipBidang;
use App\Models\SakipProgram;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipKegiatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipKegiatan::with(['urusan', 'bidang', 'program'])->orderBy('kode_kegiatan');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refkegiatan_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refkegiatan_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_program', function($row){
                    return $row->program->nama_program ?? '-';
                })
                ->editColumn('kegiatan_isaktif', function($row){
                    $class = $row->kegiatan_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->kegiatan_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'kegiatan_isaktif'])
                ->make(true);
        }
        $urusans = SakipUrusan::where('urusan_isaktif', 'T')->get();
        $bidangs = SakipBidang::where('bidang_isaktif', 'T')->get();
        $programs = SakipProgram::where('program_isaktif', 'T')->get();
        return view('backend.sakip.kegiatan.index', compact('urusans', 'bidangs', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kegiatan' => 'required|string',
            'nama_kegiatan' => 'required|string',
            'refprogram_id' => 'required|exists:sakip_program,refprogram_id',
        ]);

        $data = [
            'kode_kegiatan' => $request->kode_kegiatan,
            'nama_kegiatan' => $request->nama_kegiatan,
            'refurusan_id' => $request->refurusan_id,
            'refbidang_id' => $request->refbidang_id,
            'refprogram_id' => $request->refprogram_id,
            'kegiatan_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipKegiatan::where('refkegiatan_id', $request->id)->update($data);
        } else {
            SakipKegiatan::create($data);
        }

        return response()->json(['success' => 'Data Kegiatan berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipKegiatan::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipKegiatan::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Kegiatan berhasil dihapus.']);
    }

    public function getByProgram($program_id)
    {
        $data = SakipKegiatan::where('refprogram_id', $program_id)
            ->where('kegiatan_isaktif', 'T')
            ->orderBy('kode_kegiatan')
            ->get();
        return response()->json($data);
    }
}
