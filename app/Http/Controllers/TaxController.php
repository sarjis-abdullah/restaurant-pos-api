<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tax\StoreRequest;
use App\Http\Requests\Tax\IndexRequest;
use App\Http\Requests\Tax\UpdateRequest;
use App\Http\Resources\TaxResource;
use App\Http\Resources\TaxResourceCollection;
use App\Models\Tax;
use App\Repositories\Contracts\TaxInterface;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    private TaxInterface $interface;

    public function __construct(TaxInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new TaxResourceCollection($items);
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
        return new TaxResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tax $tax)
    {
        return new TaxResource($tax);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Tax $tax)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        $this->interface->delete($tax);
        return response()->json(null, 204);
    }
}
