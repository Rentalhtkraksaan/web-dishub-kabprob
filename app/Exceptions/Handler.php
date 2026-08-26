<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Sesi kedaluwarsa (CSRF token mismatch). Silakan muat ulang halaman.',
                    'csrf_expired' => true,
                ], 419);
            }

            return redirect()->back()->withInput($request->except('password', 'password_confirmation', '_token'))->with('error', 'Sesi formulir telah disegarkan secara otomatis. Silakan klik tombol Simpan kembali.');
        });
    }
}
