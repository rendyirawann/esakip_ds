<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SakipSettings; // Adjust if different

class FrontendDashboardController extends Controller
{
    public function index()
    {
        // Get app settings for layout
        $appSettings = []; // You might need to fetch this like in backend
        
        return view('frontend.dashboard.index', compact('appSettings'));
    }
}
