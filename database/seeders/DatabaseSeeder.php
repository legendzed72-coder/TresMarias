<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users
        User::firstOrCreate(
            ['email' => 'admin@tresmarias.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin12345'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin2@tresmarias.com'],
            [
                'name' => 'Admin Manager',
                'password' => bcrypt('admin12345'),
                'role' => 'admin',
            ]
        );

        // Create staff users
        User::firstOrCreate(
            ['email' => 'staff@tresmarias.com'],
            [
                'name' => 'Staff Member',
                'password' => bcrypt('staff12345'),
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff2@tresmarias.com'],
            [
                'name' => 'Chef Manager',
                'password' => bcrypt('staff12345'),
                'role' => 'staff',
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff3@tresmarias.com'],
            [
                'name' => 'Delivery Staff',
                'password' => bcrypt('staff12345'),
                'role' => 'staff',
            ]
        );

        // Create test customer
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'customer',
            ]
        );

        // Create sample customer users for orders
        $customers = User::factory(5)->create();

        $this->call(BreadSeeder::class);

        // Create sample cancelled orders
        Order::factory()->cancelled()->count(5)->create();
        
        // Create completed orders
        Order::factory()->completed()->count(3)->create();
    }
}
