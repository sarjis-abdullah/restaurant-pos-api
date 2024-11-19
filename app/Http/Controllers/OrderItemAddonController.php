<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderItemAddon\IndexRequest;
use App\Http\Requests\OrderItemAddon\StoreRequest;
use App\Http\Requests\OrderItemAddon\UpdateRequest;
use App\Http\Resources\OrderItemAddonResource;
use App\Http\Resources\OrderItemAddonResourceCollection;
use App\Models\OrderItemAddon;
use App\Repositories\Contracts\OrderItemAddonInterface;
use Illuminate\Http\Request;

class OrderItemAddonController extends Controller
{
    private OrderItemAddonInterface $interface;

    public function __construct(OrderItemAddonInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new OrderItemAddonResourceCollection($list);
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
        $list = $this->interface->save($request->all());
        return new OrderItemAddonResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderItemAddon $orderItemAddon)
    {
        return new OrderItemAddonResource($orderItemAddon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, OrderItemAddon $orderItemAddon)
    {
        $list = $this->interface->update($orderItemAddon, $request->all());
        return new OrderItemAddonResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderItemAddon $orderItemAddon)
    {
        $this->interface->delete($orderItemAddon);
        return response()->json(null, 204);
    }
}
