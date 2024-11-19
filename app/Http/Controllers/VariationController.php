<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVariationRequest;
use App\Http\Requests\UpdateVariationRequest;
use App\Http\Requests\Variation\IndexRequest;
use App\Http\Requests\Variation\StoreRequest;
use App\Http\Requests\Variation\UpdateRequest;
use App\Http\Resources\VariationResource;
use App\Http\Resources\VariationResourceCollection;
use App\Models\Variation;
use App\Repositories\Contracts\VariationInterface;

class VariationController extends Controller
{
    private VariationInterface $interface;

    /**
     * @param VariationInterface $interface
     */
    public function __construct(VariationInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new VariationResourceCollection($list);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $list = $this->interface->save($request->all());
        return new VariationResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Variation $variation)
    {
        return new VariationResource($variation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Variation $variation)
    {
        $list = $this->interface->update($variation, $request->all());
        return new VariationResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variation $variation)
    {
        $this->interface->delete($variation);
        return response()->json(null, 204);
    }
}
