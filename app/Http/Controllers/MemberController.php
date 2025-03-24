<?php

namespace App\Http\Controllers;

use App\Enums\MemberStatus;
use App\Http\Requests\CreateMemberRequest;
use App\Http\Requests\CreateProspectRequest;
use App\Http\Requests\CreateTrialRequest;
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
    public function create_member(CreateMemberRequest $request)
    {
        $validated = $request->validated();

        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['prefered_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['prefered_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['status'] = MemberStatus::LIVE_MEMBER;


        // Create the member
        $member = Member::create($validated);

        if (!empty($fitnessGoals)) {
            $member->fitnessGoals()->createMany(
                array_map(fn($goal) => ['fitness_goal' => $goal], $fitnessGoals)
            );
        }
        if (!empty($preferredContactMethods)) {
            $member->contactMethods()->createMany(
                array_map(fn($method) => ['prefered_contact_method' => $method], $preferredContactMethods)
            );
        }

        if (!empty($preferredWorkOutTimes)) {
            $member->workoutTimes()->createMany(
                array_map(fn($time) => ['prefered_workout_time' => $time], $preferredWorkOutTimes)
            );
        }

        // Create interests
        if (!empty($interestedIn)) {
            $member->interests()->createMany(
                array_map(fn($interest) => ['interested_in' => $interest], $interestedIn)
            );
        }

        Log::info("Member created successfully", ['member_id' => $member->id]);
    }

    public function create_trial(CreateTrialRequest $request)
    {
        $validated = $request->validated();

        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['prefered_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['prefered_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['status'] = MemberStatus::TRIAL;


        // Create the member
        $member = Member::create($validated);

        if (!empty($fitnessGoals)) {
            $member->fitnessGoals()->createMany(
                array_map(fn($goal) => ['fitness_goal' => $goal], $fitnessGoals)
            );
        }
        if (!empty($preferredContactMethods)) {
            $member->contactMethods()->createMany(
                array_map(fn($method) => ['prefered_contact_method' => $method], $preferredContactMethods)
            );
        }

        if (!empty($preferredWorkOutTimes)) {
            $member->workoutTimes()->createMany(
                array_map(fn($time) => ['prefered_workout_time' => $time], $preferredWorkOutTimes)
            );
        }

        // Create interests
        if (!empty($interestedIn)) {
            $member->interests()->createMany(
                array_map(fn($interest) => ['interested_in' => $interest], $interestedIn)
            );
        }

        Log::info("Member created successfully", ['member_id' => $member->id]);
    }


    public function create_prospect(CreateProspectRequest $request)
    {
        $validated = $request->validated();

        $fitnessGoals = $validated['fitness_goals'] ?? [];
        $preferredContactMethods = $validated['preferred_contact_method'] ?? [];
        $preferredWorkOutTimes = $validated['prefered_workout_times'] ?? [];
        $interestedIn = $validated['interested_in'] ?? [];
        $leadSources = $validated['lead_source'] ?? [];

        unset(
            $validated['fitness_goals'],
            $validated['preferred_contact_method'],
            $validated['prefered_workout_times'],
            $validated['interested_in'],
            $validated['lead_source']
        );

        $validated['lead_sources'] = $leadSources;
        $validated['status'] = MemberStatus::PROSPECT;


        // Create the member
        $member = Member::create($validated);

        if (!empty($fitnessGoals)) {
            $member->fitnessGoals()->createMany(
                array_map(fn($goal) => ['fitness_goal' => $goal], $fitnessGoals)
            );
        }
        if (!empty($preferredContactMethods)) {
            $member->contactMethods()->createMany(
                array_map(fn($method) => ['prefered_contact_method' => $method], $preferredContactMethods)
            );
        }

        if (!empty($preferredWorkOutTimes)) {
            $member->workoutTimes()->createMany(
                array_map(fn($time) => ['prefered_workout_time' => $time], $preferredWorkOutTimes)
            );
        }

        // Create interests
        if (!empty($interestedIn)) {
            $member->interests()->createMany(
                array_map(fn($interest) => ['interested_in' => $interest], $interestedIn)
            );
        }

        Log::info("Member created successfully", ['member_id' => $member->id]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
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
}
