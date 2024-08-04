<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Exceptions\PosException;
use App\Http\Requests\StoreTableRequest;
use App\Http\Requests\Table\BookingRequest;
use App\Http\Requests\Table\StoreRequest;
use App\Http\Requests\UpdateTableRequest;
use App\Http\Resources\TableResource;
use App\Http\Resources\TableResourceCollection;
use App\Models\Table;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Table::paginate(5);
        return new TableResourceCollection($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $items = Table::create($request->all());
        return new TableResource($items);
    }

    /**
     * @throws PosException
     */
    public function bookTable(BookingRequest $request, Table $table): TableResource
    {
        if ($table->status == TableStatus::available->value){
            $data = $request->validated();
            $data['status'] = TableStatus::requestToBook->value;
            $table->update($data);
            return new TableResource($table);
        }
        throw new PosException([
            'message' => 'Table is not available or already booked'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTableRequest $request, Table $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        //
    }
}
