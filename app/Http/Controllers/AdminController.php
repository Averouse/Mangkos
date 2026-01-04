<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
        }

        return back()->withErrors(['email' => 'Invalid admin credentials']);
    }

    public function dashboard()
    {
        // Exclude admins from all counts and lists
        $totalUsers = User::whereIn('role', ['user', 'owner'])->count();
        $studentCount = User::where('role', 'user')->count();
        $ownerCount = User::where('role', 'owner')->count();
        $pendingCount = User::whereIn('role', ['user', 'owner'])->where('status', 'pending')->count();
        $approvedCount = User::whereIn('role', ['user', 'owner'])->where('status', 'approved')->count();
        
        $pendingUsers = User::whereIn('role', ['user', 'owner'])->where('status', 'pending')->latest()->get();
        $approvedUsers = User::whereIn('role', ['user', 'owner'])->where('status', 'approved')->latest()->get();
        
        // Add kost data
        $pendingKosts = \App\Models\Kost::with('owner')->where('status', 'pending')->latest()->get();
        $pendingKostCount = $pendingKosts->count();

        $approvedKosts = \App\Models\Kost::with('owner')->where('status', 'approved')->latest()->get();


        return view('admin.dashboard', compact(
            'totalUsers', 'studentCount', 'ownerCount', 'pendingCount', 'approvedCount',
            'pendingUsers', 'approvedUsers', 'pendingKosts', 'pendingKostCount', 'approvedKosts'
        ));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        
        if (in_array($user->role, ['user', 'owner'])) {
            $user->status = 'approved';
            $user->save();
            
            return response()->json(['success' => true, 'message' => 'User approved successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Cannot approve admin users']);
    }

    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        
        if (in_array($user->role, ['user', 'owner'])) {
            $user->status = 'rejected';
            $user->save();
            
            return response()->json(['success' => true, 'message' => 'User rejected successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Cannot reject admin users']);
    }

    public function approveKost($id)
    {
        $kost = \App\Models\Kost::findOrFail($id);
        $kost->status = 'approved';
        $kost->save();
        
        return response()->json(['success' => true, 'message' => 'Kost approved successfully']);
    }

    public function rejectKost($id)
    {
        $kost = \App\Models\Kost::findOrFail($id);
        $kost->status = 'rejected';
        $kost->save();
        
        return response()->json(['success' => true, 'message' => 'Kost rejected successfully']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
