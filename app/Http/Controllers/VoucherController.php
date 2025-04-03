<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $voucher = Voucher::where("member_id", $request->member_id)->get();

        return VoucherResource::collection($voucher);
    }

    public function getVoucher(Request $request)
    {
        $voucher = Voucher::where([
            ['member_id', $request->member_id],
            ['voucher_id', $request->voucher_code],
        ])->first();

        if (!$voucher) {
            return response()->json(['voucher_error' => 'Voucher not found'], 422);
        }

        if ($voucher->validity >= Carbon::today()) {
            return response()->json(['voucher_error' => 'Voucher has expired'], 422);
        }

        return new VoucherResource($voucher);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreVoucherRequest $request)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $validated = $request->validated();

        do {
            $voucherId = substr(str_shuffle($pool), 0, 8);
        } while (Voucher::where('voucher_id', $voucherId)->exists());

        $validated["voucher_id"] = $voucherId;

        $voucher = Voucher::create($validated);

        return new VoucherResource($voucher);
    }


    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        return new VoucherResource($voucher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
