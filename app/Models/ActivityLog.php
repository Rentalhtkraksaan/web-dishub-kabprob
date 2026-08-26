<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper to record user activity quickly.
     */
    public static function record(string $action, string $description): self
    {
        $user = Auth::user();

        $ip = request()->header('CF-Connecting-IP') 
            ?: (request()->header('X-Forwarded-For') ? explode(',', request()->header('X-Forwarded-For'))[0] : null)
            ?: request()->ip();

        if ($ip === '::1') {
            $ip = '127.0.0.1';
        }

        return self::create([
            'user_id'     => $user ? $user->id : null,
            'user_name'   => $user ? ($user->name ?? $user->username) : 'Sistem / Tamu',
            'user_role'   => $user ? $user->role : 'guest',
            'action'      => strtoupper($action),
            'description' => $description,
            'ip_address'  => trim($ip),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
