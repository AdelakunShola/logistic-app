<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Shipments
            ['name' => 'shipments.view', 'display_name' => 'View Shipments', 'module' => 'shipments'],
            ['name' => 'shipments.create', 'display_name' => 'Create Shipments', 'module' => 'shipments'],
            ['name' => 'shipments.edit', 'display_name' => 'Edit Shipments', 'module' => 'shipments'],
            ['name' => 'shipments.delete', 'display_name' => 'Delete Shipments', 'module' => 'shipments'],
            ['name' => 'shipments.track', 'display_name' => 'Track Shipments', 'module' => 'shipments'],
            ['name' => 'shipments.assign_driver', 'display_name' => 'Assign Drivers', 'module' => 'shipments'],
            ['name' => 'shipments.manage_delays', 'display_name' => 'Manage Delays', 'module' => 'shipments'],
            ['name' => 'shipments.manage_returns', 'display_name' => 'Manage Returns', 'module' => 'shipments'],
            ['name' => 'shipments.manage_issues', 'display_name' => 'Manage Issues', 'module' => 'shipments'],

            // Fleet
            ['name' => 'fleet.view', 'display_name' => 'View Vehicles', 'module' => 'fleet'],
            ['name' => 'fleet.create', 'display_name' => 'Add Vehicles', 'module' => 'fleet'],
            ['name' => 'fleet.edit', 'display_name' => 'Edit Vehicles', 'module' => 'fleet'],
            ['name' => 'fleet.delete', 'display_name' => 'Delete Vehicles', 'module' => 'fleet'],
            ['name' => 'fleet.maintenance', 'display_name' => 'Manage Maintenance', 'module' => 'fleet'],
            ['name' => 'fleet.status', 'display_name' => 'View Fleet Status', 'module' => 'fleet'],

            // Drivers
            ['name' => 'drivers.view', 'display_name' => 'View Drivers', 'module' => 'drivers'],
            ['name' => 'drivers.create', 'display_name' => 'Add Drivers', 'module' => 'drivers'],
            ['name' => 'drivers.edit', 'display_name' => 'Edit Drivers', 'module' => 'drivers'],
            ['name' => 'drivers.delete', 'display_name' => 'Remove Drivers', 'module' => 'drivers'],
            ['name' => 'drivers.assign', 'display_name' => 'Assign Drivers to Vehicles', 'module' => 'drivers'],
            ['name' => 'drivers.performance', 'display_name' => 'View Performance', 'module' => 'drivers'],

            // Warehouses
            ['name' => 'warehouses.view', 'display_name' => 'View Warehouses', 'module' => 'warehouses'],
            ['name' => 'warehouses.create', 'display_name' => 'Add Warehouses', 'module' => 'warehouses'],
            ['name' => 'warehouses.edit', 'display_name' => 'Edit Warehouses', 'module' => 'warehouses'],
            ['name' => 'warehouses.delete', 'display_name' => 'Delete Warehouses', 'module' => 'warehouses'],
            ['name' => 'warehouses.inventory', 'display_name' => 'Manage Inventory', 'module' => 'warehouses'],
            ['name' => 'warehouses.transfers', 'display_name' => 'Manage Transfers', 'module' => 'warehouses'],

            // Users & Staff
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            ['name' => 'users.manage_roles', 'display_name' => 'Manage Roles', 'module' => 'users'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'module' => 'reports'],
            ['name' => 'reports.performance', 'display_name' => 'View Performance Reports', 'module' => 'reports'],

            // Support
            ['name' => 'support.view', 'display_name' => 'View Tickets', 'module' => 'support'],
            ['name' => 'support.create', 'display_name' => 'Create Tickets', 'module' => 'support'],
            ['name' => 'support.assign', 'display_name' => 'Assign Tickets', 'module' => 'support'],
            ['name' => 'support.resolve', 'display_name' => 'Resolve Tickets', 'module' => 'support'],

            // Settings
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings'],
            ['name' => 'settings.pricing', 'display_name' => 'Manage Pricing', 'module' => 'settings'],
            ['name' => 'settings.roles', 'display_name' => 'Manage Roles & Permissions', 'module' => 'settings'],

            // Activity Logs
            ['name' => 'logs.view', 'display_name' => 'View Activity Logs', 'module' => 'logs'],
            ['name' => 'logs.export', 'display_name' => 'Export Logs', 'module' => 'logs'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                ['display_name' => $perm['display_name'], 'module' => $perm['module']]
            );
        }

        // Create default roles and assign permissions
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access',
                'permissions' => '*', // all permissions
            ],
            [
                'name' => 'driver',
                'display_name' => 'Driver',
                'description' => 'Driver role with delivery-related access',
                'permissions' => [
                    'shipments.view', 'shipments.track',
                    'fleet.view',
                    'support.view', 'support.create',
                ],
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'General staff with operational access',
                'permissions' => [
                    'shipments.view', 'shipments.create', 'shipments.edit', 'shipments.track',
                    'shipments.assign_driver', 'shipments.manage_delays',
                    'fleet.view', 'fleet.status',
                    'drivers.view', 'drivers.assign', 'drivers.performance',
                    'warehouses.view', 'warehouses.inventory',
                    'reports.view', 'reports.performance',
                    'support.view', 'support.create', 'support.assign', 'support.resolve',
                    'logs.view',
                ],
            ],
            [
                'name' => 'customer',
                'display_name' => 'Customer',
                'description' => 'Customer with limited access',
                'permissions' => [
                    'shipments.view', 'shipments.track',
                    'support.view', 'support.create',
                ],
            ],
        ];

        $allPermissions = Permission::all();

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                ]
            );

            if ($roleData['permissions'] === '*') {
                $role->permissions()->sync($allPermissions->pluck('id'));
            } else {
                $permIds = $allPermissions->whereIn('name', $roleData['permissions'])->pluck('id');
                $role->permissions()->sync($permIds);
            }
        }
    }
}
