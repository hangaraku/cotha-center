# Reward Order Cancellation Feature

## Overview
Students can now cancel their pending reward orders and receive a full refund of their points. This feature includes multiple layers of protection to prevent abuse.

## How It Works

### For Students
1. Navigate to **Riwayat Penukaran** (Order History) page
2. Find orders with status **"Terdaftar"** (Pending)
3. Click the **"Batalkan"** button
4. Confirm the cancellation in the dialog
5. Points are immediately refunded to your account
6. Reward stock is restored

### Status Flow
- **Status 0 (Terdaftar/Pending)**: Order is registered, waiting for admin approval. **CAN BE CANCELLED**
- **Status 1 (Sedang dikemas/Processing)**: Order approved by admin, being processed. **CANNOT BE CANCELLED**
- **Status 2 (Dibatalkan/Cancelled)**: Order was cancelled by student, points refunded

## Anti-Abuse Mechanisms

### 1. Database Transaction with Row Locking
```php
DB::transaction(function () {
    $order = UserReward::where('id', $orderId)
        ->where('user_id', auth()->id())
        ->where('status', 0)
        ->lockForUpdate() // Prevents race conditions
        ->firstOrFail();
    // ... refund logic
});
```
**Protection**: Prevents multiple simultaneous cancellation requests from refunding points multiple times.

### 2. Status Verification
- Only orders with `status = 0` (pending) can be cancelled
- Double-check status before processing refund
- Once cancelled (`status = 2`), cannot be cancelled again

### 3. User Ownership Verification
```php
->where('user_id', auth()->id())
```
**Protection**: Students can only cancel their own orders.

### 4. Rate Limiting
```php
Route::post('/user-rewards/{order}/cancel', ...)
    ->middleware('throttle:5,1'); // Max 5 cancellations per minute
```
**Protection**: Prevents spam cancellation attempts.

### 5. Atomic Operations
```php
$user->increment('point', $reward->price);
$reward->increment('stock', 1);
```
**Protection**: Uses database-level atomic operations to ensure data consistency.

### 6. Audit Logging
Every cancellation is logged with:
- Order ID
- User ID
- Reward ID
- Refunded points
- Timestamp

**Protection**: Creates audit trail for monitoring suspicious activity.

### 7. Confirmation Dialog
JavaScript confirmation dialog prevents accidental cancellations.

## Point Calculation System

The `StudentPointService` correctly handles cancelled orders:

```php
// Points spent = pending (0) + claimed (1) orders only
$pointsSpent = UserReward::whereIn('status', [0, 1])->sum('reward.price');

// Cancelled orders (status = 2) are NOT counted as spent
// This ensures cancelled order points are effectively "refunded"
$netPoints = $pointsEarned - $pointsSpent;
```

### Example Scenario
1. Student earns 100 points
2. Student orders Reward A (50 points) → Status: Pending
3. Student's available points: 50
4. Student cancels order → Status: Cancelled
5. Student's available points: 100 (refunded)
6. Student cannot cancel again (status is no longer pending)

## Security Considerations

### What's Protected ✅
- ✅ Race conditions (multiple simultaneous cancellations)
- ✅ Unauthorized cancellations (other users' orders)
- ✅ Double refunds (status checks + transactions)
- ✅ Spam attacks (rate limiting)
- ✅ Data inconsistency (atomic operations)
- ✅ Audit trail (logging)

### Admin Responsibilities
- Monitor cancellation logs for suspicious patterns
- Approve pending orders promptly (moves to status 1, preventing cancellation)
- Review users with excessive cancellation rates

## Database Schema

### user_rewards table
```sql
CREATE TABLE user_rewards (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    reward_id BIGINT UNSIGNED NOT NULL,
    status TINYINT NOT NULL DEFAULT 0 COMMENT '0=pending, 1=claimed, 2=cancelled',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (reward_id) REFERENCES rewards(id)
);
```

## API Endpoints

### Cancel Order
**POST** `/user-rewards/{order}/cancel`

**Middleware**: `auth`, `throttle:5,1`

**Request**: None (order ID in URL)

**Response**:
- Success: Redirect with success message
- Error: Redirect with error message

**Possible Errors**:
- Order not found
- Order not owned by user
- Order status is not pending
- Database transaction failed

## Testing Recommendations

### Manual Testing
1. **Normal Flow**:
   - Create order → Cancel → Verify points refunded
   
2. **Race Condition**:
   - Open two browser tabs
   - Try cancelling same order simultaneously
   - Verify only one succeeds

3. **Status Check**:
   - Admin approves order (status → 1)
   - Try to cancel → Should fail

4. **Rate Limiting**:
   - Try cancelling 6 orders within 1 minute
   - 6th attempt should be throttled

### Automated Testing
See `tests/Feature/UserRewardCancellationTest.php` (to be created)

## Monitoring

### Key Metrics to Track
1. Cancellation rate per user
2. Time between order and cancellation
3. Users with multiple rapid cancel/reorder cycles
4. Failed cancellation attempts

### Log Locations
- Success logs: `storage/logs/laravel.log` (INFO level)
- Error logs: `storage/logs/laravel.log` (ERROR level)

### Example Log Entries
```
[INFO] Reward order cancelled: {"order_id":123,"user_id":45,"reward_id":7,"refunded_points":50,"timestamp":"2025-11-06 10:30:00"}
[ERROR] Failed to cancel reward order: {"order_id":123,"user_id":45,"error":"Order not found"}
```

## Future Enhancements

### Potential Improvements
1. **Cancellation Window**: Only allow cancellations within X hours of order
2. **Cancellation Limit**: Limit cancellations per user per day/week
3. **Penalty System**: Reduce refund percentage for frequent cancellers
4. **Admin Notification**: Alert admins of suspicious cancellation patterns
5. **Cooldown Period**: Prevent re-ordering same reward immediately after cancellation

## Code Locations

- Controller: `app/Http/Controllers/UserRewardController.php`
- View: `resources/views/user_rewards/index.blade.php`
- Routes: `routes/web.php`
- Point Service: `app/Services/StudentPointService.php`
- Model: `app/Models/UserReward.php`

## Related Documentation
- [Point System](README_POINT_SYSTEM.md)
- [Account Feature](README_ACCOUNT_FEATURE.md)

