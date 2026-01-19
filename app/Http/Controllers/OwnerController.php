<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kost;
use App\Models\RentalApplication;
use App\Models\Notification;

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
        
        // Get rental applications for owner's kosts
        $kostIds = $kosts->pluck('id');
        $rentalApplications = RentalApplication::whereIn('kost_id', $kostIds)
            ->with(['user', 'kost'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pendingApplications = $rentalApplications->where('status', 'pending')->count();
        
        return view('owner.dashboard', compact('kosts', 'totalRooms', 'occupiedRooms', 'rentalApplications', 'pendingApplications'));
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
        
        // Check profile completion
        if (!Auth::user()->phone) {
            return response()->json(['success' => false, 'message' => 'Lengkapi nomor WhatsApp terlebih dahulu'], 400);
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

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20'
        ]);
        
        Auth::user()->update($validated);
        
        return response()->json(['success' => true]);
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $file = $request->file('photo');
        $filename = time() . '_profile_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/profiles'), $filename);
        
        Auth::user()->update(['profile_photo' => $filename]);
        
        return response()->json(['success' => true, 'photo' => $filename]);
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
    
    public function approveRental($id)
    {
        $application = RentalApplication::with('kost')
            ->whereHas('kost', function($query) {
                $query->where('owner_id', Auth::id());
            })
            ->findOrFail($id);
        
        $application->status = 'approved';
        $application->save();
        
        // Create notification
        Notification::create([
            'user_id' => $application->user_id,
            'type' => 'rental_status',
            'title' => 'Pengajuan Kos Disetujui',
            'message' => 'Pengajuan Anda untuk ' . $application->kost->name . ' telah disetujui!',
            'related_id' => $application->kost_id,
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function rejectRental($id)
    {
        $application = RentalApplication::with('kost')
            ->whereHas('kost', function($query) {
                $query->where('owner_id', Auth::id());
            })
            ->findOrFail($id);
        
        $application->status = 'rejected';
        $application->save();
        
        $reason = request('reason', 'Kamar tidak tersedia');
        
        // Create notification
        Notification::create([
            'user_id' => $application->user_id,
            'type' => 'rental_status',
            'title' => 'Pengajuan Kos Ditolak',
            'message' => 'Maaf, pengajuan Anda untuk ' . $application->kost->name . ' ditolak.',
            'related_id' => $application->kost_id,
            'rejection_reason' => $reason,
        ]);
        
        return response()->json(['success' => true]);
    }

}