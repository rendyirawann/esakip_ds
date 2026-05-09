<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipSubkegiatan;
use App\Models\SakipUrusan;
use App\Models\SakipBidang;
use App\Models\SakipProgram;
use App\Models\SakipKegiatan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipSubkegiatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipSubkegiatan::with(['urusan', 'bidang', 'program', 'kegiatan'])->orderBy('kode_subkegiatan');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refsubkegiatan_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refsubkegiatan_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_kegiatan', function($row){
                    return $row->kegiatan->nama_kegiatan ?? '-';
                })
                ->editColumn('subkegiatan_isaktif', function($row){
                    $class = $row->subkegiatan_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->subkegiatan_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'subkegiatan_isaktif'])
                ->make(true);
        }
        $urusans = SakipUrusan::where('urusan_isaktif', 'T')->get();
        $bidangs = SakipBidang::where('bidang_isaktif', 'T')->get();
        $programs = SakipProgram::where('program_isaktif', 'T')->get();
        $kegiatans = SakipKegiatan::where('kegiatan_isaktif', 'T')->get();
        return view('backend.sakip.subkegiatan.index', compact('urusans', 'bidangs', 'programs', 'kegiatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_subkegiatan' => 'required|string',
            'nama_subkegiatan' => 'required|string',
            'refkegiatan_id' => 'required|exists:sakip_kegiatan,refkegiatan_id',
        ]);

        $data = [
            'kode_subkegiatan' => $request->kode_subkegiatan,
            'nama_subkegiatan' => $request->nama_subkegiatan,
            'refurusan_id' => $request->refurusan_id,
            'refbidang_id' => $request->refbidang_id,
            'refprogram_id' => $request->refprogram_id,
            'refkegiatan_id' => $request->refkegiatan_id,
            'subkegiatan_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipSubkegiatan::where('refsubkegiatan_id', $request->id)->update($data);
        } else {
            SakipSubkegiatan::create($data);
        }

        return response()->json(['success' => 'Data Sub Kegiatan berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipSubkegiatan::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipSubkegiatan::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Sub Kegiatan berhasil dihapus.']);
    }
}
