# Account Management Feature Documentation

## Overview
Fitur manajemen akun platform pembelajaran yang memungkinkan Super Admin, Teacher, dan Supervisor untuk mengelola akun platform student.

## ⚠️ Important Note: Linter Errors
**Semua linter error terkait `hasRole()` method adalah FALSE POSITIVE.**

Method `hasRole()` tersedia dari Spatie Permission trait yang sudah di-import di User model:
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    // ...
}
```

## Implemented Features

### 1. AccountRelationManager
**File**: `app/Filament/Resources/StudentResource/RelationManagers/AccountsRelationManager.php`

**Fitur**:
- Menambahkan akun platform untuk student tertentu
- Form dengan validasi yang proper
- Table dengan informasi lengkap
- Actions: Create, Edit, Delete
- Filter berdasarkan platform

### 2. AccountResource
**File**: `app/Filament/Resources/AccountResource.php`

**Fitur**:
- Resource terpisah untuk mengelola semua akun
- Center-based access control
- Form dengan dropdown student selection
- Advanced filtering dan search
- Bulk actions

### 3. AccountPolicy
**File**: `app/Policies/AccountPolicy.php`

**Role Access**:
- ✅ Super Admin: Full access to all centers
- ✅ Teacher: Access to their center only
- ✅ Supervisor: Access to their center only
- ❌ Student: No access

**Methods**:
- `viewAny()`: Check if user can view accounts list
- `view()`: Check if user can view specific account
- `create()`: Check if user can create accounts
- `update()`: Check if user can update accounts
- `delete()`: Check if user can delete accounts
- `restore()`: Check if user can restore accounts
- `forceDelete()`: Check if user can permanently delete accounts

### 4. Authentication Gates
**File**: `app/Providers/AuthServiceProvider.php`

**Defined Gates**:
- `manage-accounts`: General account management permission
- `view-accounts`: View accounts permission
- `create-accounts`: Create accounts permission
- `update-accounts`: Update accounts permission
- `delete-accounts`: Delete accounts permission

### 5. Middleware Update
**File**: `app/Http/Middleware/AuthenticateAdmin.php`

**Updated to include**:
- Super Admin
- Teacher
- Supervisor

## Database Structure

### Accounts Table
```sql
CREATE TABLE accounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    platform_name VARCHAR(255) NOT NULL,
    account_details TEXT NOT NULL,
    platform_link VARCHAR(500) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Relationships
- **User** (1) → **Account** (Many)
- **Center** (1) → **User** (Many) → **Account** (Many)

## Usage Examples

### For Super Admin
```php
// Can access all accounts from all centers
$accounts = Account::all();
```

### For Teacher/Supervisor
```php
// Can only access accounts from their center
$accounts = Account::whereHas('user', function($query) {
    $query->where('center_id', Auth::user()->center_id);
})->get();
```

### Role Checking
```php
// Note: Linter shows error but method exists
if ($user->hasRole('super_admin')) {
    // Super admin logic
}

if ($user->hasRole('Teacher')) {
    // Teacher logic
}

if ($user->hasRole('Supervisor')) {
    // Supervisor logic
}
```

## Security Features

1. **Role-based Access Control**: Hanya admin yang bisa akses
2. **Center-based Restrictions**: Teacher/Supervisor hanya bisa akses center mereka
3. **Policy Protection**: Semua CRUD operations dilindungi policy
4. **Middleware Protection**: Admin panel dilindungi middleware
5. **Form Validation**: Validasi input yang proper
6. **SQL Injection Protection**: Menggunakan Eloquent ORM

## Frontend Integration

### Student Access
- Students bisa akses fitur "Akun Saya" di frontend
- Hanya bisa lihat dan edit akun mereka sendiri
- Tidak bisa akses admin panel

### Admin Access
- Super Admin: Akses penuh ke semua center
- Teacher: Akses terbatas ke center mereka
- Supervisor: Akses terbatas ke center mereka

## Testing

### Manual Testing Checklist
- [ ] Super Admin bisa akses AccountResource
- [ ] Teacher bisa akses AccountResource (center mereka)
- [ ] Supervisor bisa akses AccountResource (center mereka)
- [ ] Student tidak bisa akses admin panel
- [ ] Center-based filtering berfungsi
- [ ] CRUD operations berfungsi dengan policy
- [ ] Form validation berfungsi
- [ ] Bulk actions berfungsi

## Troubleshooting

### Common Issues
1. **Linter Error**: `hasRole()` method not found
   - **Solution**: Ignore linter error, method exists from Spatie Permission
   
2. **Access Denied**: User cannot access accounts
   - **Check**: User role and center assignment
   
3. **Empty List**: No accounts shown
   - **Check**: Center-based filtering and user permissions

## Future Enhancements

1. **Audit Trail**: Log semua perubahan akun
2. **Bulk Import**: Import akun dari CSV/Excel
3. **Export Feature**: Export akun ke berbagai format
4. **Advanced Search**: Search berdasarkan multiple criteria
5. **Account Templates**: Template untuk platform umum
6. **Integration**: Integrasi dengan platform eksternal 