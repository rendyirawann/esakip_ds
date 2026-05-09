<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipBidang;
use App\Models\SakipUrusan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipBidangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipBidang::with('urusan')->orderBy('kode_bidang');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="editData('.$row->refbidang_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refbidang_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-duotone ki-trash fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                            </a>
                        </div>';
                })
                ->addColumn('nama_urusan', function($row){
                    return $row->urusan->nama_urusan ?? '-';
                })
                ->editColumn('bidang_isaktif', function($row){
                    $class = $row->bidang_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->bidang_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'bidang_isaktif'])
                ->make(true);
        }
        $urusans = SakipUrusan::where('urusan_isaktif', 'T')->orderBy('nama_urusan')->get();
        return view('backend.sakip.bidang.index', compact('urusans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bidang' => 'required|string|max:50',
            'nama_bidang' => 'required|string|max:255',
            'refurusan_id' => 'required|exists:sakip_urusan,urusan_id',
        ]);

        $data = [
            'kode_bidang' => $request->kode_bidang,
            'nama_bidang' => $request->nama_bidang,
            'refurusan_id' => $request->refurusan_id,
            'bidang_isaktif' => $request->has('is_aktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipBidang::where('refbidang_id', $request->id)->update($data);
        } else {
            SakipBidang::create($data);
        }

        return response()->json(['success' => 'Data Bidang berhasil disimpan.']);
    }

    public function edit($id)
    {
        $data = SakipBidang::findOrFail($id);
        return response()->json($data);
    }

    public function destroy($id)
    {
        SakipBidang::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Bidang berhasil dihapus.']);
    }

    public function getByUrusan($urusan_id)
    {
        $data = SakipBidang::where('refurusan_id', $urusan_id)
            ->where('bidang_isaktif', 'T')
            ->orderBy('kode_bidang')
            ->get();
        return response()->json($data);
    }
}
