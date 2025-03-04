<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipItemRequest;
use App\Http\Requests\UpdateMembershipItemRequest;
use App\Http\Resources\MembershipItemResource;
use App\Models\MembershipItem;
use Illuminate\Http\Request;

class MembershipItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MembershipItem::with("membershipType")->filter($request->all());
        $membership_items = $query->paginateResults($request->all());

        return MembershipItemResource::collection($membership_items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembershipItemRequest $request)
    {
        $validated = $request->validated();
        $validated["created_by"] = auth()->user()->id;
        $validated["updated_by"] = auth()->user()->id;

        $membership_items = MembershipItem::create($validated);

        return new MembershipItemResource($membership_items);

        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipItem $membershipItem)
    {
        return new MembershipItemResource($membershipItem->load('membershipType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipItemRequest $request, MembershipItem $membershipItem)
    {
        $validated = $request->validated();
        $membershipItem->update($validated);

        return new MembershipItemResource($membershipItem->load('membershipType'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipItem $membershipItem)
    {
        //
    }
}
