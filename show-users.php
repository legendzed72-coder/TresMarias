<?php

require __DIR__ . '/bootstrap/app.php';

$app = app();

echo "=== ADMIN ACCOUNTS ===\n";
\App\Models\User::where('role', 'admin')->get()->each(function($user) {
    echo "- {$user->name} ({$user->email})\n";
});

echo "\n=== STAFF ACCOUNTS ===\n";
\App\Models\User::where('role', 'staff')->get()->each(function($user) {
    echo "- {$user->name} ({$user->email})\n";
});

echo "\n=== CUSTOMER ACCOUNTS ===\n";
\App\Models\User::where('role', 'customer')->limit(5)->get()->each(function($user) {
    echo "- {$user->name} ({$user->email})\n";
});

echo "\n=== SUMMARY ===\n";
echo "Total Users: " . \App\Models\User::count() . "\n";
echo "Admin Users: " . \App\Models\User::where('role', 'admin')->count() . "\n";
echo "Staff Users: " . \App\Models\User::where('role', 'staff')->count() . "\n";
echo "Customer Users: " . \App\Models\User::where('role', 'customer')->count() . "\n";
