<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManageAdminController extends Controller
{
    public function index(){
          $admins = User::get();
        return view('manage_admin', compact('admins'));
    }
    public function destroy($id)
{
    User::where('id', $id)->delete();
    

    return redirect()->route('admin.index')->with('success', 'User deleted successfully.');
}


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role_id' => 'required|exists:roles,id',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id,
    ]);


    return redirect()->back()->with('success', 'User created successfully!');
}



}
