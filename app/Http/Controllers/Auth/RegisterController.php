<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\KategoriProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $kategori = KategoriProduct::all();
        return view('auth.register', compact('kategori'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nim' => 'required|unique:users',
            'email' => ['required', 'unique:users', 'regex:/^[a-zA-Z0-9._%+-]+@cic\.ac\.id$/'],
            'name' => 'required|string',
            'prodi' => 'required|string',
            'kios' => 'required|string',
            'kategori_products' => 'required|integer',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8',
        ], [
            'nim.required' => 'Nomor Induk Mahasiswa Wajib Di Isi.',
            'name.required' => 'Nama Lengkap Mahasiswa Wajib Di Isi.',
            'email.required' => 'Email Aktif Mahasiswa Wajib Di Isi.',
            'prodi.required' => 'Prodi/Hima Mahasiswa Wajib Di Isi.',
            'kios.required' => 'Nama Kios Penjualan Mahasiswa Wajib Di Isi.',
            'kategori_products.required' => 'Kategori Makanan Yang Akan Di Jual Oleh Mahasiswa Wajib Di Isi.',
            'password.required' => 'Password Wajib Di Isi.',
            'password_confirmation.required' => 'Konfirmasi Password Wajib Di Isi.',

            'nim.unique' => 'Nomor Induk Mahasiswa Sudah Terdaftar.',
            'email.unique' => 'Email Mahasiswa Sudah Terdaftar.',
            'email.regex' => 'Email Harus Menggunakan Email @cic.ac.id.',
            'password.confirmed' => 'Konfirmasi Passwod Tidak Match Dengan Password.',
            'password.min' => 'Password Harus Berupa 8 Huruf/Angka.',
            'password_confirmation' => 'Konfirmasi Password Harus Berupa 8 Huruf/Angka.'
        ]);

        $user = User::create([
            'nim' => $request->nim,
            'email' => $request->email,
            'name' => $request->name,
            'prodi' => $request->prodi,
            'kios' => $request->kios,
            'kategori_products' => $request->kategori_products,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('Mahasiswa');

        Auth::login($user);
        return redirect()->route('dashboard');
    }
}
