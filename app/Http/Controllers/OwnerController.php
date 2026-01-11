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
            'total_rooms' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facilities' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Handle photo uploads
        $photoFilenames = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/kosts'), $filename);
                $photoFilenames[] = $filename;
            }
        }

        $validated['owner_id'] = Auth::id();
        $validated['available_rooms'] = $validated['total_rooms'];
        $validated['photos'] = $photoFilenames;
        $validated['facilities'] = $request->facilities ?? [];

        Kost::create($validated);

        return response()->json(['success' => true, 'message' => 'Kost berhasil ditambahkan']);
    }

    public function uploadKtp(Request $request)
    {
        if (Auth::user()->role !== 'owner') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'id_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'selfie_with_id_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Upload ID card photo
        $idCardFile = $request->file('id_card_photo');
        $idCardFilename = time() . '_id_card_' . $idCardFile->getClientOriginalName();
        $idCardFile->move(public_path('uploads/ktp'), $idCardFilename);
        
        // Upload selfie photo
        $selfieFile = $request->file('selfie_with_id_photo');
        $selfieFilename = time() . '_selfie_' . $selfieFile->getClientOriginalName();
        $selfieFile->move(public_path('uploads/ktp'), $selfieFilename);
        
        Auth::user()->update([
            'id_card_photo' => $idCardFilename,
            'selfie_with_id_photo' => $selfieFilename,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true, 'message' => 'KTP verification photos uploaded successfully']);
    }

    public function toggleFull($id)
    {
        $kost = Kost::where('id', $id)->where('owner_id', Auth::id())->first();
        
        if (!$kost) {
            return response()->json(['success' => false, 'message' => 'Kost not found']);
        }
        
        $kost->is_full = !$kost->is_full;
        $kost->save();
        
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $kost = Kost::where('id', $id)->where('owner_id', Auth::id())->first();
        if (!$kost) return response()->json(['success' => false, 'message' => 'Kost not found']);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:putra,putri,campur',
            'total_rooms' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facilities' => 'nullable|array',
            'existing_photos' => 'nullable|array',
            'new_photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Handle photos
        $finalPhotos = $request->existing_photos ?? [];
        
        // Add new photos
        if ($request->hasFile('new_photos')) {
            foreach ($request->file('new_photos') as $photo) {
                $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads/kosts'), $filename);
                $finalPhotos[] = $filename;
            }
        }
        
        $validated['photos'] = $finalPhotos;
        $validated['facilities'] = $request->facilities ?? [];
        $validated['status'] = 'pending'; // Reset to pending for admin review
        
        $kost->update($validated);
        
        return response()->json(['success' => true, 'message' => 'Kost updated successfully']);
    }

}