<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipCascadingsubkegiatan;
use App\Models\SakipIndikatorcascadingsubkegiatan;
use App\Models\SakipIndikatorcascadingsubkegiatanTriwulan;
use App\Models\SakipSkpd;
use App\Models\SakipPeriode;
use App\Models\SakipSubkegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CascadingSubKegiatanController extends Controller
{
    public function index()
    {
        $skpds = SakipSkpd::orderBy('nama_skpd', 'asc')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();
        $isSuperadmin = Auth::guard('frontend')->user()->hasRole(['Superadmin', 'superadmin']);
        $current_skpd = Auth::guard('frontend')->user()->refskpd_id;

        return view('frontend.renstra.cascadingsubkegiatan.index', compact('skpds', 'periodes', 'isSuperadmin', 'current_skpd'));
    }

    public function data(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;

        $query = SakipCascadingsubkegiatan::select('sakip_cascadingsubkegiatan.*')
            ->join('sakip_cascadingkegiatan', 'sakip_cascadingsubkegiatan.refcascadingkegiatan_id', '=', 'sakip_cascadingkegiatan.refcascadingkegiatan_id')
            ->join('sakip_cascadingprogram', 'sakip_cascadingsubkegiatan.refcascadingprogram_id', '=', 'sakip_cascadingprogram.refcascadingprogram_id')
            ->join('sakip_subkegiatan', 'sakip_cascadingsubkegiatan.refsubkegiatan_id', '=', 'sakip_subkegiatan.refsubkegiatan_id')
            ->with([
                'program', 'kegiatan', 'subkegiatan', 'periode', 'skpd', 
                'cascadingKegiatan.kegiatan', 
                'cascadingKegiatan.cascadingProgram.program',
                'cascadingKegiatan.cascadingProgram.misi',
                'cascadingKegiatan.cascadingProgram.tujuanRpjmd',
                'cascadingKegiatan.cascadingProgram.sasaranRenstra',
                'penjabats.penjabatSkpd'
            ])
            ->where('sakip_cascadingsubkegiatan.refskpd_id', $skpd_id)
            ->where('sakip_cascadingsubkegiatan.refperiode_id', $periode_id)
            ->orderBy('sakip_cascadingprogram.refmisi_id')
            ->orderBy('sakip_cascadingprogram.reftujuan_id')
            ->orderBy('sakip_cascadingprogram.refsasaran_id')
            ->orderBy('sakip_cascadingprogram.refprogram_id')
            ->orderBy('sakip_cascadingprogram.uraian_indikatorprogram')
            ->orderBy('sakip_cascadingkegiatan.refkegiatan_id')
            ->orderBy('sakip_subkegiatan.nama_subkegiatan');

        return DataTables::of($query)
            ->addColumn('misi_text', function($row) {
                return $row->cascadingKegiatan && $row->cascadingKegiatan->cascadingProgram && $row->cascadingKegiatan->cascadingProgram->misi 
                    ? strip_tags($row->cascadingKegiatan->cascadingProgram->misi->uraian_misi) : '-';
            })
            ->addColumn('tujuan_text', function($row) {
                return $row->cascadingKegiatan && $row->cascadingKegiatan->cascadingProgram && $row->cascadingKegiatan->cascadingProgram->tujuanRpjmd 
                    ? strip_tags($row->cascadingKegiatan->cascadingProgram->tujuanRpjmd->uraian_tujuan) : '-';
            })
            ->addColumn('sasaran_text', function($row) {
                return $row->cascadingKegiatan && $row->cascadingKegiatan->cascadingProgram && $row->cascadingKegiatan->cascadingProgram->sasaranRenstra 
                    ? strip_tags($row->cascadingKegiatan->cascadingProgram->sasaranRenstra->uraian_sasaranrenstra) : '-';
            })
            ->addColumn('program_text', function($row) {
                if ($row->cascadingKegiatan && $row->cascadingKegiatan->cascadingProgram) {
                    $cp = $row->cascadingKegiatan->cascadingProgram;
                    $kode = $cp->program->kode_program ?? '-';
                    $nama = $cp->program->nama_program ?? '-';
                    $indikator = $cp->uraian_indikatorprogram ?? '-';
                    return '[' . $kode . '] - ' . $nama . ' [' . $indikator . ']';
                }
                return '-';
            })
            ->addColumn('kegiatan_text', function($row) {
                if ($row->cascadingKegiatan) {
                    $kode = $row->cascadingKegiatan->kegiatan->kode_kegiatan ?? '-';
                    $nama = $row->cascadingKegiatan->kegiatan->nama_kegiatan ?? '-';
                    $indikator = $row->cascadingKegiatan->uraian_indikatorkegiatan ?? '-';
                    return '[' . $kode . '] - ' . $nama . ' [' . $indikator . ']';
                }
                return '-';
            })
            ->addColumn('penjabat_list', function($row) {
                return $row->penjabats->map(function($pj) {
                    return [
                        'id' => $pj->refpenjabatcascadingsubkegiatan_id,
                        'nama' => $pj->penjabatSkpd->nama_penjabat ?? '-',
                        'nip' => $pj->penjabatSkpd->nip_penjabat ?? '-',
                        'jabatan' => $pj->penjabatSkpd->jabatan_eselon ?? '-',
                    ];
                })->values()->all();
            })
            ->addColumn('subkegiatan_kode', function($row) {
                return $row->subkegiatan->kode_subkegiatan ?? '-';
            })
            ->addColumn('subkegiatan_nama', function($row) {
                return $row->subkegiatan->nama_subkegiatan ?? '-';
            })
            ->addColumn('anggaran', function($row) {
                return number_format($row->subkegiatan_anggaran, 0, ',', '.');
            })
            ->make(true);
    }

    public function getKegiatanCascading(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode_id = $request->periode_id;
        
        $data = \App\Models\SakipCascadingkegiatan::with('kegiatan')
            ->where('refskpd_id', $skpd_id)
            ->where('refperiode_id', $periode_id)
            ->get();
            
        return response()->json($data);
    }

    public function getAssociatedValues(Request $request)
    {
        $cascading_kegiatan_id = $request->cascading_kegiatan_id;
        $ck = \App\Models\SakipCascadingkegiatan::with('cascadingProgram.program')->find($cascading_kegiatan_id);
        
        if ($ck) {
            $program_info = '-';
            if ($ck->cascadingProgram) {
                $p = $ck->cascadingProgram;
                $pKode = $p->program->kode_program ?? '-';
                $pNama = $p->program->nama_program ?? '-';
                $pIndikator = $p->uraian_indikatorprogram ?? '-';
                $program_info = '[' . $pKode . '] - ' . $pNama . ' [' . $pIndikator . ']';
            }

            return response()->json([
                'refcascadingprogram_id' => $ck->refcascadingprogram_id,
                'refsasaranrenstra_id' => $ck->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $ck->refindikatorsasaranrenstra_id,
                'refprogram_id' => $ck->refprogram_id,
                'refkegiatan_id' => $ck->refkegiatan_id,
                'program_info' => $program_info
            ]);
        }
        return response()->json([]);
    }

    public function getSubKegiatans(Request $request)
    {
        $kegiatan_id = $request->kegiatan_id;
        $data = \App\Models\SakipSubkegiatan::where('refkegiatan_id', $kegiatan_id)->where('subkegiatan_isaktif', 'T')->get();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Sync sequences
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_cascadingsubkegiatan', 'refcascadingsubkegiatan_id'), coalesce(max(refcascadingsubkegiatan_id), 0) + 1, false) FROM sakip_cascadingsubkegiatan");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingsubkegiatan', 'refindikatorsubkegiatan_id'), coalesce(max(refindikatorsubkegiatan_id), 0) + 1, false) FROM sakip_indikatorcascadingsubkegiatan");
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_indikatorcascadingsubkegiatan_triwulan', 'refindikatorsubkegiatantriwulan_id'), coalesce(max(refindikatorsubkegiatantriwulan_id), 0) + 1, false) FROM sakip_indikatorcascadingsubkegiatan_triwulan");

            $model = new SakipCascadingsubkegiatan();
            $data = $request->except(['refcascadingsubkegiatan_id', 'refbidang_id', 'refmisi_id', 'reftujuan_id', 'refsasaran_id']);
            $model->fill($data);
            
            // Safety Check: Fetch associated IDs from Parent Kegiatan if null
            if (!$model->refcascadingprogram_id || !$model->refprogram_id) {
                $ck = \App\Models\SakipCascadingkegiatan::find($request->refcascadingkegiatan_id);
                if ($ck) {
                    $model->refcascadingprogram_id = $ck->refcascadingprogram_id;
                    $model->refsasaranrenstra_id = $ck->refsasaranrenstra_id;
                    $model->refindikatorsasaranrenstra_id = $ck->refindikatorsasaranrenstra_id;
                    $model->refprogram_id = $ck->refprogram_id;
                    $model->refkegiatan_id = $ck->refkegiatan_id;
                }
            }

            $model->subkegiatan_target = str_replace(',', '.', $request->subkegiatan_target);
            $model->subkegiatan_anggaran = str_replace(['.', ','], ['', '.'], $request->subkegiatan_anggaran);
            $model->save();

            // Step 1: Create Indikator Cascading Sub Kegiatan
            $indikator = new SakipIndikatorcascadingsubkegiatan();
            $indikator->refcascadingsubkegiatan_id = $model->refcascadingsubkegiatan_id;
            $indikator->refcascadingkegiatan_id = $model->refcascadingkegiatan_id;
            $indikator->refcascadingprogram_id = $model->refcascadingprogram_id;
            $indikator->refsasaranrenstra_id = $model->refsasaranrenstra_id;
            $indikator->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
            $indikator->refskpd_id = $model->refskpd_id;
            $indikator->refperiode_id = $model->refperiode_id;
            $indikator->refprogram_id = $model->refprogram_id;
            $indikator->refkegiatan_id = $model->refkegiatan_id;
            $indikator->refsubkegiatan_id = $model->refsubkegiatan_id;
            $indikator->target_rkt = $model->subkegiatan_target;
            $indikator->target_pk = $model->subkegiatan_target;
            $indikator->anggaran_rkt = $model->subkegiatan_anggaran;
            $indikator->anggaran_pk = $model->subkegiatan_anggaran;
            $indikator->save();

            // Step 2: Create 4 Triwulan rows
            for ($i = 1; $i <= 4; $i++) {
                $triwulan = new SakipIndikatorcascadingsubkegiatanTriwulan();
                $triwulan->refindikatorsubkegiatan_id = $indikator->refindikatorsubkegiatan_id;
                $triwulan->refcascadingsubkegiatan_id = $model->refcascadingsubkegiatan_id;
                $triwulan->refcascadingkegiatan_id = $model->refcascadingkegiatan_id;
                $triwulan->refcascadingprogram_id = $model->refcascadingprogram_id;
                $triwulan->refsasaranrenstra_id = $model->refsasaranrenstra_id;
                $triwulan->refindikatorsasaranrenstra_id = $model->refindikatorsasaranrenstra_id;
                $triwulan->reftriwulan_id = $i;
                $triwulan->refskpd_id = $model->refskpd_id;
                $triwulan->refperiode_id = $model->refperiode_id;
                $triwulan->refprogram_id = $model->refprogram_id;
                $triwulan->refkegiatan_id = $model->refkegiatan_id;
                $triwulan->refsubkegiatan_id = $model->refsubkegiatan_id;
                $triwulan->triwulan_target_rkt = $model->subkegiatan_target;
                $triwulan->triwulan_target_pk = $model->subkegiatan_target;
                $triwulan->save();
            }

            DB::commit();
            return response()->json(['success' => 'Cascading Sub Kegiatan berhasil ditambahkan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $data = SakipCascadingsubkegiatan::with(['program', 'kegiatan', 'subkegiatan'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = SakipCascadingsubkegiatan::findOrFail($id);
            $data = $request->except(['refbidang_id', 'refmisi_id', 'reftujuan_id', 'refsasaran_id']);
            $model->fill($data);
            $model->subkegiatan_target = str_replace(',', '.', $request->subkegiatan_target);
            $model->subkegiatan_anggaran = str_replace(['.', ','], ['', '.'], $request->subkegiatan_anggaran);
            $model->save();

            // Update associated indicators
            SakipIndikatorcascadingsubkegiatan::where('refcascadingsubkegiatan_id', $id)->update([
                'refcascadingkegiatan_id' => $model->refcascadingkegiatan_id,
                'refcascadingprogram_id' => $model->refcascadingprogram_id,
                'refsasaranrenstra_id' => $model->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $model->refindikatorsasaranrenstra_id,
                'refskpd_id' => $model->refskpd_id,
                'refperiode_id' => $model->refperiode_id,
                'refprogram_id' => $model->refprogram_id,
                'refkegiatan_id' => $model->refkegiatan_id,
                'refsubkegiatan_id' => $model->refsubkegiatan_id,
                'target_rkt' => $model->subkegiatan_target,
                'target_pk' => $model->subkegiatan_target,
                'anggaran_rkt' => $model->subkegiatan_anggaran,
                'anggaran_pk' => $model->subkegiatan_anggaran,
            ]);

            // Update associated triwulan
            SakipIndikatorcascadingsubkegiatanTriwulan::where('refcascadingsubkegiatan_id', $id)->update([
                'refcascadingkegiatan_id' => $model->refcascadingkegiatan_id,
                'refcascadingprogram_id' => $model->refcascadingprogram_id,
                'refsasaranrenstra_id' => $model->refsasaranrenstra_id,
                'refindikatorsasaranrenstra_id' => $model->refindikatorsasaranrenstra_id,
                'refskpd_id' => $model->refskpd_id,
                'refperiode_id' => $model->refperiode_id,
                'refprogram_id' => $model->refprogram_id,
                'refkegiatan_id' => $model->refkegiatan_id,
                'refsubkegiatan_id' => $model->refsubkegiatan_id,
                'triwulan_target_rkt' => $model->subkegiatan_target,
                'triwulan_target_pk' => $model->subkegiatan_target,
            ]);

            DB::commit();
            return response()->json(['success' => 'Cascading Sub Kegiatan berhasil diperbarui!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            SakipIndikatorcascadingsubkegiatanTriwulan::where('refcascadingsubkegiatan_id', $id)->delete();
            SakipIndikatorcascadingsubkegiatan::where('refcascadingsubkegiatan_id', $id)->delete();
            // Delete penjabat if any (add model if exists later)
            SakipCascadingsubkegiatan::destroy($id);

            DB::commit();
            return response()->json(['success' => 'Cascading Sub Kegiatan dan data terkait berhasil dihapus!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
