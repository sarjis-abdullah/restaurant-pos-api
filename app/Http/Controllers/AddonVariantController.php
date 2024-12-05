<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddonVariant\IndexRequest;
use App\Http\Resources\AddonVariantResource;
use App\Http\Resources\AddonVariantResourceCollection;
use App\Models\AddonVariant;
use App\Repositories\Contracts\AddonVariantInterface;
use Illuminate\Http\Request;

class AddonVariantController extends Controller
{
    private AddonVariantInterface $interface;

    public function __construct(AddonVariantInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new AddonVariantResourceCollection($items);
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
    public function store(Request $request)
    {
        $items = $this->interface->saveMultipleAddonVariants($request->all());
        return new AddonVariantResourceCollection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(AddonVariant $addonVariant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddonVariant $addonVariant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AddonVariant $addonVariant)
    {
        $item = $this->interface->update($addonVariant, $request->all());
        return new AddonVariantResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddonVariant $addonVariant)
    {
        $this->interface->delete($addonVariant);
        return response()->json(null, 204);
    }
}
