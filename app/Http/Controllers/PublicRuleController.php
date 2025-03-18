<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicRuleRequest;
use App\Http\Requests\UpdatePublicRuleRequest;
use App\Http\Resources\PublicRuleResource;
use App\Models\PublicRule;
use Illuminate\Http\Request;

class PublicRuleController extends Controller
{
    public function __construct()
    {
        // Apply middleware for permissions
        $this->middleware('permission:public_rule_view', ['only' => ['index']]);
        $this->middleware('permission:public_rule_create', ['only' => ['store']]);
        $this->middleware('permission:public_rule_edit', ['only' => ['update']]);
        $this->middleware('permission:public_rule_delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PublicRule::query()->filter($request->all());
        $public_variable = $query->paginateResults($request->all());

        return PublicRuleResource::collection($public_variable);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicRuleRequest $request)
    {
        $validated = $request->validated();
        $public_variables = PublicRule::create($validated);

        return new PublicRuleResource($public_variables);
    }

    /**
     * Display the specified resource.
     */
    public function show(PublicRule $PublicRule)
    {
        return new PublicRuleResource($PublicRule);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicRuleRequest $request, PublicRule $PublicRule)
    {
        $validated = $request->validated();
        $PublicRule->update($validated); // Perform the update

        // Fetch the updated record and return it as a resource
        return new PublicRuleResource($PublicRule->refresh());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublicRule $PublicRule)
    {
        //
    }
}
