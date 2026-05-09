<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SakipSasaranrenstra;
use App\Models\SakipSasaran;
use App\Models\SakipPeriode;
use App\Models\SakipSkpd;
use App\Models\SakipVisi;
use App\Models\SakipMisi;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class SasaranRenstraController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        // SKPD Detection
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

        if ($request->ajax()) {
            $query = SakipSasaranrenstra::with(['sasaranRpjmd', 'periode', 'skpd']);
            
            if ($skpd_id) {
                $query->where('refskpd_id', $skpd_id);
            }

            if ($request->periode_id) {
                $query->where('refperiode_id', $request->periode_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_skpd', function($row) {
                    return $row->skpd->nama_skpd ?? '-';
                })
                ->addColumn('sasaran_rpjmd', function($row) {
                    return $row->sasaranRpjmd->uraian_sasaran ?? '-';
                })
                ->addColumn('action', function($row) {
                    return '
                        <div class="d-flex justify-content-center flex-shrink-0">
                            <a href="javascript:void(0)" onclick="showData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Detail">
                                <i class="ki-outline ki-eye fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit">
                                <i class="ki-outline ki-pencil fs-2"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteData('.$row->refsasaranrenstra_id.')" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus">
                                <i class="ki-outline ki-trash fs-2"></i>
                            </a>
                        </div>';
                })
                ->rawColumns(['action', 'sasaran_rpjmd'])
                ->make(true);
        }

        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $skpds = $isSuperadmin ? SakipSkpd::orderBy('nama_skpd')->get() : null;
        $current_skpd = $skpd_id ? SakipSkpd::find($skpd_id) : null;

        return view('frontend.renstra.sasaran.index', compact('periodes', 'skpds', 'isSuperadmin', 'current_skpd'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'uraian_sasaranrenstra' => 'required|string',
            'refperiode_id' => 'required',
            'refsasaran_id' => 'required',
            'refskpd_id' => 'required',
        ]);

        // Get Visi/Misi from Sasaran RPJMD
        $sasaranRpjmd = SakipSasaran::find($request->refsasaran_id);

        $data = $request->except('id');
        $data['refvisi_id'] = $sasaranRpjmd->refvisi_id ?? null;
        $data['refmisi_id'] = $sasaranRpjmd->refmisi_id ?? null;
        $data['reftujuan_id'] = $sasaranRpjmd->reftujuan_id ?? null;
        $data['sasaranrenstra_isaktif'] = 'T';

        SakipSasaranrenstra::create($data);

        return response()->json(['success' => 'Data Sasaran Renstra berhasil ditambahkan.']);
    }

    public function show($id)
    {
        $sasaran = SakipSasaranrenstra::with(['skpd', 'periode', 'sasaranRpjmd'])->findOrFail($id);
        if (request()->ajax()) {
            return response()->json($sasaran);
        }
        return view('frontend.renstra.sasaran.show', compact('sasaran'));
    }

    public function edit($id)
    {
        $sasaran = SakipSasaranrenstra::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($sasaran);
        }
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $sasaran_rpjmds = SakipSasaran::where('refperiode_id', $sasaran->refperiode_id)->get();
        return view('frontend.renstra.sasaran.edit', compact('sasaran', 'periodes', 'sasaran_rpjmds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'uraian_sasaranrenstra' => 'required|string',
            'refperiode_id' => 'required',
            'refsasaran_id' => 'required',
        ]);

        $sasaranRpjmd = SakipSasaran::find($request->refsasaran_id);
        $data = $request->except('id');
        $data['refvisi_id'] = $sasaranRpjmd->refvisi_id ?? null;
        $data['refmisi_id'] = $sasaranRpjmd->refmisi_id ?? null;
        $data['reftujuan_id'] = $sasaranRpjmd->reftujuan_id ?? null;

        SakipSasaranrenstra::findOrFail($id)->update($data);

        return response()->json(['success' => 'Data Sasaran Renstra berhasil diperbarui.']);
    }

    public function destroy($id)
    {
        SakipSasaranrenstra::findOrFail($id)->delete();
        return response()->json(['success' => 'Data Sasaran Renstra berhasil dihapus.']);
    }

    public function getSasaranRpjmd($periode_id)
    {
        $data = SakipSasaran::where('refperiode_id', $periode_id)->where('sasaran_isaktif', 'T')->get();
        return response()->json($data);
    }
}
