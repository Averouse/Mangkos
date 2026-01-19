# Profile Completion Before Verification

## Overview
Users and owners must now complete their profile information before they can upload verification documents (KTM/KTP).

## Requirements

### For USERS (Students)
Before uploading KTM verification, users must fill:
- ✅ **Phone Number** (WhatsApp) - Required for contact after matchmaking
- ✅ **Campus** - University name
- ✅ **Major** - Program of study
- ✅ **Year** - Academic year/batch

### For OWNERS (Kost Owners)
Before uploading KTP verification, owners must fill:
- ✅ **Phone Number** (WhatsApp) - Required for tenant contact
- Address (optional but recommended)

## Implementation Details

### User Dashboard (`resources/views/user/dashboard.blade.php`)
1. Profile form now has required fields with asterisk (*)
2. Profile form submits to `/user/profile/update` route
3. KTM upload section is disabled (grayed out) until profile is complete
4. Yellow warning message shows if profile incomplete
5. Blue info message shows when profile is complete

### Owner Dashboard (`resources/views/owner/dashboard.blade.php`)
1. Profile form requires phone number
2. Profile form submits to `/owner/profile/update` route
3. KTP upload section is disabled until phone number is filled
4. Yellow warning message shows if phone incomplete
5. Blue info message shows when phone is filled

### Backend Validation

#### AuthController (`app/Http/Controllers/AuthController.php`)
- `updateProfile()` - Validates and saves user profile (name, phone, campus, major, year)
- `uploadKtm()` - Checks profile completion before allowing KTM upload

#### OwnerController (`app/Http/Controllers/OwnerController.php`)
- `updateProfile()` - Validates and saves owner profile (name, phone, address)
- `uploadKtp()` - Checks phone number before allowing KTP upload

### Routes (`routes/web.php`)
```php
Route::post('/user/profile/update', [AuthController::class, 'updateProfile']);
Route::post('/owner/profile/update', [OwnerController::class, 'updateProfile']);
```

## User Flow

### For Users:
1. Register → Login → Dashboard
2. Fill profile: phone, campus, major, year
3. Click "Simpan Perubahan" (Save Changes)
4. Upload KTM card photo + selfie with KTM
5. Wait for admin verification
6. Access matchmaking after approval

### For Owners:
1. Register → Login → Dashboard
2. Fill profile: phone number (minimum)
3. Click "Simpan Perubahan" (Save Changes)
4. Upload KTP card photo + selfie with KTP
5. Wait for admin verification
6. Add kost listings after approval

## Benefits

1. **Complete Contact Information** - Ensures users can contact each other after matching
2. **Student Verification** - Campus/major/year confirms user is actually a student
3. **Better Matchmaking** - More complete profiles lead to better matches
4. **Owner Accountability** - Phone number ensures tenants can reach owners
5. **Data Quality** - Prevents incomplete profiles in the system

## Technical Notes

- Profile fields are stored in `users` table (already in fillable array)
- No migration needed - fields already exist in database
- Frontend validation via HTML5 `required` attribute
- Backend validation in controller methods
- Form disabled via inline style when profile incomplete
- AJAX form submission for smooth UX
