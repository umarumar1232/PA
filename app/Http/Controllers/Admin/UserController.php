<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->role;

        $query = User::query();

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users', 'role'));
    }
    public function mahasiswa(Request $request)
    {
        $search = $request->search;

        $users = User::where('role', 'mahasiswa')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nim', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();

        return view('admin.mahasiswa.index', compact('users', 'search'));
    }
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required'
        ]);

        $user = User::create([
            'nama' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(Str::random(16)), // password sementara random
            'role' => $request->role,
        ]);

        // kirim link reset password
        Password::sendResetLink([
            'email' => $user->email
        ]);

        return redirect()->back()->with('success', 'User berhasil dibuat dan email reset password dikirim.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,mahasiswa',
        ]);

        // Cegah admin mengubah role dirinya sendiri
        if ($user->id === auth()->id() && $request->role !== 'admin') {
            return back()->with('error', 'Kamu tidak bisa mengubah role akun sendiri.');
        }

        $user->update([
            'nama'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }
}