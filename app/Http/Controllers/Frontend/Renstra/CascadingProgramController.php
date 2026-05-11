<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingprogram;
use App\Models\SakipIndikatorcascadingprogram;
use App\Models\SakipCascadingsubkegiatan;
use App\Models\SakipPenjabatskpdCascadingprogram;
use App\Models\SakipProgram;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CascadingProgramController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);
        $current_skpd = $isSuperadmin ? null : SakipSkpd::find($user->refskpd_id);

        $skpds = SakipSkpd::where('skpd_isaktif', 'T')->orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $bidangs = \App\Models\SakipBidang::where('bidang_isaktif', 'T')->orderBy('kode_bidang', 'asc')->get();

        return view('frontend.renstra.cascadingprogram.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd', 'bidangs'));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;

        $query = SakipCascadingprogram::with([
            'misi', 
            'tujuanRpjmd',
            'sasaranRenstra',
            'program',
            'penjabat.penjabatMaster'
        ])->where('sakip_cascadingprogram.refskpd_id', $skpd_id);

        if ($periode_id) {
            $query->where('sakip_cascadingprogram.refperiode_id', $periode_id);
        }

        $query->orderBy('refmisi_id')
            ->orderBy('reftujuan_id')
            ->orderBy('refsasaran_id')
            ->orderBy('refprogram_id')
            ->orderBy('uraian_indikatorprogram');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('misi_text', function($row) {
                return $row->misi ? strip_tags($row->misi->uraian_misi) : '-';
            })
            ->addColumn('tujuan_text', function($row) {
                return $row->tujuanRpjmd ? strip_tags($row->tujuanRpjmd->uraian_tujuan) : '-';
            })
            ->addColumn('sasaran_text', function($row) {
                return $row->sasaranRenstra ? strip_tags($row->sasaranRenstra->uraian_sasaranrenstra) : '-';
            })
            ->addColumn('program_kode', function($row) {
                return $row->program->kode_program ?? '-';
            })
            ->addColumn('program_nama', function($row) {
                return $row->program->nama_program ?? '-';
            })
            ->addColumn('anggaran', function($row) {
                $sum = SakipCascadingsubkegiatan::where('refskpd_id', $row->refskpd_id)
                    ->where('refprogram_id', $row->refprogram_id)
                    ->where('refperiode_id', $row->refperiode_id)
                    ->sum(DB::raw('CAST(subkegiatan_anggaran AS NUMERIC)'));
                return $sum ?? 0;
            })
            ->addColumn('penjabat_list', function($row) {
                return array_values($row->penjabat->map(function($p) {
                    return [
                        'nama' => $p->penjabatMaster->nama_penjabat ?? '-',
                        'nip' => $p->penjabatMaster->nip_penjabat ?? '-',
                        'jabatan' => $p->penjabatMaster->jabatan_eselon ?? '-',
                        'id' => $p->refpenjabatcascadingprogram_id
                    ];
                })->toArray());
            })
            ->rawColumns(['penjabat_list'])
            ->make(true);
    }

    public function show($id)
    {
        $data = SakipCascadingprogram::with(['misi', 'tujuanRpjmd', 'sasaranRenstra', 'program'])->findOrFail($id);
        return response()->json($data);
    }

    public function getSasaranRenstra(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;
        
        $data = \App\Models\SakipSasaranrenstra::where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id)
            ->get();
            
        return response()->json($data);
    }

    public function getIndikatorSasaranRenstra(Request $request)
    {
        $sasaran_renstra_id = $request->sasaran_renstra_id;
        $data = \App\Models\SakipIndikatorsasaranrenstra::where('refsasaranrenstra_id', $sasaran_renstra_id)->get();
        return response()->json($data);
    }

    public function getAssociatedValues(Request $request)
    {
        $sasaran_renstra_id = $request->sasaran_renstra_id;
        // Pastikan kita ambil data langsung dari kolom tabel sakip_sasaranrenstra
        $sasaranRenstra = \App\Models\SakipSasaranrenstra::find($sasaran_renstra_id);
        
        if ($sasaranRenstra) {
            return response()->json([
                'refsasaran_id' => $sasaranRenstra->refsasaran_id,
                'reftujuan_id' => $sasaranRenstra->reftujuan_id,
                'refmisi_id' => $sasaranRenstra->refmisi_id,
                'uraian_misi' => $sasaranRenstra->tujuan->misi->uraian_misi ?? '-',
                'uraian_tujuan' => $sasaranRenstra->tujuan->uraian_tujuan ?? '-',
                'uraian_sasaran' => $sasaranRenstra->uraian_sasaranrenstra ?? '-',
            ]);
        }
        return response()->json([]);
    }

    public function getPrograms(Request $request)
    {
        $bidang_id = $request->bidang_id;
        $data = SakipProgram::where('refbidang_id', $bidang_id)->where('program_isaktif', 'T')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Sync sequences to prevent duplicate key errors (PostgreSQL specific)
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_cascadingprogram', 'refcascadingprogram_id'), coalesce(max(refcascadingprogram_id), 0) + 1, false) FROM sakip_cascadingprogram");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingprogram', 'refindikatorprogram_id'), coalesce(max(refindikatorprogram_id), 0) + 1, false) FROM sakip_indikatorcascadingprogram");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingprogram_triwulan', 'refindikatorprogramtriwulan_id'), coalesce(max(refindikatorprogramtriwulan_id), 0) + 1, false) FROM sakip_indikatorcascadingprogram_triwulan");

            $model = new SakipCascadingprogram();
            $model->fill($request->all());

            // Safety Check: Fetch associated IDs from Sasaran Renstra if null
            if (!$model->refsasaran_id || !$model->reftujuan_id || !$model->refmisi_id) {
                $sr = \App\Models\SakipSasaranrenstra::find($request->refsasaranrenstra_id);
                if ($sr) {
                    $model->refsasaran_id = $sr->refsasaran_id;
                    $model->reftujuan_id = $sr->reftujuan_id;
                    $model->refmisi_id = $sr->refmisi_id;
                }
            }

            $model->program_target = str_replace(',', '.', $request->program_target);
            $model->save();

            // Step 1: Create Indikator Cascading Program
            $indikator = new SakipIndikatorcascadingprogram();
            $indikator->refcascadingprogram_id = $model->refcascadingprogram_id;
            $indikator->refsasaranrenstra_id = $model->refsasaranrenstra_id;
            $indikator->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
            $indikator->refskpd_id = $model->refskpd_id;
            $indikator->refperiode_id = $model->refperiode_id;
            $indikator->refbidang_id = $model->refbidang_id;
            $indikator->refprogram_id = $model->refprogram_id;
            $indikator->target_rkt = $model->program_target;
            $indikator->save();

            // Step 2: Create 4 Triwulan rows
            for ($i = 1; $i <= 4; $i++) {
                $triwulan = new \App\Models\SakipIndikatorcascadingprogramTriwulan();
                $triwulan->refindikatorprogram_id = $indikator->refindikatorprogram_id;
                $triwulan->refcascadingprogram_id = $model->refcascadingprogram_id;
                $triwulan->refsasaranrenstra_id = $model->refsasaranrenstra_id;
                $triwulan->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
                $triwulan->refskpd_id = $model->refskpd_id;
                $triwulan->refperiode_id = $model->refperiode_id;
                $triwulan->refbidang_id = $model->refbidang_id;
                $triwulan->refprogram_id = $model->refprogram_id;
                $triwulan->triwulan_target_rkt = $model->program_target;
                $triwulan->reftriwulan_id = $i;
                $triwulan->save();
            }

            DB::commit();
            return response()->json(['success' => 'Cascading Program berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = SakipCascadingprogram::findOrFail($id);
            $model->fill($request->all());

            // Safety Check: Fetch associated IDs from Sasaran Renstra if null
            if (!$model->refsasaran_id || !$model->reftujuan_id || !$model->refmisi_id) {
                $sr = \App\Models\SakipSasaranrenstra::find($request->refsasaranrenstra_id);
                if ($sr) {
                    $model->refsasaran_id = $sr->refsasaran_id;
                    $model->reftujuan_id = $sr->reftujuan_id;
                    $model->refmisi_id = $sr->refmisi_id;
                }
            }

            $model->program_target = str_replace(',', '.', $request->program_target);
            $model->save();

            // Update associated indicators
            SakipIndikatorcascadingprogram::where('refcascadingprogram_id', $id)->update([
                'refsasaranrenstra_id' => $model->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $model->refindikatorsasaranrenstra_id,
                'refskpd_id' => $model->refskpd_id,
                'refperiode_id' => $model->refperiode_id,
                'refbidang_id' => $model->refbidang_id,
                'refprogram_id' => $model->refprogram_id,
                'target_rkt' => $model->program_target,
                'target_pk' => $model->program_target,
            ]);

            // Update associated triwulan
            \App\Models\SakipIndikatorcascadingprogramTriwulan::where('refcascadingprogram_id', $id)->update([
                'refsasaranrenstra_id' => $model->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $model->refindikatorsasaranrenstra_id,
                'refskpd_id' => $model->refskpd_id,
                'refperiode_id' => $model->refperiode_id,
                'refbidang_id' => $model->refbidang_id,
                'refprogram_id' => $model->refprogram_id,
                'triwulan_target_rkt' => $model->program_target,
                'triwulan_target_pk' => $model->program_target,
            ]);

            DB::commit();
            return response()->json(['success' => 'Cascading Program berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Delete associated Triwulan
            \App\Models\SakipIndikatorcascadingprogramTriwulan::where('refcascadingprogram_id', $id)->delete();
            
            // Delete associated Indikator
            SakipIndikatorcascadingprogram::where('refcascadingprogram_id', $id)->delete();
            
            // Delete associated Penjabat linkages
            SakipPenjabatskpdCascadingprogram::where('refcascadingprogram_id', $id)->delete();

            // Finally delete the program
            SakipCascadingprogram::destroy($id);

            DB::commit();
            return response()->json(['success' => 'Cascading Program dan data terkait berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
