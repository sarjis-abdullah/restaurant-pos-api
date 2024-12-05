<?php

namespace App\Http\Controllers;

use App\Http\Requests\Addon\IndexRequest;
use App\Http\Requests\Addon\UpdateRequest;
use App\Http\Requests\Addon\StoreRequest;
use App\Http\Resources\AddonResource;
use App\Http\Resources\AddonResourceCollection;
use App\Models\Addon;
use App\Repositories\Contracts\AddonInterface;
use Illuminate\Http\Request;

class AddonController extends Controller
{
    private AddonInterface $interface;

    public function __construct(AddonInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new AddonResourceCollection($list);
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
        $list = $this->interface->saveMultipleAddons($request->all());
        return new AddonResourceCollection($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Addon $addon)
    {
        return new AddonResource($addon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Addon $addon)
    {
        $list = $this->interface->update($addon, $request->all());
        return new AddonResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Addon $addon)
    {
        $this->interface->delete($addon);
        return response()->json(null, 204);
    }
}
