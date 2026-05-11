<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingkegiatan;
use App\Models\SakipIndikatorcascadingkegiatan;
use App\Models\SakipIndikatorcascadingkegiatanTriwulan;
use App\Models\SakipCascadingsubkegiatan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CascadingKegiatanController extends Controller
{
    public function index()
    {
        $skpds = SakipSkpd::orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $isSuperadmin = Auth::guard('frontend')->user()->hasRole(['Superadmin', 'superadmin']);
        $current_skpd = Auth::guard('frontend')->user()->refskpd_id;

        return view('frontend.renstra.cascadingkegiatan.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;

        $query = SakipCascadingkegiatan::select('sakip_cascadingkegiatan.*')
            ->join('sakip_cascadingprogram', 'sakip_cascadingkegiatan.refcascadingprogram_id', '=', 'sakip_cascadingprogram.refcascadingprogram_id')
            ->join('sakip_kegiatan', 'sakip_cascadingkegiatan.refkegiatan_id', '=', 'sakip_kegiatan.refkegiatan_id')
            ->with(['program', 'kegiatan', 'periode', 'skpd', 'penjabat.penjabatMaster', 'cascadingProgram.program', 'cascadingProgram.misi', 'cascadingProgram.tujuanRpjmd', 'cascadingProgram.sasaranRenstra'])
            ->where('sakip_cascadingkegiatan.refskpd_id', $skpd_id)
            ->where('sakip_cascadingkegiatan.refperiode_id', $periode_id)
            ->orderBy('sakip_cascadingprogram.refmisi_id')
            ->orderBy('sakip_cascadingprogram.reftujuan_id')
            ->orderBy('sakip_cascadingprogram.refsasaran_id')
            ->orderBy('sakip_cascadingprogram.refprogram_id')
            ->orderBy('sakip_cascadingprogram.uraian_indikatorprogram')
            ->orderBy('sakip_kegiatan.nama_kegiatan');

        return DataTables::of($query)
            ->addColumn('misi_text', function($row) {
                return $row->cascadingProgram && $row->cascadingProgram->misi ? strip_tags($row->cascadingProgram->misi->uraian_misi) : '-';
            })
            ->addColumn('tujuan_text', function($row) {
                return $row->cascadingProgram && $row->cascadingProgram->tujuanRpjmd ? strip_tags($row->cascadingProgram->tujuanRpjmd->uraian_tujuan) : '-';
            })
            ->addColumn('sasaran_text', function($row) {
                return $row->cascadingProgram && $row->cascadingProgram->sasaranRenstra ? strip_tags($row->cascadingProgram->sasaranRenstra->uraian_sasaranrenstra) : '-';
            })
            ->addColumn('program_text', function($row) {
                if ($row->cascadingProgram) {
                    $kode = $row->cascadingProgram->program->kode_program ?? '-';
                    $nama = $row->cascadingProgram->program->nama_program ?? '-';
                    $indikator = $row->cascadingProgram->uraian_indikatorprogram ?? '-';
                    return '[' . $kode . '] - ' . $nama . ' [' . $indikator . ']';
                }
                return '-';
            })
            ->addColumn('kegiatan_kode', function($row) {
                return $row->kegiatan->kode_kegiatan ?? '-';
            })
            ->addColumn('kegiatan_nama', function($row) {
                return $row->kegiatan->nama_kegiatan ?? '-';
            })
            ->addColumn('anggaran', function($row) {
                $sum = SakipCascadingsubkegiatan::where('refskpd_id', $row->refskpd_id)
                    ->where('refkegiatan_id', $row->refkegiatan_id)
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
                        'id' => $p->refpenjabatcascadingkegiatan_id
                    ];
                })->toArray());
            })
            ->make(true);
    }

    public function getProgramCascading(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;
        
        $data = \App\Models\SakipCascadingprogram::with('program')
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id)
            ->get();
            
        return response()->json($data);
    }

    public function getIndikatorProgram(Request $request)
    {
        $cascading_program_id = $request->cascading_program_id;
        $data = \App\Models\SakipIndikatorcascadingprogram::where('refcascadingprogram_id', $cascading_program_id)->get();
        return response()->json($data);
    }

    public function getAssociatedValues(Request $request)
    {
        $cascading_program_id = $request->cascading_program_id;
        $cp = \App\Models\SakipCascadingprogram::find($cascading_program_id);
        
        if ($cp) {
            return response()->json([
                'refsasaranrenstra_id' => $cp->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $cp->refindikatorsasaranrenstra_id,
                'refbidang_id' => $cp->refbidang_id,
                'refprogram_id' => $cp->refprogram_id,
                'refsasaran_id' => $cp->refsasaran_id,
                'reftujuan_id' => $cp->reftujuan_id,
                'refmisi_id' => $cp->refmisi_id,
            ]);
        }
        return response()->json([]);
    }

    public function getKegiatans(Request $request)
    {
        $program_id = $request->program_id;
        $data = \App\Models\SakipKegiatan::where('refprogram_id', $program_id)->where('kegiatan_isaktif', 'T')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Sync sequences
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_cascadingkegiatan', 'refcascadingkegiatan_id'), coalesce(max(refcascadingkegiatan_id), 0) + 1, false) FROM sakip_cascadingkegiatan");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingkegiatan', 'refindikatorkegiatan_id'), coalesce(max(refindikatorkegiatan_id), 0) + 1, false) FROM sakip_indikatorcascadingkegiatan");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingkegiatan_triwulan', 'refindikatorkegiatantriwulan_id'), coalesce(max(refindikatorkegiatantriwulan_id), 0) + 1, false) FROM sakip_indikatorcascadingkegiatan_triwulan");

            $model = new SakipCascadingkegiatan();
            $data = $request->except(['refbidang_id', 'refmisi_id', 'reftujuan_id', 'refsasaran_id']);
            $model->fill($data);
            
            // Safety Check: Fetch associated IDs from Parent Program if null
            if (!$model->refsasaranrenstra_id || !$model->refprogram_id) {
                $cp = \App\Models\SakipCascadingprogram::find($request->refcascadingprogram_id);
                if ($cp) {
                    $model->refsasaranrenstra_id = $cp->refsasaranrenstra_id;
                    $model->refindikatorsasaranrenstra_id = $cp->refindikatorsasaranrenstra_id;
                    $model->refprogram_id = $cp->refprogram_id;
                }
            }

            $model->kegiatan_target = str_replace(',', '.', $request->kegiatan_target);
            $model->save();

            // Step 1: Create Indikator Cascading Kegiatan
            $indikator = new SakipIndikatorcascadingkegiatan();
            $indikator->refcascadingkegiatan_id = $model->refcascadingkegiatan_id;
            $indikator->refcascadingprogram_id = $model->refcascadingprogram_id;
            $indikator->refsasaranrenstra_id = $model->refsasaranrenstra_id;
            $indikator->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
            $indikator->refskpd_id = $model->refskpd_id;
            $indikator->refperiode_id = $model->refperiode_id;
            $indikator->refprogram_id = $model->refprogram_id;
            $indikator->refkegiatan_id = $model->refkegiatan_id;
            $indikator->target_rkt = $model->kegiatan_target;
            $indikator->save();

            // Step 2: Create 4 Triwulan rows
            for ($i = 1; $i <= 4; $i++) {
                $triwulan = new SakipIndikatorcascadingkegiatanTriwulan();
                $triwulan->refindikatorkegiatan_id = $indikator->refindikatorkegiatan_id;
                $triwulan->refcascadingkegiatan_id = $model->refcascadingkegiatan_id;
                $triwulan->refcascadingprogram_id = $model->refcascadingprogram_id;
                $triwulan->refsasaranrenstra_id = $model->refsasaranrenstra_id;
                $triwulan->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
                $triwulan->refskpd_id = $model->refskpd_id;
                $triwulan->refperiode_id = $model->refperiode_id;
                $triwulan->refprogram_id = $model->refprogram_id;
                $triwulan->refkegiatan_id = $model->refkegiatan_id;
                $triwulan->triwulan_target_rkt = $model->kegiatan_target;
                $triwulan->reftriwulan_id = $i;
                $triwulan->save();
            }

            DB::commit();
            return response()->json(['success' => 'Cascading Kegiatan berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = SakipCascadingkegiatan::with(['program', 'kegiatan'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = SakipCascadingkegiatan::findOrFail($id);
            $data = $request->except(['refbidang_id', 'refmisi_id', 'reftujuan_id', 'refsasaran_id']);
            $model->fill($data);
            $model->kegiatan_target = str_replace(',', '.', $request->kegiatan_target);
            $model->save();

            // Update associated indicators
            SakipIndikatorcascadingkegiatan::where('refcascadingkegiatan_id', $id)->update([
                'target_rkt' => $model->kegiatan_target
            ]);

            // Update associated triwulan
            SakipIndikatorcascadingkegiatanTriwulan::where('refcascadingkegiatan_id', $id)->update([
                'triwulan_target_rkt' => $model->kegiatan_target
            ]);

            DB::commit();
            return response()->json(['success' => 'Cascading Kegiatan berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            SakipIndikatorcascadingkegiatanTriwulan::where('refcascadingkegiatan_id', $id)->delete();
            SakipIndikatorcascadingkegiatan::where('refcascadingkegiatan_id', $id)->delete();
            \App\Models\SakipPenjabatskpdCascadingkegiatan::where('refcascadingkegiatan_id', $id)->delete();
            SakipCascadingkegiatan::destroy($id);

            DB::commit();
            return response()->json(['success' => 'Cascading Kegiatan dan data terkait berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
