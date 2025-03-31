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
            "data" => $base_currency,
        ]);
    }

    public function update(Request $request, BaseCurrency $baseCurrency)
    {
        $request->validate([
            'code' => 'required|string|max:3',
            'name' => 'required|string',
            'symbol' => 'required|string',
            'decimal_place' => 'required|integer',
        ]);

        $baseCurrency->update($request->all());

        return response()->json([
            'message' => 'Base currency updated successfully',
            'data' => $baseCurrency,
        ]);
    }
}
