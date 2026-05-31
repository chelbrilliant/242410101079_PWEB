<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Tampilkan halaman profil
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    // Form edit profil
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // Update nama dan email
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Update cookie nama jika nama berubah
        $cookie = cookie('last_user_name', $user->name, 60 * 24 * 30);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui!')
            ->withCookie($cookie);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
        ]);

        $user = auth()->user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diperbarui!');
    }
}
