# Admin Guide: Reward Order Cancellation System

## Quick Overview

Students can now cancel **pending** reward orders and receive full point refunds. This guide explains how to monitor and manage this feature.

## Order Status Flow

```
┌─────────────────────────────────────────────────────┐
│                                                     │
│  Status 0: Terdaftar (Pending)                     │
│  • Order just created                              │
│  • Student CAN cancel ✅                           │
│  • Awaiting admin approval                         │
│                                                     │
└──────────────────┬──────────────────────────────────┘
                   │
                   │ Admin approves order
                   ↓
┌─────────────────────────────────────────────────────┐
│                                                     │
│  Status 1: Sedang dikemas (Processing)             │
│  • Order approved by admin                         │
│  • Student CANNOT cancel ❌                        │
│  • Being prepared for delivery                     │
│                                                     │
└─────────────────────────────────────────────────────┘

OR (if student cancels while pending)

┌─────────────────────────────────────────────────────┐
│                                                     │
│  Status 2: Dibatalkan (Cancelled)                  │
│  • Student cancelled the order                     │
│  • Points refunded automatically                   │
│  • Stock restored automatically                    │
│  • Cannot be cancelled again                       │
│                                                     │
└─────────────────────────────────────────────────────┘
```

## Key Points for Admins

### 1. Approve Orders Quickly
- **Why**: Pending orders can be cancelled by students
- **Action**: Review and approve orders promptly in Filament admin panel
- **Result**: Once approved (status 1), students cannot cancel

### 2. Monitor Cancellation Patterns
Check logs for suspicious activity:
```bash
# View recent cancellations
tail -f storage/logs/laravel.log | grep "Reward order cancelled"

# Count cancellations by user
grep "Reward order cancelled" storage/logs/laravel.log | grep "user_id" | sort | uniq -c
```

### 3. Red Flags to Watch For

⚠️ **High Cancellation Rate**
- User cancels > 5 orders per day
- **Action**: Contact user to understand why

⚠️ **Rapid Cancel/Reorder Cycles**
- User orders → cancels → reorders same reward repeatedly
- **Action**: May indicate attempt to manipulate stock or timing

⚠️ **Failed Cancellation Attempts**
- Multiple failed attempts to cancel same order
- **Action**: Check logs for error details

⚠️ **Cancellations Near Rate Limit**
- User hitting 5 cancellations per minute limit
- **Action**: Investigate for automated abuse

## Monitoring Dashboard

### Daily Checklist
1. ✅ Check pending orders count
2. ✅ Review cancellation logs
3. ✅ Approve legitimate orders
4. ✅ Investigate suspicious patterns

### Weekly Review
1. ✅ Top 10 users by cancellation count
2. ✅ Most cancelled rewards (may indicate issues)
3. ✅ Average time between order and cancellation
4. ✅ Failed cancellation attempt patterns

## Viewing Logs

### Successful Cancellation Log Entry
```json
[INFO] Reward order cancelled: {
    "order_id": 123,
    "user_id": 45,
    "reward_id": 7,
    "refunded_points": 50,
    "timestamp": "2025-11-06 10:30:00"
}
```

### Failed Cancellation Log Entry
```json
[ERROR] Failed to cancel reward order: {
    "order_id": 123,
    "user_id": 45,
    "error": "Order not found or cannot be cancelled"
}
```

### Order Creation Log Entry
```json
[INFO] Reward order created: {
    "order_id": 123,
    "user_id": 45,
    "reward_id": 7,
    "points_spent": 50,
    "remaining_stock": 9,
    "timestamp": "2025-11-06 10:25:00"
}
```

## Common Scenarios

### Scenario 1: Student Ordered Wrong Reward
**Student Action**: Cancel order while pending
**Admin Action**: None required - system handles automatically
**Result**: Points refunded, stock restored

### Scenario 2: Student Changed Mind
**Student Action**: Cancel order while pending
**Admin Action**: None required
**Result**: Points refunded, stock restored

### Scenario 3: Student Wants to Cancel After Approval
**Student Action**: Tries to cancel (will fail)
**Admin Action**: 
- If legitimate reason: Manually refund points and restore stock
- Update order status in admin panel
**Result**: Manual intervention required

### Scenario 4: Suspected Abuse
**Observation**: User cancels 10+ orders per day
**Admin Action**:
1. Review user's order history
2. Contact user for explanation
3. If abuse confirmed:
   - Warn user
   - Consider temporary suspension
   - Monitor future activity

## Database Queries for Monitoring

### Find Users with Most Cancellations
```sql
SELECT 
    u.id,
    u.name,
    u.email,
    COUNT(*) as cancellation_count
FROM user_rewards ur
JOIN users u ON ur.user_id = u.id
WHERE ur.status = 2
GROUP BY u.id, u.name, u.email
ORDER BY cancellation_count DESC
LIMIT 10;
```

