<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipTujuanrenstra;
use App\Models\SakipTujuan;
use App\Models\SakipPeriode;
use App\Models\SakipSkpd;
use App\Models\SakipMisi;
use App\Models\SakipSasaran;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TujuanRenstraController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $skpds = [];
        if ($isSuperadmin) {
            $skpds = SakipSkpd::orderBy('nama_skpd', 'asc')->get();
        }

        return view('frontend.renstra.tujuan.index', compact('isSuperadmin', 'periodes', 'skpds'));
    }

    public function getData(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        $skpd_id = null;
        if (!$isSuperadmin) {
            $skpd_id = $user->refskpd_id ?? null;
            if (!$skpd_id) {
                $username = strtolower($user->username);
                $matchedSkpd = SakipSkpd::whereRaw('LOWER(nama_skpd) LIKE ?', ["%{$username}%"])->first();
                if ($matchedSkpd) {
                    $skpd_id = $matchedSkpd->refskpd_id;
                }
            }
        } else {
            $skpd_id = $request->skpd_id;
        }

        $periode_id = $request->periode_id;

        $query = SakipSasaranrenstra::with(['misi', 'sasaranRpjmd.tujuanRpjmd', 'tujuanRenstra'])
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id);

        $sasaranRenstra = $query->get();
        $data = [];
        $globalCounter = 1;

        foreach ($sasaranRenstra as $sasaran) {
            $misi_text = strip_tags($sasaran->misi->uraian_misi ?? '-');
            $tujuan_rpjmd_text = strip_tags($sasaran->sasaranRpjmd->tujuanRpjmd->uraian_tujuan ?? '-');
            $sasaran_renstra_text = strip_tags($sasaran->uraian_sasaranrenstra);

            if ($sasaran->tujuanRenstra->isEmpty()) {
                $data[] = [
                    'row_no' => $globalCounter++,
                    'misi' => $misi_text,
                    'tujuan_rpjmd' => $tujuan_rpjmd_text,
                    'sasaran_renstra' => $sasaran_renstra_text,
                    'uraian_tujuanrenstra' => '<span class="text-muted italic">Belum ada Tujuan Renstra</span>',
                    'action' => '<button class="btn btn-sm btn-primary w-100" onclick="addData('.$sasaran->refsasaranrenstra_id.')"><i class="fas fa-plus me-1"></i> Tambah</button>',
                    'refsasaranrenstra_id' => $sasaran->refsasaranrenstra_id,
                    'reftujuanrenstra_id' => null,
                ];
            } else {
                $totalChildren = $sasaran->tujuanRenstra->count();
                foreach ($sasaran->tujuanRenstra as $index => $tr) {
                    $isFirst = ($index === 0);
                    $isLast = ($index === $totalChildren - 1);
                    
                    $action = '<div class="d-flex justify-content-center">';
                    $action .= '<button class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="editData('.$tr->reftujuanrenstra_id.')" data-bs-toggle="tooltip" title="Edit"><i class="ki-outline ki-pencil fs-2"></i></button>';
                    $action .= '<button class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm '.($isLast ? 'me-1' : '').'" onclick="deleteData('.$tr->reftujuanrenstra_id.')" data-bs-toggle="tooltip" title="Hapus"><i class="ki-outline ki-trash fs-2"></i></button>';
                    
                    if ($isLast) {
                        $action .= '<button class="btn btn-icon btn-bg-light btn-active-color-success btn-sm" onclick="addData('.$sasaran->refsasaranrenstra_id.')" data-bs-toggle="tooltip" title="Tambah Baru"><i class="ki-outline ki-plus fs-2"></i></button>';
                    }
                    
                    $action .= '</div>';

                    $data[] = [
                        'row_no' => $isFirst ? $globalCounter++ : '',
                        'misi' => $isFirst ? $misi_text : '',
                        'tujuan_rpjmd' => $isFirst ? $tujuan_rpjmd_text : '', 
                        'sasaran_renstra' => $isFirst ? $sasaran_renstra_text : '',
                        'uraian_tujuanrenstra' => strip_tags($tr->uraian_tujuanrenstra),
                        'action' => $action,
                        'refsasaranrenstra_id' => $sasaran->refsasaranrenstra_id,
                        'reftujuanrenstra_id' => $tr->reftujuanrenstra_id,
                    ];
                }
            }
        }

        return DataTables::of($data)
            ->rawColumns(['uraian_tujuanrenstra', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_tujuanrenstra' => 'required',
            'refsasaranrenstra_id' => 'required',
        ]);

        $sasaran = SakipSasaranrenstra::find($request->refsasaranrenstra_id);
        
        $data = [
            'uraian_tujuanrenstra' => $request->uraian_tujuanrenstra,
            'refsasaranrenstra_id' => $request->refsasaranrenstra_id,
            'refskpd_id' => $sasaran->refskpd_id,
            'refperiode_id' => $sasaran->refperiode_id,
            'refmisi_id' => $sasaran->refmisi_id,
            'reftujuan_id' => $sasaran->reftujuan_id,
            'refsasaran_id' => $sasaran->refsasaran_id,
        ];
        
        if ($request->reftujuanrenstra_id) {
            $tujuan = SakipTujuanrenstra::find($request->reftujuanrenstra_id);
            $tujuan->update($data);
            return response()->json(['success' => 'Data Tujuan Renstra berhasil diperbarui']);
        } else {
            SakipTujuanrenstra::create($data);
            return response()->json(['success' => 'Data Tujuan Renstra berhasil ditambahkan']);
        }
    }

    public function show($id)
    {
        $tujuan = SakipTujuanrenstra::findOrFail($id);
        return response()->json($tujuan);
    }

    public function edit($id)
    {
        $tujuan = SakipTujuanrenstra::with('sasaranRenstra')->find($id);
        return response()->json($tujuan);
    }

    public function destroy($id)
    {
        SakipTujuanrenstra::destroy($id);
        return response()->json(['success' => 'Data Tujuan Renstra berhasil dihapus']);
    }
}
