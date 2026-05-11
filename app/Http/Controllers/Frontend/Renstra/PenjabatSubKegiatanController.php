<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipPenjabatSkpd;
use App\Models\SakipPenjabatskpdCascadingsubkegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjabatSubKegiatanController extends Controller
{
    public function fetchPenjabatskpd(Request $request)
    {
        $refperiode_id = $request->refperiode_id;
        $refskpd_id = $request->refskpd_id;
        $refcascadingsubkegiatan_id = $request->refcascadingsubkegiatan_id;

        $existingIds = SakipPenjabatskpdCascadingsubkegiatan::where('refcascadingsubkegiatan_id', $refcascadingsubkegiatan_id)
            ->pluck('refpenjabatskpd_id')
            ->toArray();

        $penjabats = SakipPenjabatSkpd::where('refperiode_id', $refperiode_id)
            ->where('refskpd_id', $refskpd_id)
            ->whereNotIn('refpenjabatskpd_id', $existingIds)
            ->get();

        return response()->json($penjabats);
    }

    public function store(Request $request)
    {
        try {
            // Sync sequence
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_penjabatskpd_cascadingsubkegiatan', 'refpenjabatcascadingsubkegiatan_id'), coalesce(max(refpenjabatcascadingsubkegiatan_id), 0) + 1, false) FROM sakip_penjabatskpd_cascadingsubkegiatan");

            SakipPenjabatskpdCascadingsubkegiatan::create($request->all());
            return response()->json(['success' => 'Penjabat berhasil ditautkan!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            SakipPenjabatskpdCascadingsubkegiatan::destroy($id);
            return response()->json(['success' => 'Penjabat SKPD berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
