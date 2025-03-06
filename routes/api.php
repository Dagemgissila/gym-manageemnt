<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FieldContentController;
use App\Http\Controllers\FieldValidationController;
use App\Http\Controllers\MembershipItemController;
use App\Http\Controllers\MembershipTypeController;
use App\Http\Controllers\PublicRuleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariableFieldsController;
use App\Models\FieldValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post("/login", [AuthController::class, "login"]);

Route::middleware(["api.check.auth"])->group(function () {
   Route::get("/user", [AuthController::class, "user"]);
   Route::post("/refresh-token", [AuthController::class, "refreshToken"]);
   Route::post('/logout', [AuthController::class, 'logout']);

   //users
   Route::apiResource('users', UserController::class);
   Route::apiResource('membership-types', MembershipTypeController::class);
   Route::apiResource('membership-items', MembershipItemController::class);
   Route::apiResource('public-rules', PublicRuleController::class);
   // In api.php
   Route::patch('/field-validations/bulk-update', [FieldValidationController::class, 'bulkUpdate']);
   Route::apiResource('field-validations', FieldValidationController::class);
   Route::apiResource('variable-fields', VariableFieldsController::class);
   Route::get('/field-contents', [FieldContentController::class, "index"]);


   //role and permissions
   Route::get('/roles', [RolePermissionController::class, 'index']);
   Route::get('/roles/{id}', [RolePermissionController::class, 'role']);
   Route::get('/role-with-permission/{id}', [RolePermissionController::class, 'RoleWithPermission']);
   Route::get('/permissions', [RolePermissionController::class, 'permissions']);
   Route::post('/add-role-permissions', [RolePermissionController::class, 'addRolePermission']);
   Route::patch('/edit-role-permissions', [RolePermissionController::class, 'updateRolePermission']);
});
