<?php

namespace App\Http\Controllers;

use App\Http\Requests\Menu\StoreRequest;
use App\Http\Requests\Menu\UpdateRequest;
use App\Http\Requests\Menu\IndexRequest;
use App\Http\Resources\FloorResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\MenuResourceCollection;
use App\Models\Floor;
use App\Models\Menu;
use App\Repositories\Contracts\MenuInterface;

class MenuController extends Controller
{
    private MenuInterface $interface;

    public function __construct(MenuInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new MenuResourceCollection($items);
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
        return new MenuResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return new MenuResource($menu);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Menu $menu)
    {
        $items = $this->interface->update($menu, $request->all());
        return new MenuResource($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $this->interface->delete($menu);
        return response()->json(null, 204);
    }
}
