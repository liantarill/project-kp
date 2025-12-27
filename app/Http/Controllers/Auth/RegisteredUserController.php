<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $departments = Department::orderBy('name')->get();
        $institutions = Institution::orderBy('name')->get();

        return view('auth.register', compact('departments', 'institutions'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'institution_id' => ['nullable', 'exists:institutions,id'],
            'major' => ['required', 'string', 'max:255'],
            'level' => ['required', 'in:SMA,SMK,D1,D2,D3,D4,S1'],
            'department_id' => ['required', 'exists:departments,id'],
            'acceptance_proof' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $acceptanceProofPath = null;

        if ($request->hasFile('acceptance_proof')) {
            $acceptanceProofPath = $request->file('acceptance_proof')
                ->store('acceptance-proofs', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'institution_id' => $request->institution_id,
            'major' => $request->major,
            'level' => $request->level,
            'department_id' => $request->department_id,
            'acceptance_proof' => $acceptanceProofPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
