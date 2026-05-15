<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all()->groupBy('group');
        return view('admin.roles', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'group' => 'required|string',
        ]);

        Role::create([
            'name' => $request->name,
            'group' => $request->group,
        ]);

        return back()->with('success', 'Rol creado correctamente.');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Rol eliminado.');
    }
}
