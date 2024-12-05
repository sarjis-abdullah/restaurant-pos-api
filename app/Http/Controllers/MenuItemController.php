<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuItem\IndexRequest;
use App\Http\Requests\MenuItem\UpdateRequest;
use App\Http\Requests\MenuItem\StoreRequest;
use App\Http\Resources\MenuItemResource;
use App\Http\Resources\MenuItemResourceCollection;
use App\Models\MenuItem;
use App\Repositories\Contracts\MenuItemInterface;

class MenuItemController extends Controller
{
    private MenuItemInterface $interface;

    public function __construct(MenuItemInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new MenuItemResourceCollection($items);
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
        return new MenuItemResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $menuItem)
    {
        return new MenuItemResource($menuItem);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MenuItem $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, MenuItem $menuItem): MenuItemResource
    {
        $items = $this->interface->update($menuItem, $request->all());
        return new MenuItemResource($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        $this->interface->delete($menuItem);
        return response()->json(null, 204);
    }
}
