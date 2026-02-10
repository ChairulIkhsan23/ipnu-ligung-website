<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating users...');

        // ============================================
        // 1. BUAT USER ADMIN
        // ============================================
        $this->command->info('Creating Admin user...');
        $admin = User::firstOrCreate(
            // EMAIL HARUS SAMA DENGAN EMAIL GOOGLE ADMIN
            ['email' => 'chairulikhsan2121@gmail.com'],
            [
                'name' => 'Administrator IPNU',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->syncRoles($adminRole);
            $this->command->info('Admin user created: chairulikhsan2121@gmail.com');
        } else {
            $this->command->error('Admin role not found! Run RolePermissionSeeder first.');
        }

        // ============================================
        // 2. BUAT USER EDITOR
        // ============================================
        $this->command->info('Creating Editor user...');
        $editor = User::firstOrCreate(
            // EMAIL HARUS SAMA DENGAN EMAIL GOOGLE EDITOR
            ['email' => 'editor1@gmail.com'],
            [
                'name' => 'Editor IPNU',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign role editor
        $editorRole = Role::where('name', 'editor')->first();
        if ($editorRole) {
            $editor->syncRoles($editorRole);
            $this->command->info('Editor user created: editor1@gmail.com');
        } else {
            $this->command->error('Editor role not found! Run RolePermissionSeeder first.');
        }

        $this->command->info('User creation completed!');
    }
}
