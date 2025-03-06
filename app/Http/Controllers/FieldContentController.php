<?php

namespace App\Http\Controllers;

use App\Http\Resources\FieldContentResource;
use App\Models\FieldContent;
use Illuminate\Http\Request;

class FieldContentController extends Controller
{
    public function index()
    {
        $fields = FieldContent::all();
        return FieldContentResource::collection($fields);
    }
}
