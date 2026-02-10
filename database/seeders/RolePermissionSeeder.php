<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // 1. BUAT PERMISSIONS
        // ============================================
        $permissions = [
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Blog Management
            'view_posts',
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            
            // Program Management
            'view_programs',
            'create_programs',
            'edit_programs',
            'delete_programs',
            
            // About Page
            'view_about',
            'edit_about',
            
            // Homepage
            'view_homepage',
            'edit_homepage',

            // Category Management
            'view_categories',
            'create_categories',
            'edit_categories',
            'delete_categories',

            // Tag Management
            'view_tags',
            'create_tags',
            'edit_tags',
            'delete_tags',
        ];

        $this->command->info('Creating permissions...');
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
        $this->command->info('Permissions created: ' . count($permissions));

        // ============================================
        // 2. BUAT ROLES
        // ============================================
        
        // Role: ADMIN - Semua permission
        $this->command->info('Creating Admin role...');
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions(Permission::all());
        $this->command->info('Admin role created with all permissions');

        // Role: EDITOR - Hanya konten
        $this->command->info('Creating Editor role...');
        $editorRole = Role::firstOrCreate([
            'name' => 'editor',
            'guard_name' => 'web',
        ]);
        $editorPermissions = [
            'view_posts',
            'create_posts',
            'edit_posts',
            'view_programs',
            'create_programs',
            'edit_programs',
            'view_about',
            'view_homepage',
            'view_categories',
            'view_tags',
        ];
        $editorRole->syncPermissions($editorPermissions);
        $this->command->info('Editor role created with ' . count($editorPermissions) . ' permissions');

        $this->command->info('Role & Permission setup completed!');
    }
}