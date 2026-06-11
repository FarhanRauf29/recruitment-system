<?php

namespace App\Http\Controllers\Auth;

use App\Models\Pelamar;
use App\Http\Controllers\Controller;
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
        return view('auth.register');
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelamar',
        ]);

        event(new Registered($user));

        Auth::login($user);

        Pelamar::create([
            'user_id' => $user->id,
            'nama_lengkap' => $request->name,
            'email' => $user->email,
            'no_telepon' => null,
            'alamat' => null,
            'jenis_kelamin' => null,
            'tanggal_lahir' => null,
            'pendidikan_terakhir' => null,
        ]);

        return redirect(route('dashboard', absolute: false));
    }
}
