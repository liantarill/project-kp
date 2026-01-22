<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        //ini kalo pencet dari gmail\

        // 1. Jika email BELUM diverifikasi → verifikasi dulu
        if (! $request->user()->hasVerifiedEmail()) {
            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
        }

        // 2. Jika participant tapi masih pending admin
        if ($request->user()->role === 'participant' && $request->user()->status === 'pending') {
            auth()->logout();

            return redirect()
                ->route('login')
                ->with('error', 'Email berhasil diverifikasi, namun akun kamu masih menunggu verifikasi admin.');
        }

        // 3. Selain itu, lanjut ke dashboard
        return redirect()->intended(
            route('dashboard', absolute: false) . '?verified=1'
        );
    }
}
