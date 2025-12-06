<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * ProfileController
 *
 * Mengelola fitur profil pengguna:
 * - Menampilkan form edit profil
 * - Mengupdate data profil
 * - Menghapus akun pengguna
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman form profil user.
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // user yang sedang login
        ]);
    }

    /**
     * Mengupdate informasi profil user.
     *
     * - Menggunakan Form Request (ProfileUpdateRequest) untuk validasi
     * - Jika email berubah → reset verifikasi email
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Isi field user berdasarkan data terverifikasi dari request
        $request->user()->fill($request->validated());

        // Jika email diubah → wajib verifikasi ulang
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Simpan perubahan profil
        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun user dari sistem.
     *
     * Flow:
     * 1. Validasi password user sebelum menghapus
     * 2. Logout user
     * 3. Hapus akun dari database
     * 4. Hapus session
     * 5. Redirect ke homepage
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password untuk keamanan
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Logout dulu sebelum menghapus akun
        Auth::logout();

        // Hapus user dari database
        $user->delete();

        // Invalidate session agar tidak bisa dipakai lagi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}