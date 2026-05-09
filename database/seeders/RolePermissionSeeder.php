<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ================================================
        // 1. CREATE ALL PERMISSIONS
        // ================================================

        // --- Navigation / Page Access ---
        $navPermissions = [
            'view_dashboard',
            'view_data_master',
            'view_resources',
            'view_help',
            'view_renstra',
        ];

        // --- User Management ---
        $userPermissions = [
            'user.show', 'user.create', 'user.edit', 'user.delete', 'user.massdelete', 'user.ban',
        ];

        // --- Role Management ---
        $rolePermissions = [
            'role.show', 'role.create', 'role.edit', 'role.delete', 'role.massdelete',
        ];

        // --- Renstra Permissions ---
        $renstraPermissions = [
            'renstra.dataskpd.view',
            'renstra.sasaran.view',
            'renstra.sasaran.create',
            'renstra.sasaran.edit',
            'renstra.sasaran.delete',
        ];

        // Create all permissions
        $allPermissions = array_merge(
            $navPermissions, $userPermissions, $rolePermissions, $renstraPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ================================================
        // 2. CREATE ROLES
        // ================================================
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        $roleAdmin      = Role::firstOrCreate(['name' => 'admin']);
        $roleSkpd       = Role::firstOrCreate(['name' => 'skpd']);

        // ================================================
        // 3. ASSIGN PERMISSIONS TO ROLES
        // ================================================

        // SUPERADMIN
        $roleSuperadmin->syncPermissions(Permission::all());

        // ADMIN
        $adminPermissions = array_merge($navPermissions, $renstraPermissions);
        $roleAdmin->syncPermissions($adminPermissions);

        // SKPD
        $skpdPermissions = [
            'view_dashboard',
            'view_renstra',
            'renstra.dataskpd.view',
            'renstra.sasaran.view',
            'renstra.sasaran.create',
            'renstra.sasaran.edit',
        ];
        $roleSkpd->syncPermissions($skpdPermissions);
    }
}