### Find Most Cancelled Rewards
```sql
SELECT 
    r.id,
    r.name,
    COUNT(*) as cancellation_count
FROM user_rewards ur
JOIN rewards r ON ur.reward_id = r.id
WHERE ur.status = 2
GROUP BY r.id, r.name
ORDER BY cancellation_count DESC
LIMIT 10;
```

### Find Recent Cancellations
```sql
SELECT 
    ur.id,
    u.name as student_name,
    r.name as reward_name,
    r.price as refunded_points,
    ur.created_at as order_date,
    ur.updated_at as cancel_date
FROM user_rewards ur
JOIN users u ON ur.user_id = u.id
JOIN rewards r ON ur.reward_id = r.id
WHERE ur.status = 2
ORDER BY ur.updated_at DESC
LIMIT 20;
```

### Find Pending Orders (Need Approval)
```sql
SELECT 
    ur.id,
    u.name as student_name,
    r.name as reward_name,
    r.price as points,
    ur.created_at as order_date,
    TIMESTAMPDIFF(HOUR, ur.created_at, NOW()) as hours_pending
FROM user_rewards ur
JOIN users u ON ur.user_id = u.id
JOIN rewards r ON ur.reward_id = r.id
WHERE ur.status = 0
ORDER BY ur.created_at ASC;
```

## Filament Admin Panel Actions

### Approve an Order
1. Go to **User Rewards** in admin panel
2. Find the pending order (status = 0)
3. Edit the order
4. Change status to **1** (Claimed/Processing)
5. Save

**Effect**: Student can no longer cancel this order

### Manually Cancel an Order (if needed)
1. Go to **User Rewards** in admin panel
2. Find the order
3. Note the reward price (points to refund)
4. Change order status to **2** (Cancelled)
5. Go to **Users** panel
6. Find the student
7. Add the points back manually
8. Go to **Rewards** panel
9. Increment the stock by 1

**Note**: Automatic cancellation (by student) handles all this automatically

## Rate Limiting

The system has built-in rate limits to prevent abuse:

- **Order Creation**: Max 10 orders per minute per user
- **Order Cancellation**: Max 5 cancellations per minute per user

If a user hits these limits, they'll see:
- HTTP 429 (Too Many Requests)
- Must wait 1 minute before trying again

**Admin Action**: If user frequently hits rate limits, investigate for:
- Automated scripts
- Abuse attempts
- Legitimate high activity (may need limit adjustment)

## Security Features

The system includes multiple protections:

1. ✅ **Transaction Safety**: All operations are atomic
2. ✅ **Race Condition Prevention**: Database row locking
3. ✅ **Ownership Verification**: Users can only cancel own orders
4. ✅ **Status Checks**: Only pending orders can be cancelled
5. ✅ **Rate Limiting**: Prevents spam attacks
6. ✅ **Audit Logging**: Full trail of all actions

## Troubleshooting

### Issue: Student Says Points Not Refunded
**Check**:
1. View user's current points in admin panel
2. Check order status (should be 2 if cancelled)
3. Check logs for cancellation entry
4. Verify refund amount in log matches reward price

**If Points Missing**:
1. Check if cancellation actually succeeded (status = 2)
2. Review error logs for failed transactions
3. Manually verify point calculation
4. If needed, manually add points and document

### Issue: Stock Not Restored
**Check**:
1. View reward's current stock in admin panel
2. Check order status (should be 2 if cancelled)
3. Check logs for cancellation entry
4. Verify stock increment in log

**If Stock Missing**:
1. Check if cancellation actually succeeded
2. Review error logs for failed transactions
3. Manually increment stock if needed

### Issue: User Cannot Cancel Order
**Possible Reasons**:
1. Order already approved (status = 1) ✅ Expected behavior
2. Order already cancelled (status = 2) ✅ Expected behavior
3. User hit rate limit (5/minute) ✅ Expected behavior
4. Order belongs to different user ✅ Expected behavior
5. Technical error ⚠️ Check logs

**Action**: Review logs and order status to determine cause

## Best Practices

### DO ✅
- Approve orders within 24 hours
- Monitor cancellation logs weekly
- Investigate unusual patterns
- Keep logs for at least 30 days
- Document any manual interventions

### DON'T ❌
- Manually edit points without checking logs
- Ignore high cancellation rates
- Approve orders without verification
- Delete cancellation logs
- Override rate limits without investigation

## Contact Developer

If you encounter:
- Consistent point calculation errors
- Database transaction failures
- Unexpected system behavior
- Need to adjust rate limits

Contact the development team with:
- Error logs
- Affected user IDs
- Order IDs
- Timestamp of issue
- Steps to reproduce

## Summary

The cancellation system is designed to:
1. ✅ Give students flexibility to cancel pending orders
2. ✅ Automatically handle refunds and stock restoration
3. ✅ Prevent abuse through multiple security layers
4. ✅ Provide full audit trail for monitoring
5. ✅ Minimize admin intervention needed

**Your main job**: Approve orders promptly and monitor for unusual patterns.

