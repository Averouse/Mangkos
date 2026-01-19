<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Notification;

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
            
            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'profile_verification',
                'title' => 'Profil Disetujui',
                'message' => 'Selamat! Profil Anda telah diverifikasi dan disetujui.',
            ]);
            
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
            
            $reason = request('reason', 'Dokumen tidak valid');
            
            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => 'profile_verification',
                'title' => 'Profil Ditolak',
                'message' => 'Maaf, profil Anda ditolak. Silakan periksa kembali dokumen verifikasi Anda.',
                'rejection_reason' => $reason,
            ]);
            
            return response()->json(['success' => true, 'message' => 'User rejected successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Cannot reject admin users']);
    }

    public function approveKost($id)
    {
        $kost = \App\Models\Kost::findOrFail($id);
        $kost->status = 'approved';
        $kost->save();
        
        // Notify owner
        Notification::create([
            'user_id' => $kost->owner_id,
            'type' => 'kost_verification',
            'title' => 'Kost Disetujui',
            'message' => 'Kost "' . $kost->name . '" telah disetujui dan sekarang terlihat publik.',
            'related_id' => $kost->id,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Kost approved successfully']);
    }

    public function rejectKost($id)
    {
        $kost = \App\Models\Kost::findOrFail($id);
        $kost->status = 'rejected';
        $kost->save();
        
        $reason = request('reason', 'Informasi tidak lengkap');
        
        // Notify owner
        Notification::create([
            'user_id' => $kost->owner_id,
            'type' => 'kost_verification',
            'title' => 'Kost Ditolak',
            'message' => 'Kost "' . $kost->name . '" ditolak. Silakan periksa kembali informasi yang diberikan.',
            'related_id' => $kost->id,
            'rejection_reason' => $reason,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Kost rejected successfully']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
