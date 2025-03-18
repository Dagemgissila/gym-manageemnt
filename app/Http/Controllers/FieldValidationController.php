<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldValidationRequest;
use App\Http\Requests\UpdateFieldValidationRequest;
use App\Models\FieldValidation;
use Illuminate\Http\Request;
use Log;

class FieldValidationController extends Controller
{


    public function __construct()
    {
        // Apply middleware for permissions
        $this->middleware('permission:field_validation_view', ['only' => ['index']]);
        $this->middleware('permission:field_validation_update', ['only' => ['bulkUpdate']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fields = FieldValidation::all();
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldValidationRequest $request)
    {
        $fieldValidation = FieldValidation::create($request->all());

        return response()->json($fieldValidation);

    }

    /**
     * Display the specified resource.
     */
    public function show(FieldValidation $fieldValidation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldValidationRequest $request, FieldValidation $fieldValidation)
    {
        $fieldValidation->update($request->all());
        return response()->json($fieldValidation);
    }



    // In FieldValidationController.php

    public function bulkUpdate(Request $request)
    {
        $validatedData = $request->validate([
            '*.id' => 'required|exists:field_validations,id', // Ensure each item has an ID
            '*.field_name' => 'sometimes|string|max:255',
            '*.field_key' => 'sometimes|string|max:255',
            '*.prospect' => 'sometimes|string|in:YES,NO',
            '*.trial' => 'sometimes|string|in:YES,NO',
            '*.membership' => 'sometimes|string|in:YES,NO',
        ]);

        Log::info('Validated Data:', $validatedData);
        foreach ($validatedData as $item) {
            $fieldValidation = FieldValidation::find($item['id']);
            if ($fieldValidation) {
                $fieldValidation->update($item);
            }
        }

        return response()->json(['message' => 'Forms Validation update successful']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FieldValidation $fieldValidation)
    {
        //
    }
}
