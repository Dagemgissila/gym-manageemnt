<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExchangeRateRequest;
use App\Http\Requests\UpdateExchangeRateRequest;
use App\Http\Resources\ExchangeRateResource;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ExchangeRate::with(["BaseCurrency", "ForeignCurrency"])->filter($request->all());
        $exchange_rate = $query->paginateResults($request->all());

        return ExchangeRateResource::collection($exchange_rate);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExchangeRateRequest $request)
    {
        $validated = $request->validated();
        $exchange_rate = ExchangeRate::create($validated);
        return new ExchangeRateResource($exchange_rate);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExchangeRate $exchangeRate)
    {
        $exchangeRate->load(["BaseCurrency", "ForeignCurrency"]);
        return new ExchangeRateResource($exchangeRate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExchangeRateRequest $request, ExchangeRate $exchangeRate)
    {
        $validated = $request->validated();
        $exchangeRate->update($validated);

        return new ExchangeRateResource($exchangeRate);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExchangeRate $exchangeRate)
    {
        //
    }
}
