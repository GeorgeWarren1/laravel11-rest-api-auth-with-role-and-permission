<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionsByRole = [
            'admin' => ['restore posts', 'force delete posts'],
            'editor' => ['create a post', 'update a post', 'delete a post'],
            'participant' => ['view all posts', 'view a post']
        ];

        $insertPermissions = fn ($role) => collect($permissionsByRole[$role])
            ->map(fn ($name) => DB::table('permissions')->insertGetId(['name' => $name, 'guard_name' => 'web']))
            ->toArray();

        $permissionIdsByRole = [
            'admin' => $insertPermissions('admin'),
            'editor' => $insertPermissions('editor'),
            'participant' => $insertPermissions('participant')
        ];

        foreach ($permissionIdsByRole as $role => $permissionIds) {
            $roles = Role::create([
                'name' => $role
            ]);

            DB::table('role_has_permissions')
                ->insert(
                    collect($permissionIds)->map(fn ($id) => [
                        'role_id' => $roles->id,
                        'permission_id' => $id
                    ])->toArray()
                );
        }
    }
}
