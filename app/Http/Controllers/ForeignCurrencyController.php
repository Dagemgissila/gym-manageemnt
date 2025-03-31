<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreForeignCurrencyRequest;
use App\Http\Requests\UpdateForeignCurrencyRequest;
use App\Http\Resources\ForeignCurrencyresource;
use App\Models\ForeignCurrency;
use Illuminate\Http\Request;

class ForeignCurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ForeignCurrency::query()->filter($request->all());
        $foreign_currencys = $query->paginateResults($request->all());

        return ForeignCurrencyresource::collection($foreign_currencys);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreForeignCurrencyRequest $request)
    {
        $validated = $request->validated();
        $foreign_currency = ForeignCurrency::create($validated);

        return new ForeignCurrencyresource($foreign_currency);
    }

    /**
     * Display the specified resource.
     */
    public function show(ForeignCurrency $foreignCurrency)
    {
        return new ForeignCurrencyresource($foreignCurrency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateForeignCurrencyRequest $request, ForeignCurrency $foreignCurrency)
    {
        $validated = $request->validated();

        $foreignCurrency->update($validated);
        return new ForeignCurrencyresource($foreignCurrency);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ForeignCurrency $foreignCurrency)
    {
        //
    }
}
