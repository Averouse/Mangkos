<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kost;

class OwnerController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'owner') {
            return redirect()->route('dashboard');
        }
        
        $owner = Auth::user();
        $kosts = Kost::where('owner_id', $owner->id)->get();
        $totalRooms = $kosts->sum('total_rooms');
        $occupiedRooms = $kosts->sum(function($kost) {
            return $kost->total_rooms - $kost->available_rooms;
        });
        
        return view('owner.dashboard', compact('kosts', 'totalRooms', 'occupiedRooms'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:putra,putri,campur',
            'total_rooms' => 'required|integer|min:1'
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['available_rooms'] = $validated['total_rooms'];

        Kost::create($validated);

        return response()->json(['success' => true, 'message' => 'Kost berhasil ditambahkan']);
    }

    public function uploadKtp(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $file = $request->file('ktp_photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/ktp'), $filename);
        
        Auth::user()->update([
            'ktp_photo' => $filename,
            'status' => 'pending' // Reset to pending for KTP review
        ]);
        
        return response()->json(['success' => true, 'message' => 'KTP uploaded successfully']);
    }
}