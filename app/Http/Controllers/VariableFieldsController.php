<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVariableFieldsRequest;
use App\Http\Requests\UpdateVariableFieldsRequest;
use App\Http\Resources\VariableFieldResource;
use App\Models\VariableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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

        try {
            DB::beginTransaction();

            $createdRecords = [];
            foreach ($validated['values'] as $valueData) {
                $createdRecords[] = VariableFields::create([
                    'field_content_id' => $validated['field_content_id'],
                    'value' => $valueData['value'],
                    'status' => $valueData['status'],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Data created successfully',
                'data' => $createdRecords,
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create data',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VariableFields $variableField)
    {
        return new VariableFieldResource($variableField->load('fieldContent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVariableFieldsRequest $request, VariableFields $variableField)
    {
        $validated = $request->validated();
        $variableField->update($validated);

        return new VariableFieldResource($variableField);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VariableFields $variableFields)
    {
        //
    }
}
