<?php

namespace App\Http\Controllers;

use App\Models\BaseCurrency;
use Illuminate\Http\Request;

class BaseCurrencyController extends Controller
{
    public function index()
    {
        $base_currency = BaseCurrency::all();

        return response()->json([
            "data" => $base_currency
        ]);
    }
}
