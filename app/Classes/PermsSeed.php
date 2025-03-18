<?php

namespace App\Classes;

use Spatie\Permission\Models\Permission;

class PermsSeed
{
    public static $mainPermissionsArray = [
        // Users permissions
        'users_view' => [
            'name' => 'users_view',
        ],
        'users_create' => [
            'name' => 'users_create',
        ],
        'users_edit' => [
            'name' => 'users_edit',
        ],
        'users_delete' => [
            'name' => 'users_delete',
        ],



        // Membership Type permissions
        'membership_type_view' => [
            'name' => 'membership_type_view',
        ],
        'membership_type_create' => [
            'name' => 'membership_type_create',
        ],
        'membership_type_edit' => [
            'name' => 'membership_type_edit',
        ],
        'membership_type_delete' => [
            'name' => 'membership_type_delete',
        ],


        // Membership Item permissions
        'membership_item_view' => [
            'name' => 'membership_item_view',
        ],
        'membership_item_create' => [
            'name' => 'membership_item_create',
        ],
        'membership_item_edit' => [
            'name' => 'membership_item_edit',
        ],
        'membership_item_delete' => [
            'name' => 'membership_item_delete',
        ],


        // Role permissions
        'roles_view' => [
            'name' => 'roles_view',
        ],
        'roles_create' => [
            'name' => 'roles_create',
        ],
        'roles_edit' => [
            'name' => 'roles_edit',
        ],
        'roles_delete' => [
            'name' => 'roles_delete',
        ],

        // PublicRule permissions
        'public_rule_view' => [
            'name' => 'public_rule_view',
        ],
        'public_rule_create' => [
            'name' => 'public_rule_create',
        ],
        'public_rule_edit' => [
            'name' => 'public_rule_edit',
        ],
        'public_rule_delete' => [
            'name' => 'public_rule_delete',
        ],



        // PublicRule permissions
        'field_validation_view' => [
            'name' => 'field_validation_view',
        ],
        'field_validation_create' => [
            'name' => 'field_validation_update',
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
                $newPermission->save();
            }
        }
    }
}
