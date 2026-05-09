<?php

namespace App\Http\Controllers\Frontend\Renstra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SakipSkpd;
use App\Models\SakipVisi;
use App\Models\SakipMisi;
use App\Models\SakipPeriode;
use Illuminate\Support\Facades\Auth;

class DataSkpdController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::guard('frontend')->user();
        $isSuperadmin = $user->hasRole(['Superadmin', 'superadmin']);

        if ($isSuperadmin) {
            $skpd_id = $request->skpd_id;
            $skpds = SakipSkpd::orderBy('nama_skpd')->get();
        } else {
            // For regular users, attempt to link them to an SKPD
            $skpd_id = $user->refskpd_id ?? null;
            
            if (!$skpd_id) {
                // Heuristic: Try to match username with nama_skpd
                $username = strtolower($user->username);
                $matchedSkpd = SakipSkpd::whereRaw('LOWER(nama_skpd) LIKE ?', ["%{$username}%"])->first();
                if ($matchedSkpd) {
                    $skpd_id = $matchedSkpd->refskpd_id;
                }
            }
            $skpds = null;
        }

        // Filter Periode (Tahun)
        $periode_id = $request->periode_id;
        $periodes = SakipPeriode::orderBy('periode', 'desc')->get();

        // Removed auto-default to latest period to respect "manual trigger" request
        // if (!$periode_id && $periodes->count() > 0) {
        //     $periode_id = $periodes->first()->refperiode_id;
        // }

        // Fetch Data SKPD
        $data_skpd = null;
        if ($skpd_id) {
            $data_skpd = SakipSkpd::where('refskpd_id', $skpd_id)->first();
        }

        // Fetch Visi & Misi based on Periode
        $visi = null;
        $misi = collect();
        if ($periode_id) {
            $visi = SakipVisi::where('refperiode_id', $periode_id)->first();
            if ($visi) {
                $misi = SakipMisi::where('refvisi_id', $visi->refvisi_id)->get();
            }
        }

        return view('frontend.renstra.dataskpd.index', compact(
            'isSuperadmin', 
            'skpds', 
            'periodes', 
            'skpd_id', 
            'periode_id', 
            'data_skpd', 
            'visi', 
            'misi'
        ));
    }
}
