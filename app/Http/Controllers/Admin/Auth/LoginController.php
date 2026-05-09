<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $remember = (bool) ($data['remember'] ?? false);

        if (Auth::attempt([$field => $data['login'], 'password' => $data['password']], $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->forceFill(['last_login_at' => now()])->save();

            LoginHistory::create([
                'user_id' => $user->id,
                'username' => $user->username,
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'success' => true,
                'logged_in_at' => now(),
            ]);

            sweetalert()->addSuccess(__('common.saved_success'));
            return redirect()->intended(route('admin.dashboard'));
        }

        LoginHistory::create([
            'user_id' => null,
            'username' => $data['login'],
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'success' => false,
            'failure_reason' => 'Invalid credentials',
            'logged_in_at' => now(),
        ]);

        return back()
            ->withErrors(['login' => __('auth.failed')])
            ->withInput($request->only('login'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user) {
            LoginHistory::where('user_id', $user->id)
                ->whereNull('logged_out_at')
                ->latest('id')->limit(1)
                ->update(['logged_out_at' => now()]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
