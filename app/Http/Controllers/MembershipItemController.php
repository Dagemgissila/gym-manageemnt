<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMembershipItemRequest;
use App\Http\Requests\UpdateMembershipItemRequest;
use App\Http\Resources\MembershipItemResource;
use App\Models\DateTimeRestriction;
use App\Models\MembershipItem;
use Illuminate\Http\Request;

class MembershipItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MembershipItem::with(["membershipType", "dateTimeRestrictions"])->filter($request->all());
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

        // Ensure `selected_days` is an array and `gym_access` is true before proceeding
        if (is_array($request->selected_days) && $membership_items->gym_access == 1) {
            foreach ($request->selected_days as $day) {
                $membership_items->dateTimeRestrictions()->create([
                    'day' => $day['day'],
                    'from_time' => $day['from_time'],
                    'to_time' => $day['to_time'],
                    'time_period' => $day['time_period'],
                ]);
            }
        }

        // Return the resource with loaded dateTimeRestrictions
        return new MembershipItemResource($membership_items);
    }

    /**
     * Display the specified resource.
     */
    public function show(MembershipItem $membershipItem)
    {
        return new MembershipItemResource($membershipItem->load(["membershipType", "dateTimeRestrictions"]));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMembershipItemRequest $request, MembershipItem $membershipItem)
    {
        $validated = $request->validated();
        $membershipItem->update($validated);

        // Ensure `selected_days` is an array and `gym_access` is enabled before proceeding
        if (is_array($request->selected_days) && $membershipItem->gym_access == 1) {

            // Remove existing dateTimeRestrictions to avoid duplicates
            $membershipItem->dateTimeRestrictions()->delete();

            // Add the new dateTimeRestrictions
            foreach ($request->selected_days as $day) {
                $membershipItem->dateTimeRestrictions()->create([
                    'day' => $day['day'],
                    'from_time' => $day['from_time'],
                    'to_time' => $day['to_time'],
                    'time_period' => $day['time_period'],
                ]);
            }
        }

        // Return the resource with loaded dateTimeRestrictions
        return new MembershipItemResource($membershipItem->load('dateTimeRestrictions', 'membershipType'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MembershipItem $membershipItem)
    {
        //
    }
}
