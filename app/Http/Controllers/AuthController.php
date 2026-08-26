<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SiteSetting;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function generateCaptchaSvg($code)
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="160" height="46" viewBox="0 0 160 46" style="background:#f1f5f9; border-radius:10px; border:1px solid #cbd5e1; user-select:none; display:block;">';
        $svg .= '<line x1="10" y1="12" x2="150" y2="34" stroke="#94a3b8" stroke-width="1.5" stroke-dasharray="4"/>';
        $svg .= '<line x1="15" y1="35" x2="145" y2="10" stroke="#cbd5e1" stroke-width="1.5"/>';
        $chars = str_split($code);
        $x = 18;
        foreach ($chars as $i => $char) {
            $rot = rand(-12, 12);
            $y = rand(30, 34);
            $colors = ['#0f172a', '#0284c7', '#0369a1', '#1e293b', '#0ea5e9'];
            $color = $colors[$i % count($colors)];
            $svg .= "<text x='{$x}' y='{$y}' fill='{$color}' font-size='24' font-weight='800' font-family='Courier, monospace' transform='rotate({$rot}, {$x}, {$y})'>{$char}</text>";
            $x += 28;
        }
        $svg .= '</svg>';
        return $svg;
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        $code = strtoupper(substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5));
        session(['captcha_code' => $code]);
        $captchaSvg = $this->generateCaptchaSvg($code);

        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('auth.login', compact('settings', 'captchaSvg'));
    }

    public function refreshCaptcha()
    {
        $code = strtoupper(substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5));
        session(['captcha_code' => $code]);
        $captchaSvg = $this->generateCaptchaSvg($code);

        return response()->json([
            'status' => 'success',
            'captcha' => $captchaSvg
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'string'],
        ]);

        $inputCaptcha = strtoupper(trim($request->input('captcha')));
        $sessionCaptcha = strtoupper(trim((string) session('captcha_code')));

        if ($inputCaptcha !== $sessionCaptcha) {
            // Generate a fresh captcha code on error
            $newCode = strtoupper(substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5));
            session(['captcha_code' => $newCode]);

            return back()->withErrors([
                'captcha' => 'Kode captcha yang Anda masukkan tidak sesuai / salah.',
            ])->onlyInput('email');
        }

        $loginInput = trim($request->input('email'));
        $password = $request->input('password');

        // Cari user berdasarkan username, email, atau nama (fleksibel)
        $user = \App\Models\User::where('username', $loginInput)
            ->orWhere('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));

            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $newCode = strtoupper(substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5));
                session(['captcha_code' => $newCode]);

                return back()->withErrors([
                    'email' => 'Akun Anda saat ini sedang dinonaktifkan oleh Administrator.',
                ])->onlyInput('email');
            }

            // Record activity log
            ActivityLog::record('LOGIN', 'Pengguna "' . ($user->name ?? $user->username) . '" dengan role (' . ucfirst($user->role) . ') berhasil login ke sistem.');

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        // Generate a fresh captcha code on failure
        $newCode = strtoupper(substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZ'), 0, 5));
        session(['captcha_code' => $newCode]);

        return back()->withErrors([
            'email' => 'Username/Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                ActivityLog::record('LOGOUT', 'Pengguna "' . ($user->name ?? $user->username) . '" (' . ucfirst($user->role) . ') telah logout dari sistem.');
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        Auth::logout();

        try {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari sistem.');
    }

    public function forgotPasswordVerify(Request $request)
    {
        $request->validate([
            'identity'      => 'required|string',
            'whatsapp'      => 'required|string',
            'referral_code' => 'required|string',
        ], [
            'identity.required'      => 'Username / Email wajib diisi.',
            'whatsapp.required'      => 'No. WhatsApp (Validasi 1) wajib diisi.',
            'referral_code.required' => 'Kode Referral (Validasi 2) wajib diisi.',
        ]);

        $identity = trim($request->input('identity'));
        $inputWa = preg_replace('/[^0-9]/', '', $request->input('whatsapp'));
        $inputRef = trim($request->input('referral_code'));

        // Cari user berdasarkan username, email, atau nama
        $user = \App\Models\User::where('username', $identity)
            ->orWhere('email', $identity)
            ->orWhere('name', $identity)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'forgot_error' => 'Akun pengguna dengan Username/Email tersebut tidak ditemukan.',
            ])->withInput();
        }

        $dbWa = preg_replace('/[^0-9]/', '', (string)$user->whatsapp);
        $dbRef = trim((string)$user->referral_code);

        // Validasi 1: WhatsApp
        $waValid = (!empty($dbWa) && !empty($inputWa) && ($dbWa === $inputWa || str_ends_with($dbWa, substr($inputWa, -8)) || str_ends_with($inputWa, substr($dbWa, -8))));

        // Validasi 2: Kode Referral
        $refValid = (!empty($dbRef) && !empty($inputRef) && strcasecmp($dbRef, $inputRef) === 0);

        if (!$waValid || !$refValid) {
            $errorMsg = 'Validasi Gagal! ';
            if (!$waValid && !$refValid) {
                $errorMsg .= 'No. WhatsApp dan Kode Referral tidak cocok dengan data terdaftar.';
            } elseif (!$waValid) {
                $errorMsg .= 'No. WhatsApp (Validasi 1) yang Anda masukkan tidak cocok.';
            } else {
                $errorMsg .= 'Kode Referral (Validasi 2) yang Anda masukkan tidak cocok.';
            }

            return back()->withErrors([
                'forgot_error' => $errorMsg,
            ])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'forgot_error' => 'Akun Anda saat ini sedang dinonaktifkan oleh Administrator.',
            ])->withInput();
        }

        // Login user secara otomatis & perintahkan modal ganti sandi
        Auth::login($user);
        session(['show_force_change_password' => true]);

        ActivityLog::record('FORGOT_PASSWORD_LOGIN', "Pengguna \"{$user->name}\" berhasil memulihkan akses login melalui Validasi 2-Langkah (WhatsApp & Kode Referral).");

        return redirect()->route('admin.dashboard')->with('success', 'Validasi 2-Langkah Berhasil! Anda telah login. Silakan ganti password baru Anda di bawah ini.');
    }
}
