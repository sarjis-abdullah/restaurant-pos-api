<?php

namespace App\Http\Controllers;

use App\Http\Requests\Variant\IndexRequest;
use App\Http\Requests\Variant\StoreRequest;
use App\Http\Requests\Variant\UpdateRequest;
use App\Http\Resources\VariantResource;
use App\Http\Resources\VariantResourceCollection;
use App\Models\Variant;
use App\Repositories\Contracts\VariantInterface;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    private VariantInterface $interface;

    public function __construct(VariantInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new VariantResourceCollection($items);
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
        $item = $this->interface->saveMultipleVariants($request->all());
        return new VariantResourceCollection($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(Variant $variant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Variant $variant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Variant $variant)
    {
        $item = $this->interface->update($variant, $request->all());
        return new VariantResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variant $variant)
    {
        $this->interface->delete($variant);
        return response()->json(null, 204);
    }
}
