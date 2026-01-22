<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        Auth::user()->sendEmailVerificationNotification();

        return $request->user()->hasVerifiedEmail()
            ? ($request->user()->status === 'pending' ? redirect()->route('login') : redirect()->intended(route('dashboard', absolute: false)))
            : view('auth.verify-email');
    }
}
