<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\RentalApplication;
use App\Models\Notification;
use Illuminate\Http\Request;
use Auth;
use Str;

class KostController extends Controller
{
    public function kostSearch(Request $request)
    {
        $query = Kost::with('owner')->where('status', 'approved');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
            });
        }
        
        $kosts = $query->latest()->get();
        
        return view('user.kostsearching', compact('kosts'));
    }

    public function show($id)
    {
        $kost = Kost::with('owner')->findOrFail($id);
        return view('user.kostdetail', compact('kost'));
    }
    
    public function applyRental(Request $request)
    {
        // Check if user is verified
        if (Auth::user()->status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus terverifikasi terlebih dahulu untuk mengajukan sewa'
            ]);
        }
        
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id'
        ]);
        
        // Check if already applied
        $existing = RentalApplication::where('user_id', Auth::id())
            ->where('kost_id', $validated['kost_id'])
            ->first();
            
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mengajukan sewa untuk kost ini'
            ]);
        }
        
        // Create rental application
        $application = RentalApplication::create([
            'user_id' => Auth::id(),
            'kost_id' => $validated['kost_id'],
            'status' => 'pending',
        ]);
        
        $kost = Kost::with('owner')->findOrFail($validated['kost_id']);
        
        // Notify owner
        Notification::create([
            'user_id' => $kost->owner_id,
            'type' => 'rental_application',
            'title' => 'Pengajuan Sewa Baru',
            'message' => Auth::user()->name . ' mengajukan sewa untuk ' . $kost->name,
            'related_id' => $application->id,
        ]);
        
        return response()->json([
            'success' => true,
            'application_time' => $application->created_at->format('d M Y H:i'),
            'kost_name' => $kost->name,
            'owner_name' => $kost->owner->name,
            'owner_phone' => $kost->owner->phone,
            'user_name' => Auth::user()->name
        ]);
    }

}