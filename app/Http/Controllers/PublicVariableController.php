<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicVariableRequest;
use App\Http\Requests\UpdatePublicVariableRequest;
use App\Http\Resources\PublicVariableResource;
use App\Models\PublicVariable;
use Illuminate\Http\Request;

class PublicVariableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PublicVariable::query()->filter($request->all());
        $public_variable = $query->paginateResults($request->all());

        return PublicVariableResource::collection($public_variable);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicVariableRequest $request)
    {
        $validated = $request->validated();
        $public_variables = PublicVariable::create($validated);

        return new PublicVariableResource($public_variables);
    }

    /**
     * Display the specified resource.
     */
    public function show(PublicVariable $publicVariable)
    {
        return new PublicVariableResource($publicVariable);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePublicVariableRequest $request, PublicVariable $publicVariable)
    {
        $validated = $request->validated();
        $public_variables = $publicVariable->update($validated);
        return new PublicVariableResource($public_variables);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublicVariable $publicVariable)
    {
        //
    }
}
