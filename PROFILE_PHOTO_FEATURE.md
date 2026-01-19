# Profile Photo Upload Feature

## Summary
Added profile photo upload functionality for both users (mahasiswa) and owners (pemilik kost).

## Changes Made

### 1. Database
- The `photo` field already exists in the `users` table (in User model fillable array)
- Profile photos are stored in `public/uploads/profiles/`

### 2. Controllers

#### AuthController.php (for Users)
- Added `uploadProfilePhoto()` method to handle user profile photo uploads
- Validates image file (jpeg, png, jpg, max 2MB)
- Stores photo in `public/uploads/profiles/`
- Updates user record with filename

#### OwnerController.php (for Owners)
- Added `uploadProfilePhoto()` method to handle owner profile photo uploads
- Same validation and storage logic as user controller

### 3. Routes (web.php)
Added two new routes:
- `POST /user/profile/photo` - User profile photo upload
- `POST /owner/profile/photo` - Owner profile photo upload

### 4. Views

#### user/dashboard.blade.php
- Updated profile photo display to show uploaded photo or fallback to UI Avatars
- Added file input and camera button for photo upload
- Added JavaScript handler for photo upload with AJAX

#### owner/dashboard.blade.php
- Added profile photo section with upload button
- Updated to show uploaded photo or fallback to UI Avatars
- Added JavaScript handler for photo upload with AJAX

## Usage

### For Users (Mahasiswa)
1. Go to Dashboard
2. Click the camera icon on profile photo
3. Select an image file (JPG, PNG, max 2MB)
4. Photo will be uploaded and displayed immediately

### For Owners (Pemilik Kost)
1. Go to Dashboard
2. Open "Profil Saya" modal
3. Click the camera icon on profile photo
4. Select an image file (JPG, PNG, max 2MB)
5. Photo will be uploaded and displayed immediately

## File Structure
```
public/
  uploads/
    profiles/          # Profile photos stored here
      .gitkeep
    ktm/              # KTM verification photos
    ktp/              # KTP verification photos
    kosts/            # Kost property photos
```

## Technical Details
- File naming: `{timestamp}_profile_{original_filename}`
- Max file size: 2MB
- Allowed formats: JPEG, PNG, JPG
- Photos are stored with object-cover CSS class for proper display
- Fallback to UI Avatars API if no photo uploaded
