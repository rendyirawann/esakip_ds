<?php

namespace App\Http\Controllers\Backend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SakipUrusan;
use App\Models\SakipBidang;
use App\Models\SakipPeriode;
use App\Models\SakipProgram;
use App\Models\SakipSkpd;
use App\Models\SakipKegiatan;
use App\Models\SakipSubkegiatan;
use App\Models\User;

use App\Models\SakipCascadingProgram;
use App\Models\SakipCascadingKegiatan;
use App\Models\SakipCascadingSubkegiatan;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $skpd_id = $request->skpd_id ?? 'all';
        $periode_id = $request->periode_id;

        // Default to current year if no periode_id provided
        if (!$periode_id) {
            $current_year = date('Y');
            $active_periode = SakipPeriode::where('periode', 'LIKE', '%' . $current_year . '%')->first();
            if ($active_periode) {
                $periode_id = $active_periode->refperiode_id;
            } else {
                // Fallback to the latest available period
                $latest = SakipPeriode::orderBy('periode', 'desc')->first();
                if ($latest) $periode_id = $latest->refperiode_id;
            }
        }

        $query_program = SakipCascadingProgram::query();
        $query_kegiatan = SakipCascadingKegiatan::query();
        $query_subkegiatan = SakipCascadingSubkegiatan::query();

        if ($skpd_id && $skpd_id !== 'all') {
            $query_program->where('refskpd_id', $skpd_id);
            $query_kegiatan->where('refskpd_id', $skpd_id);
            $query_subkegiatan->where('refskpd_id', $skpd_id);
        }

        if ($periode_id) {
            $query_program->where('refperiode_id', $periode_id);
            $query_kegiatan->where('refperiode_id', $periode_id);
            $query_subkegiatan->where('refperiode_id', $periode_id);
        }
        
        $stats = [
            'urusan' => SakipUrusan::count(),
            'bidang' => SakipBidang::count(),
            'program' => $query_program->count(),
            'kegiatan' => $query_kegiatan->count(),
            'subkegiatan' => $query_subkegiatan->count(),
            'skpd' => SakipSkpd::count(),
            'users' => User::count(),
        ];

        // Chart Data: Distribusi Program per Bidang
        $chart_bidang = SakipBidang::withCount(['programs' => function($query) use ($periode_id, $skpd_id) {
            if ($periode_id) $query->where('refperiode_id', $periode_id);
            if ($skpd_id && $skpd_id !== 'all') $query->where('refskpd_id', $skpd_id);
        }])
        ->orderBy('programs_count', 'desc')
        ->limit(10)
        ->get();

        $chart_labels = $chart_bidang->pluck('nama_bidang')->toArray();
        $chart_values = $chart_bidang->pluck('programs_count')->toArray();

        $skpds = SakipSkpd::orderBy('nama_skpd')->get();
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();

        // Extended stats for the table
        $skpd_summary = SakipSkpd::withCount([
            'programs' => function($query) use ($periode_id) {
                if ($periode_id) $query->where('refperiode_id', $periode_id);
            },
            'kegiatans' => function($query) use ($periode_id) {
                if ($periode_id) $query->where('refperiode_id', $periode_id);
            },
            'subkegiatans' => function($query) use ($periode_id) {
                if ($periode_id) $query->where('refperiode_id', $periode_id);
            }
        ])
        ->orderBy('nama_skpd')
        ->paginate(10);

        if ($request->ajax()) {
            // Future implementation for AJAX refresh
        }

        return view('backend.dashboard.index', compact('stats', 'skpds', 'periodes', 'skpd_summary', 'skpd_id', 'periode_id', 'chart_labels', 'chart_values'));
    }
}