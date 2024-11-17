<?php

namespace App\Http\Controllers;

use App\Http\Requests\Floor\IndexRequest;
use App\Http\Requests\Floor\StoreRequest;
use App\Http\Requests\Floor\UpdateRequest;
use App\Http\Requests\StoreFloorRequest;
use App\Http\Requests\UpdateFloorRequest;
use App\Http\Resources\FloorResource;
use App\Http\Resources\FloorResourceCollection;
use App\Models\Floor;
use App\Repositories\Contracts\FloorInterface;

class FloorController extends Controller
{
    private FloorInterface $interface;

    public function __construct(FloorInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new FloorResourceCollection($items);
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
        $items = $this->interface->save($request->all());
        return new FloorResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Floor $floor)
    {
        return new FloorResource($floor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Floor $floor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Floor $floor): FloorResource
    {
        $items = $this->interface->update($floor, $request->all());
        return new FloorResource($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Floor $floor): \Illuminate\Http\JsonResponse
    {
        $this->interface->delete($floor);
        return response()->json(null, 204);
    }
}
