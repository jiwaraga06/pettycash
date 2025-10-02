<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function showRole()
    {
        $role = Role::all();
        $data = ['role' => $role];
        return view('Admin.Role.role', $data);
    }

    public function addRole(Request $request)
    {
        $validate = $request->validate([
            "nama_role" => "required|string"
        ]);
        Role::create($validate);
        return redirect()->route('showRole')
            ->with('success', 'Role Permission berhasil dibuat!');
    }
    public function editRole(Request $request, $id)
    {
        $validate = $request->validate([
            "nama_role" => "required|string"
        ]);
        $role = Role::findOrFail($id);
        $role->update($validate);
        return redirect()->route('showRole')
            ->with('success', 'Role Permission berhasil diubah!');
    }
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('showRole')
            ->with('success', 'Role Permission berhasil dihapus!');
    }
}
