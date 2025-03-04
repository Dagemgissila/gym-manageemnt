<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipTypeRequest;
use App\Http\Requests\UpdateMembershipTypeRequest;
use App\Http\Resources\MembershipTypeResource;
use App\Models\MembershipType;
use Illuminate\Http\Request;
use Log;


class MembershipTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = MembershipType::query()->filter($request->all());
        $membership_types = $query->paginateResults($request->all());

        return MembershipTypeResource::collection($membership_types);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembershipTypeRequest $request)
    {
        $validated = $request->validated();
        $validated["created_by"] = auth()->id();
        $validated["updated_by"] = auth()->id();
        $membership_type = MembershipType::create($validated);

        return new MembershipTypeResource($membership_type);
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipType $membershipType)
    {
        return new MembershipTypeResource($membershipType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipTypeRequest $request, MembershipType $membershipType)
    {
        $validated = $request->validated();
        $membershipType->update($validated);
        return new MembershipTypeResource($membershipType);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipType $membershipType)
    {
        //
    }
}
