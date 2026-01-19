# Rejection Reason Implementation

## Overview
Added rejection reason functionality for all rejection actions (user/owner profile, kost listing, rental applications).

## Database Changes
- Added `rejection_reason` field to `notifications` table (nullable text)

## Rejection Reason Options

### For User/Owner Profile Rejection:
1. "Dokumen tidak valid" (Invalid document)
2. "Foto tidak jelas" (Photo unclear)
3. "Data tidak sesuai" (Data mismatch)
4. "Dokumen kadaluarsa" (Expired document)
5. Custom reason (fillable text field)

### For Kost Listing Rejection:
1. "Informasi tidak lengkap" (Incomplete information)
2. "Foto tidak memadai" (Inadequate photos)
3. "Alamat tidak valid" (Invalid address)
4. "Harga tidak wajar" (Unreasonable price)
5. Custom reason (fillable text field)

### For Rental Application Rejection:
1. "Kamar tidak tersedia" (Room unavailable)
2. "Tidak memenuhi syarat" (Does not meet requirements)
3. "Sudah ada penyewa lain" (Already rented to someone else)
4. "Profil tidak sesuai" (Profile mismatch)
5. Custom reason (fillable text field)

## Implementation

### Backend (Controllers):
All rejection methods now accept `reason` parameter from request:
```php
$reason = request('reason', 'Default reason');
```

### Frontend (Modal):
Rejection modal with:
- Radio buttons for predefined reasons
- Text input for custom reason
- Submit button sends reason to backend

### Notification Display:
Users see rejection reason in notification message:
```
Title: "Pengajuan Ditolak"
Message: "Maaf, pengajuan ditolak."
Reason: "Dokumen tidak valid" (displayed below message)
```

## Usage Flow

1. Admin/Owner clicks "Reject" button
2. Modal opens with reason options
3. Select predefined reason OR enter custom reason
4. Click "Submit Rejection"
5. Backend creates notification with rejection_reason
6. User sees notification with reason in their notification bell

## Files Modified
- `notifications` table migration
- `Notification` model (added to fillable)
- `AdminController.php` (rejectUser, rejectKost)
- `OwnerController.php` (rejectRental)
- Admin dashboard (rejection modal)
- Owner dashboard (rejection modal)
- User dashboard (notification display with reason)
