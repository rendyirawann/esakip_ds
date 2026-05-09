<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Jenssegers\Agent\Agent;

class FrontendLoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('frontend.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // 1. PROSES LOGIN & RATE LIMITER
        $request->authenticate();

        // 2. Regenerate Session
        $request->session()->regenerate();

        // 3. Ambil Data User
        $user = Auth::user();

        // 4. Cek Peran (Role)
        // Admin, Superadmin, dan SKPD diizinkan.
        // Jika ada role lain yang tidak boleh, bisa difilter di sini.
        if (!$user->hasAnyRole(['Superadmin', 'superadmin', 'Admin', 'admin', 'skpd', 'SKPD'])) {
            Auth::guard('web')->logout(); // Logout dari guard default jika tidak punya akses
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Anda tidak memiliki hak akses untuk masuk ke halaman ini.',
            ]);
        }

        // 5. Cek Status Banned
        if ($user->banned_at) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda telah dibekukan. Silakan hubungi admin.',
                ], 403);
            }
            throw ValidationException::withMessages([
                'email' => 'Akun Anda telah dibekukan.',
            ]);
        }

        // 6. Login via Frontend Guard (Gunakan ID user yang sama)
        Auth::guard('frontend')->login($user);
        // Logout dari guard web agar sesi benar-benar terpisah jika diinginkan, 
        // tapi user bilang "tetap memakai akun yg dari table users" dan "session yang berbeda".
        // Laravel guards handle different session keys automatically.
        Auth::guard('web')->logout(); 

        // 7. Update Data User
        $user->update([
            'last_ip' => $request->ip(),
            'last_login' => now(),
        ]);

        // 8. Catat Activity Log
        $agent = new Agent;
        if (function_exists('activity')) {
            activity()
                ->useLog('frontend_login')
                ->causedBy($user)
                ->withProperties([
                    'ip' => $request->ip(),
                    'agent' => [
                        'browser' => $agent->browser(),
                        'os' => $agent->platform(),
                        'device' => $agent->device(),
                    ],
                ])
                ->log('Login Frontend berhasil');
        }

        // 9. Return Response
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil, mengalihkan...',
                'redirect' => route('frontend.dashboard')
            ], 200);
        }

        return redirect()->intended(route('frontend.dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::guard('frontend')->user();
        
        if ($user && function_exists('activity')) {
            activity()
                ->useLog('frontend_logout')
                ->causedBy($user)
                ->log('Logout Frontend berhasil');
        }

        Auth::guard('frontend')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.login');
    }
}
