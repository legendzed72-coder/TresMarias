<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix any plain text or invalid passwords
        $users = User::all();
        
        foreach ($users as $user) {
            // Check if password is not already hashed (bcrypt hashes start with $2y$)
            if (!str_starts_with($user->password, '$2y$') && !str_starts_with($user->password, '$2a$') && !str_starts_with($user->password, '$2b$')) {
                // If it looks like a plain password or invalid hash, hash it properly
                $user->password = Hash::make($user->password);
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed as this is a data fix
    }
};
