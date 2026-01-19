<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.login');
    }

    public function showRegister()
    {
        return view('pages.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,owner'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => 'unverified'
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function uploadKtm(Request $request)
    {
        if (Auth::user()->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // Check profile completion
        if (!Auth::user()->phone || !Auth::user()->campus || !Auth::user()->major || !Auth::user()->year) {
            return response()->json(['success' => false, 'message' => 'Lengkapi profil terlebih dahulu'], 400);
        }
        
        $request->validate([
            'ktm_card_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'ktm_selfie_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        // Upload KTM card photo
        $ktmCardFile = $request->file('ktm_card_photo');
        $ktmCardFilename = time() . '_ktm_card_' . $ktmCardFile->getClientOriginalName();
        $ktmCardFile->move(public_path('uploads/ktm'), $ktmCardFilename);
        
        // Upload selfie photo
        $ktmSelfieFile = $request->file('ktm_selfie_photo');
        $ktmSelfieFilename = time() . '_ktm_selfie_' . $ktmSelfieFile->getClientOriginalName();
        $ktmSelfieFile->move(public_path('uploads/ktm'), $ktmSelfieFilename);
        
        Auth::user()->update([
            'id_card_photo' => $ktmCardFilename,
            'selfie_with_id_photo' => $ktmSelfieFilename,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true, 'message' => 'KTM verification photos uploaded successfully']);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'campus' => 'required|string|max:255',
            'major' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100'
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
