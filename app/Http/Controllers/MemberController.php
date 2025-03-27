<?php

namespace App\Http\Controllers;

use App\Enums\MemberStatus;
use App\Http\Requests\CreateMemberRequest;
use App\Http\Requests\CreateProspectRequest;
use App\Http\Requests\CreateTrialRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\Memberresource;
use App\Models\Member;
use Illuminate\Http\Request;
use Log;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::query()->filter($request->all());
        $members = $query->paginateResults($request->all());

        return Memberresource::collection($members);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return new Memberresource($member);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }




    public function UpdateMemberMembership(UpdateMemberRequest $request, $id)
    {
        $member = Member::findOrFail($id);
        $validated = $request->validated();
        $member->update($validated);

        Log::info("Member updated successfully", ['member_id' => $member->id]);

        // Return success response
        return response()->json([
            'message' => 'Member membership updated successfully',
            'member' => $member
        ], 200);
    }








    public function create_member(CreateMemberRequest $request)
    {
        $validated = $request->validated();
        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['preferred_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['preferred_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['fitness_goals'] = $fitnessGoals;
        $validated['preferred_contact_method'] = $preferredContactMethods;
        $validated['preferred_workout_times'] = $preferredWorkOutTimes;
        $validated['interested_in'] = $interestedIn;


        $validated['status'] = MemberStatus::LIVE_MEMBER;


        // Create the member
        $member = Member::create($validated);

        Log::info("Member created successfully", ['member_id' => $member->id]);
    }

    public function create_trial(CreateTrialRequest $request)
    {
        $validated = $request->validated();
        Log::info($validated);
        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['preferred_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['preferred_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['fitness_goals'] = $fitnessGoals;
        $validated['preferred_contact_method'] = $preferredContactMethods;
        $validated['preferred_workout_times'] = $preferredWorkOutTimes;
        $validated['interested_in'] = $interestedIn;



        $validated['status'] = MemberStatus::TRIAL;


        // Create the member
        $member = Member::create($validated);


        Log::info("Member created successfully", ['member_id' => $member->id]);
    }


    public function create_prospect(CreateProspectRequest $request)
    {
        $validated = $request->validated();
        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['preferred_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['preferred_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['fitness_goals'] = $fitnessGoals;
        $validated['preferred_contact_method'] = $preferredContactMethods;
        $validated['preferred_workout_times'] = $preferredWorkOutTimes;
        $validated['interested_in'] = $interestedIn;


        $validated['status'] = MemberStatus::PROSPECT;

        // Create the member
        $member = Member::create($validated);


        Log::info("Member created successfully", ['member_id' => $member->id]);
    }

}
