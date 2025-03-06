<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVariableFieldsRequest;
use App\Http\Requests\UpdateVariableFieldsRequest;
use App\Http\Resources\VariableFieldResource;
use App\Models\VariableFields;
use Illuminate\Http\Request;

class VariableFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = VariableFields::with("fieldContent")->filter($request->all());
        $variable_fields = $query->paginateResults($request->all());

        return VariableFieldResource::collection($variable_fields);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVariableFieldsRequest $request)
    {
        $validated = $request->validated();
        $created = [];

        foreach ($validated['values'] as $value) {
            $created[] = VariableFields::create([
                'field_content_id' => $validated['field_content_id'],
                'value' => $value,
                'status' => $validated['status'] ?? true,
            ]);
        }

        return response()->json([
            "message" => "data created succesfully",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(VariableFields $variableFields)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariableFieldsRequest $request, VariableFields $variableFields)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariableFields $variableFields)
    {
        //
    }
}
