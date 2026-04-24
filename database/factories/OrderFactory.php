<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 1000);
        $tax = $subtotal * 0.12;
        $deliveryFee = fake()->randomElement([0, 50, 100, 150]);
        $discount = fake()->randomFloat(2, 0, $subtotal * 0.2);

        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'order_number' => 'ORD-' . strtoupper(fake()->unique()->bothify('???-####')),
            'type' => fake()->randomElement(['pos', 'preorder', 'order']),
            'status' => fake()->randomElement(['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery', 'completed', 'cancelled']),
            'payment_status' => fake()->randomElement(['pending', 'paid', 'refunded']),
            'payment_method' => fake()->randomElement(['cod', 'online', 'cash', 'card', 'gcash', 'maya']),
            'fulfillment_type' => fake()->randomElement(['pickup', 'delivery']),
            'scheduled_at' => fake()->dateTimeBetween('-30 days', '+30 days'),
            'special_instructions' => fake()->optional()->sentence(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $deliveryFee,
            'discount' => $discount,
            'total' => $subtotal + $tax + $deliveryFee - $discount,
            'cashier_id' => User::where('role', 'staff')->inRandomOrder()->first()?->id,
            'is_offline_synced' => false,
        ];
    }

    /**
     * State for cancelled orders.
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'payment_status' => fake()->randomElement(['pending', 'refunded']),
            ];
        });
    }

    /**
     * State for completed orders.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'payment_status' => 'paid',
            ];
        });
    }
}
