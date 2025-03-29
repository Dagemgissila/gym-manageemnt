<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreForeignCurrencyRequest;
use App\Http\Requests\UpdateForeignCurrencyRequest;
use App\Models\ForeignCurrency;

class ForeignCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreForeignCurrencyRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ForeignCurrency $foreignCurrency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateForeignCurrencyRequest $request, ForeignCurrency $foreignCurrency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForeignCurrency $foreignCurrency)
    {
        //
    }
}
