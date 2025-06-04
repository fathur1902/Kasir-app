<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    public function settings(Request $request)
    {
        $editTargetUser = null;

        // Ambil user yang ingin diedit oleh admin
        if (auth()->user()->role === 'admin' && $request->has('user_id')) {
            $editTargetUser = User::findOrFail($request->get('user_id'));
        }

        $users = User::where('role', 'user')->get();
        $currentUser = auth()->user();

        return view('settings', [
            'users' => $users,
            'editTargetUser' => $editTargetUser,
            'user' => $currentUser,
        ]);
    }

    public function editPassword($id)
    {
        $targetUser = User::findOrFail($id);
        $currentUser = auth()->user();
        $users = $currentUser->role === 'admin' ? User::where('role', 'user')->get() : null;

        return view('settings', [
            'user' => $currentUser,
            'users' => $users,
            'editTargetUser' => $targetUser, // ganti dari 'editUser' ke 'editTargetUser'
        ]);
    }


    public function updatePassword(Request $request)
    {
        if (auth()->user()->role === 'admin' && $request->filled('user_id')) {
            $user = User::findOrFail($request->user_id);
        } else {
            $request->validate([
                'current_password' => ['required', 'current_password'],
            ]);
            $user = Auth::user();
        }

        $request->validate([
            'new_password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }


    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('settings')->with('success', 'Data kasir berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        // Jangan biarkan admin hapus dirinya sendiri
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }
}
