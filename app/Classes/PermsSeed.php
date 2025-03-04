<?php

namespace App\Classes;

use Spatie\Permission\Models\Permission;

class PermsSeed
{
    public static $mainPermissionsArray = [
        // Users permissions
        'users_view' => [
            'name' => 'users_view',
            'display_name' => 'User View'
        ],
        'users_create' => [
            'name' => 'users_create',
            'display_name' => 'User Create'
        ],
        'users_edit' => [
            'name' => 'users_edit',
            'display_name' => 'User Edit'
        ],
        'users_delete' => [
            'name' => 'users_delete',
            'display_name' => 'User Delete'
        ],



        // Membership Type permissions
        'membership_type_view' => [
            'name' => 'membership_type_view',
            'display_name' => 'Membership Type View'
        ],
        'membership_type_create' => [
            'name' => 'membership_type_create',
            'display_name' => 'Membership Type Create'
        ],
        'membership_type_edit' => [
            'name' => 'membership_type_edit',
            'display_name' => 'Membership Type Edit'
        ],
        'membership_type_delete' => [
            'name' => 'membership_type_delete',
            'display_name' => 'Membership Type Delete'
        ],


        // Membership Item permissions
        'membership_item_view' => [
            'name' => 'membership_item_view',
            'display_name' => 'Membership Item View'
        ],
        'membership_item_create' => [
            'name' => 'membership_item_create',
            'display_name' => 'Membership Item Create'
        ],
        'membership_item_edit' => [
            'name' => 'membership_item_edit',
            'display_name' => 'Membership Item Edit'
        ],
        'membership_item_delete' => [
            'name' => 'membership_item_delete',
            'display_name' => 'Membership Item Delete'
        ],


        // Role permissions
        'roles_view' => [
            'name' => 'roles_view',
            'display_name' => 'Role View'
        ],
        'roles_create' => [
            'name' => 'roles_create',
            'display_name' => 'Role Create'
        ],
        'roles_edit' => [
            'name' => 'roles_edit',
            'display_name' => 'Role Edit'
        ],
        'roles_delete' => [
            'name' => 'roles_delete',
            'display_name' => 'Role Delete'
        ],
    ];

    public static function getPermissionArray()
    {
        return self::$mainPermissionsArray;
    }

    public static function seedPermissions()
    {
        $permissions = self::getPermissionArray();

        foreach ($permissions as $group => $permission) {
            $permissionCount = Permission::where('name', $permission['name'])->count();

            if ($permissionCount == 0) {
                $newPermission = new Permission();
                $newPermission->name = $permission['name'];
                $newPermission->display_name = $permission['display_name'];
                $newPermission->save();
            }
        }
    }
}
