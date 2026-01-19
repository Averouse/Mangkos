# Run this migration first
php artisan migrate

# Then update existing users who haven't uploaded verification yet
# Run this in tinker or create a seeder:
# php artisan tinker

# UPDATE users SET status = 'unverified' WHERE (id_card_photo IS NULL OR selfie_with_id_photo IS NULL) AND status = 'pending';
