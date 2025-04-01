<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class TrainerControler extends Controller
{
    public function index()
    {
        $roles = Role::where("is_trainer", 1)->get();
        $roleNames = $roles->pluck('name')->toArray();
        $trainers = User::role($roleNames)->get();

        return UserResource::collection($trainers);
    }

}
