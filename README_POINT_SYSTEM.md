# Sistem Poin Otomatis untuk UserUnit

## Overview
Sistem ini secara otomatis menambah dan mengurangi poin user berdasarkan akses ke unit yang diberikan atau dicabut.

## Komponen Sistem

### 1. UserUnitObserver
**File**: `app/Observers/UserUnitObserver.php`

Observer ini secara otomatis menangani penambahan dan pengurangan poin:

- **created()**: Menambah poin user ketika UserUnit dibuat
- **deleted()**: Mengurangi poin user ketika UserUnit dihapus

### 2. Registration di AppServiceProvider
**File**: `app/Providers/AppServiceProvider.php`

Observer didaftarkan di method `boot()`:
```php
UserUnit::observe(UserUnitObserver::class);
```

### 3. User Model Update
**File**: `app/Models/User.php`

Kolom `point` ditambahkan ke `$fillable` untuk memungkinkan update otomatis.

## Cara Kerja

### Saat Unit Dibuka untuk User:
1. UserUnit record dibuat
2. Observer `created()` dipanggil
3. Poin unit ditambahkan ke user
4. User dapat melihat poin di halaman Rewards

### Saat Akses Unit Dicabut:
1. UserUnit record dihapus
2. Observer `deleted()` dipanggil
3. Poin unit dikurangi dari user (tidak bisa di bawah 0)
4. User tidak bisa lagi mengakses unit tersebut

## Integrasi dengan Sistem Reward

Sistem ini terintegrasi dengan sistem reward yang sudah ada:

- **UserRewardController**: Menggunakan `$user->point` untuk validasi dan pengurangan poin
- **Rewards Page**: Menampilkan poin user yang diupdate secara real-time
- **Point Exchange**: User dapat menukar poin dengan hadiah

## Migration untuk Data Existing

**File**: `database/migrations/2025_07_28_032317_add_points_to_existing_user_units.php`

Migration ini memberikan poin kepada user yang sudah memiliki akses unit sebelum sistem ini diimplementasikan.

## Tempat Penggunaan

### 1. ProgressControl (Filament Admin)
**File**: `app/Filament/Resources/ClassroomResource/Pages/ProgressControl.php`

Method `toggleUnitAccess()` menggunakan observer untuk menambah/mengurangi poin otomatis.

### 2. ProgressBadge (Livewire Component)
**File**: `app/Http/Livewire/ProgressBadge.php`

Method `toggleProgress()` menggunakan observer untuk menambah/mengurangi poin otomatis.

## Keamanan

- Poin tidak bisa di bawah 0 (menggunakan `max(0, $user->point - $unit->point)`)
- Hanya unit dengan poin > 0 yang mempengaruhi poin user
- Observer menggunakan relasi Eloquent yang aman

## Testing

Sistem dapat ditest dengan:

```bash
# Test observer creation
php artisan tinker
$user = App\Models\User::first();
$unit = App\Models\Unit::where('point', '>', 0)->first();
echo "Before: " . $user->point;
$userUnit = App\Models\UserUnit::create(['user_id' => $user->id, 'unit_id' => $unit->id]);
$user->refresh();
echo "After creation: " . $user->point;

# Test observer deletion
$userUnit->delete();
$user->refresh();
echo "After deletion: " . $user->point;
```

## Monitoring

Untuk memonitor sistem poin:

```bash
# Lihat user dengan poin
php artisan tinker
App\Models\User::where('point', '>', 0)->get(['id', 'name', 'point']);

# Lihat unit dengan poin
App\Models\Unit::where('point', '>', 0)->get(['id', 'name', 'point']);
```

## Troubleshooting

### Jika poin tidak bertambah:
1. Pastikan unit memiliki poin > 0
2. Cek apakah observer terdaftar di AppServiceProvider
3. Pastikan User model memiliki `point` di `$fillable`

### Jika poin tidak berkurang:
1. Pastikan UserUnit benar-benar dihapus (bukan soft delete)
2. Cek apakah ada error di log Laravel
3. Pastikan relasi UserUnit dengan Unit dan User berfungsi

## Future Enhancements

- Logging untuk tracking perubahan poin
- Notification ketika poin bertambah/berkurang
- Dashboard untuk monitoring poin user
- Export/import data poin 