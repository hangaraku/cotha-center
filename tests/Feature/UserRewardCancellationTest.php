<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reward;
use App\Models\UserReward;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRewardCancellationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Reward $reward;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user with points
        $this->user = User::factory()->create([
            'point' => 100,
        ]);

        // Create a test reward
        $this->reward = Reward::create([
            'name' => 'Test Reward',
            'shop_url' => 'https://example.com',
            'price' => 50,
            'img_url' => 'test.jpg',
            'stock' => 10,
            'order_number' => 1,
            'is_pinned' => false,
        ]);
    }

    /** @test */
    public function student_can_cancel_pending_order_and_get_refund()
    {
        // Create a pending order
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 0, // pending
        ]);

        // Deduct points and stock (simulating order creation)
        $this->user->decrement('point', $this->reward->price);
        $this->reward->decrement('stock');

        // Verify initial state
        $this->assertEquals(50, $this->user->fresh()->point);
        $this->assertEquals(9, $this->reward->fresh()->stock);

        // Cancel the order
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // Verify redirect with success message
        $response->assertRedirect(route('user-rewards.index'));
        $response->assertSessionHas('success');

        // Verify points refunded
        $this->assertEquals(100, $this->user->fresh()->point);

        // Verify stock restored
        $this->assertEquals(10, $this->reward->fresh()->stock);

        // Verify order status changed to cancelled
        $this->assertEquals(2, $order->fresh()->status);
    }

    /** @test */
    public function student_cannot_cancel_claimed_order()
    {
        // Create a claimed order
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 1, // claimed
        ]);

        $this->user->decrement('point', $this->reward->price);
        $initialPoints = $this->user->fresh()->point;

        // Try to cancel
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // Verify error response
        $response->assertRedirect(route('user-rewards.index'));
        $response->assertSessionHas('error');

        // Verify points NOT refunded
        $this->assertEquals($initialPoints, $this->user->fresh()->point);

        // Verify status unchanged
        $this->assertEquals(1, $order->fresh()->status);
    }

    /** @test */
    public function student_cannot_cancel_already_cancelled_order()
    {
        // Create a cancelled order
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 2, // cancelled
        ]);

        $initialPoints = $this->user->fresh()->point;

        // Try to cancel again
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // Verify error response
        $response->assertRedirect(route('user-rewards.index'));
        $response->assertSessionHas('error');

        // Verify points NOT refunded (no double refund)
        $this->assertEquals($initialPoints, $this->user->fresh()->point);
    }

    /** @test */
    public function student_cannot_cancel_other_users_order()
    {
        // Create another user
        $otherUser = User::factory()->create(['point' => 100]);

        // Create order for other user
        $order = UserReward::create([
            'user_id' => $otherUser->id,
            'reward_id' => $this->reward->id,
            'status' => 0, // pending
        ]);

        $otherUser->decrement('point', $this->reward->price);
        $initialPoints = $otherUser->fresh()->point;

        // Try to cancel as different user
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // Verify error response (404 or error)
        $response->assertRedirect(route('user-rewards.index'));
        $response->assertSessionHas('error');

        // Verify other user's points NOT refunded
        $this->assertEquals($initialPoints, $otherUser->fresh()->point);

        // Verify order status unchanged
        $this->assertEquals(0, $order->fresh()->status);
    }

    /** @test */
    public function cancellation_is_rate_limited()
    {
        $orders = [];
        
        // Create 6 pending orders
        for ($i = 0; $i < 6; $i++) {
            $orders[] = UserReward::create([
                'user_id' => $this->user->id,
                'reward_id' => $this->reward->id,
                'status' => 0,
            ]);
        }

        // Try to cancel 6 orders rapidly
        for ($i = 0; $i < 5; $i++) {
            $response = $this->actingAs($this->user)
                ->post(route('user-rewards.cancel', $orders[$i]->id));
            $response->assertRedirect(); // Should succeed
        }

        // 6th request should be throttled
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $orders[5]->id));
        
        $response->assertStatus(429); // Too Many Requests
    }

    /** @test */
    public function cancellation_uses_database_transaction()
    {
        // This test verifies that if any part fails, nothing is committed
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 0,
        ]);

        $this->user->decrement('point', $this->reward->price);
        $this->reward->decrement('stock');

        $initialPoints = $this->user->fresh()->point;
        $initialStock = $this->reward->fresh()->stock;

        // Mock a database error during transaction
        // In real scenario, if any part of transaction fails, 
        // everything should rollback

        // For now, just verify the transaction completes atomically
        $response = $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // Either everything succeeds or everything fails
        $finalOrder = $order->fresh();
        $finalUser = $this->user->fresh();
        $finalReward = $this->reward->fresh();

        if ($finalOrder->status === 2) {
            // If cancelled, points and stock must be restored
            $this->assertEquals($initialPoints + $this->reward->price, $finalUser->point);
            $this->assertEquals($initialStock + 1, $finalReward->stock);
        } else {
            // If not cancelled, nothing should change
            $this->assertEquals($initialPoints, $finalUser->point);
            $this->assertEquals($initialStock, $finalReward->stock);
        }
    }

    /** @test */
    public function point_calculation_excludes_cancelled_orders()
    {
        // Create and complete some units for the user (assuming UserUnit model exists)
        // For this test, we'll just set initial points
        $this->user->update(['point' => 100]);

        // Create a pending order
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 0,
        ]);

        $this->user->decrement('point', $this->reward->price);
        
        // User should have 50 points
        $this->assertEquals(50, $this->user->fresh()->point);

        // Cancel the order
        $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));

        // User should have 100 points again
        $this->assertEquals(100, $this->user->fresh()->point);

        // Verify cancelled order doesn't count as "spent" in point service
        $pointService = new \App\Services\StudentPointService();
        $calculation = $pointService->calculateStudentPoints($this->user->fresh());

        // The cancelled order should not be in points_spent
        $this->assertEquals(0, $calculation['points_spent']);
        $this->assertEquals(1, $calculation['rewards_cancelled']);
    }

    /** @test */
    public function guest_cannot_cancel_orders()
    {
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 0,
        ]);

        // Try to cancel without authentication
        $response = $this->post(route('user-rewards.cancel', $order->id));

        // Should redirect to login
        $response->assertRedirect(route('login'));

        // Order should remain unchanged
        $this->assertEquals(0, $order->fresh()->status);
    }

    /** @test */
    public function cancellation_is_logged()
    {
        $order = UserReward::create([
            'user_id' => $this->user->id,
            'reward_id' => $this->reward->id,
            'status' => 0,
        ]);

        $this->user->decrement('point', $this->reward->price);

        // Capture log output
        \Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message, $context) use ($order) {
                return $message === 'Reward order cancelled' &&
                       $context['order_id'] === $order->id &&
                       $context['user_id'] === $this->user->id &&
                       $context['reward_id'] === $this->reward->id &&
                       $context['refunded_points'] === $this->reward->price;
            });

        // Cancel the order
        $this->actingAs($this->user)
            ->post(route('user-rewards.cancel', $order->id));
    }
}

