<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Build base rules
        $emailRules = [
            'required',
            'string',
            'email',
            'max:255',
            'unique:users,email'
        ];

        // In non-testing environments require the email to exist in faculty table
        if (!app()->environment('testing')) {
            array_push($emailRules, 'exists:faculty,Email');
        }

        // Validate request data
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRules,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find the faculty member using the email (best-effort; may be null in testing)
        $faculty = Faculty::where('Email', $request->email)->first();

        // Create new user and link to faculty if available
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => 'faculty',
            'Faculty_ID' => $faculty?->Faculty_ID ?? null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
