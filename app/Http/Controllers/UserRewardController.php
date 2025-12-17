<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRewardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
        ]);

        try {
            return \DB::transaction(function () use ($request) {
                $user = Auth::user();
                
                // Lock reward for update to prevent race conditions on stock
                $reward = Reward::where('id', $request->reward_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                // Validate stock availability
                if ($reward->stock < 1) {
                    return back()->with('error', 'Stok hadiah habis.');
                }

                // Validate user has enough points
                if ($user->point < $reward->price) {
                    return back()->with('error', 'Poin kamu tidak cukup.');
                }

                // Create user reward
                $userReward = UserReward::create([
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'status' => 0, // 0 = pending
                ]);

                // Decrement stock and user points atomically
                $reward->decrement('stock');
                $user->decrement('point', $reward->price);

                // Log the order creation for audit trail
                \Log::info('Reward order created', [
                    'order_id' => $userReward->id,
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'points_spent' => $reward->price,
                    'remaining_stock' => $reward->stock,
                    'timestamp' => now()
                ]);

                return back()->with('success', 'Berhasil menukarkan hadiah!');
            });
        } catch (\Exception $e) {
            \Log::error('Failed to create reward order', [
                'user_id' => Auth::id(),
                'reward_id' => $request->reward_id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menukarkan hadiah. Silakan coba lagi.');
        }
    }

    public function index()
    {
        $orders = auth()->user()->userRewards()->with('reward')->orderBy('created_at', 'desc')->paginate(5);
        return view('user_rewards.index', compact('orders'));
    }

    public function cancel($orderId)
    {
        try {
            // Use database transaction to prevent race conditions and ensure atomicity
            return \DB::transaction(function () use ($orderId) {
                // Lock the order row for update to prevent concurrent cancellations
                $order = \App\Models\UserReward::where('id', $orderId)
                    ->where('user_id', auth()->id())
                    ->where('status', 0) // 0 = pending only
                    ->lockForUpdate() // Critical: prevents race conditions
                    ->firstOrFail();

                $user = auth()->user();
                $reward = $order->reward;

                // Double-check status hasn't changed (extra safety)
                if ($order->status !== 0) {
                    return redirect()->route('user-rewards.index')
                        ->with('error', 'Pesanan ini tidak dapat dibatalkan.');
                }

                // Refund points and increment stock atomically
                $user->increment('point', $reward->price);
                $reward->increment('stock', 1);

                // Mark as cancelled
                $order->status = 2; // 2 = cancelled
                $order->save();

                // Log the cancellation for audit trail
                \Log::info('Reward order cancelled', [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'reward_id' => $reward->id,
                    'refunded_points' => $reward->price,
                    'timestamp' => now()
                ]);

                return redirect()->route('user-rewards.index')
                    ->with('success', 'Penukaran hadiah berhasil dibatalkan dan poin dikembalikan.');
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user-rewards.index')
                ->with('error', 'Pesanan tidak ditemukan atau tidak dapat dibatalkan.');
        } catch (\Exception $e) {
            \Log::error('Failed to cancel reward order', [
                'order_id' => $orderId,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('user-rewards.index')
                ->with('error', 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
        }
    }
} 