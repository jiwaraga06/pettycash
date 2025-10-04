<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showAcount()
    {
        $account = User::with(['role'])->get();
        $role = Role::all();
        $data = [
            'account' => $account,
            'role' => $role,
        ];
        return view('Admin.Account.account', $data);
    }

    public function addAccount(Request $request)
    {
        $validate = $request->validate([
            "name" => "required|string",
            "email" => "required|string|email",
            "password" => "required|string",
            "id_role" => "required",
        ]);
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "id_role" => $request->id_role,
        ]);
        return redirect()->route('showAcount')
            ->with('success', 'Account berhasil dibuat!');
    }
    public function editAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_role = $request->id_role;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('showAcount')
            ->with('success', 'Account berhasil diubah!');
    }
    public function deleteAccount($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('showAcount')
            ->with('success', 'Account berhasil dihapus!');
    }
}
