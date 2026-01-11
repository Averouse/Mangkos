<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\RentalApplication;
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
        
        // Generate validation code
        $validationCode = 'MKS-' . strtoupper(Str::random(8));
        
        // Create rental application
        $application = RentalApplication::create([
            'user_id' => Auth::id(),
            'kost_id' => $validated['kost_id'],
            'status' => 'pending',
            'message' => $validationCode
        ]);
        
        $kost = Kost::with('owner')->findOrFail($validated['kost_id']);
        
        return response()->json([
            'success' => true,
            'validation_code' => $validationCode,
            'kost_name' => $kost->name,
            'owner_name' => $kost->owner->name,
            'owner_phone' => $kost->owner->phone,
            'user_name' => Auth::user()->name
        ]);
    }

}