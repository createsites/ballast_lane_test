<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Response;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Table::with('bookings')->get()->all(),
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTableRequest $request)
    {
        $validatedData = $request->validated();
        $table = Table::create($validatedData);

        return response()->json($table, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        return response()->json($table, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        $validatedData = $request->validated();
        $table->update($validatedData);

        return response()->json($table, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
