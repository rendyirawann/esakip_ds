<?php

namespace App\Http\Controllers\Backend\Sakip;

use App\Http\Controllers\Controller;
use App\Models\SakipSkpd;
use App\Models\SakipPenjabatSkpd;
use App\Models\SakipUrusan;
use App\Models\SakipBidang;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SakipSkpdController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SakipSkpd::with(['urusan', 'bidang'])->orderBy('kode_skpd');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    return '
                        <div class="d-flex justify-content-end flex-shrink-0">
                            <a href="javascript:void(0)" onclick="managePenjabat('.$row->refskpd_id.', \''.$row->nama_skpd.'\')" class="btn btn-icon btn-bg-light btn-active-color-success btn-sm me-1" title="Kelola Penjabat">
                                <i class="ki-outline ki-user-square fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editData('.$row->refskpd_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refskpd_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </a>
                        </div>';
                })
                ->editColumn('skpd_isaktif', function($row){
                    $class = $row->skpd_isaktif == 'T' ? 'badge-light-success' : 'badge-light-danger';
                    $text = $row->skpd_isaktif == 'T' ? 'Aktif' : 'Non-Aktif';
                    return '<span class="badge '.$class.'">'.$text.'</span>';
                })
                ->rawColumns(['action', 'skpd_isaktif'])
                ->make(true);
        }
        $urusans = SakipUrusan::where('urusan_isaktif', 'T')->get();
        $bidangs = SakipBidang::where('bidang_isaktif', 'T')->get();
        $periodes = SakipPeriode::where('periode_isaktif', 'T')->get();
        return view('backend.sakip.skpd.index', compact('urusans', 'bidangs', 'periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_skpd' => 'required|string',
            'nama_skpd' => 'required|string',
        ]);

        $data = [
            'kode_skpd' => $request->kode_skpd,
            'nama_skpd' => $request->nama_skpd,
            'kepala_skpd' => $request->kepala_skpd,
            'nip_kepala' => $request->nip_kepala,
            'refurusan_id' => $request->refurusan_id,
            'refbidang_id' => $request->refbidang_id,
            'skpd_isaktif' => $request->has('skpd_isaktif') ? 'T' : 'F',
        ];

        if ($request->id) {
            SakipSkpd::where('refskpd_id', $request->id)->update($data);
        } else {
            SakipSkpd::create($data);
        }

        return response()->json(['success' => 'Data SKPD berhasil disimpan.']);
    }

    public function edit($id)
    {
        return response()->json(SakipSkpd::findOrFail($id));
    }

    public function destroy($id)
    {
        SakipSkpd::findOrFail($id)->delete();
        return response()->json(['success' => 'Data SKPD berhasil dihapus.']);
    }

    // --- PENJABAT SKPD HANDLERS ---

    public function getPenjabat($skpd_id)
    {
        $data = SakipPenjabatSkpd::with('periode')->where('refskpd_id', $skpd_id)->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '
                    <a href="javascript:void(0)" onclick="editPenjabat('.$row->refpenjabatskpd_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <i class="ki-outline ki-pencil fs-2"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="deletePenjabat('.$row->refpenjabatskpd_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
                        <i class="ki-outline ki-trash fs-2"></i>
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storePenjabat(Request $request)
    {
        $request->validate([
            'nama_penjabat' => 'required|string',
            'refskpd_id' => 'required',
            'refperiode_id' => 'required',
        ]);

        $data = $request->except(['penjabat_id', '_token']);

        if ($request->penjabat_id) {
            SakipPenjabatSkpd::where('refpenjabatskpd_id', $request->penjabat_id)->update($data);
        } else {
            SakipPenjabatSkpd::create($data);
        }

        return response()->json(['success' => 'Data Penjabat berhasil disimpan.']);
    }

    public function editPenjabat($id)
    {
        return response()->json(SakipPenjabatSkpd::findOrFail($id));
    }

    public function destroyPenjabat($id)
    {
        SakipPenjabatSkpd::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Penjabat berhasil dihapus.']);
    }
}
