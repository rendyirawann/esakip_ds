<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use App\Models\SakipPenjabatSkpd;
use App\Models\SakipPenjabatskpdCascadingprogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjabatCascadingController extends Controller
{
    public function fetchPenjabatskpd(Request $request)
    {
        $refperiode_id = $request->refperiode_id;
        $refskpd_id = $request->refskpd_id;
        $refcascadingprogram_id = $request->refcascadingprogram_id;

        // Get existing penjabat for this program to exclude them
        $existingIds = SakipPenjabatskpdCascadingprogram::where('refcascadingprogram_id', $refcascadingprogram_id)
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
            DB::statement("SELECT setval(pg_get_serial_sequence('sakip_penjabatskpd_cascadingprogram', 'refpenjabatcascadingprogram_id'), coalesce(max(refpenjabatcascadingprogram_id), 0) + 1, false) FROM sakip_penjabatskpd_cascadingprogram");

            SakipPenjabatskpdCascadingprogram::create($request->all());
            return response()->json(['success' => 'Penjabat berhasil ditautkan!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            SakipPenjabatskpdCascadingprogram::destroy($id);
            return response()->json(['success' => 'Penjabat SKPD berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
