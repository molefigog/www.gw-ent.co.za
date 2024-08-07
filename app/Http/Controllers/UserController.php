<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Flash\Flash;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function creditUp(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validation on the amount
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $amount = $request->input('amount');
        $newBalance = $user->balance + $amount;

        // Update the user's balance
        $user->update(['balance' => $newBalance]);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User balance topped up successfully.');
    }

    public function getTotalUsers()
    {
        $totalUsers = User::count();

        return view('admin.dash', ['totalUsers' => $totalUsers]);
    }
    
}
