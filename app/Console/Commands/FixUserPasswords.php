<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash any plain text passwords in the users table using Bcrypt';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for users with plain text passwords...');

        $updated = 0;
        $users = User::all();

        foreach ($users as $user) {
            // Skip if password is already hashed with Bcrypt (starts with $2y$ or $2a$ or $2b$)
            if ($this->isBcryptHash($user->password)) {
                continue;
            }

            // If we reach here, the password is not properly hashed
            $this->warn("Found plain text password for user: {$user->email}");
            
            // Create a new secure password (random 16 chars)
            $newPassword = $this->generateSecurePassword();
            $user->password = Hash::make($newPassword);
            $user->save();
            
            $this->line("✓ Updated password for: {$user->email}");
            $this->line("  New password: {$newPassword}");
            $this->comment("  Save this password securely!");
            
            $updated++;
        }

        $this->info("\nPassword update complete!");
        $this->info("Total users updated: {$updated}");

        return Command::SUCCESS;
    }

    /**
     * Check if a password hash is a valid Bcrypt hash.
     *
     * @param  string  $hash
     * @return bool
     */
    private function isBcryptHash(string $hash): bool
    {
        return preg_match('/^\$2[aby]\$/', $hash) === 1;
    }

    /**
     * Generate a secure random password.
     *
     * @return string
     */
    private function generateSecurePassword(): string
    {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%'), 0, 16);
    }
}
