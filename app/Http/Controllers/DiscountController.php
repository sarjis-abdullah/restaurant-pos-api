<?php

namespace App\Http\Controllers;

use App\Http\Requests\Discount\IndexRequest;
use App\Http\Requests\Discount\StoreRequest;
use App\Http\Requests\Discount\UpdateRequest;
use App\Http\Resources\DiscountResource;
use App\Http\Resources\DiscountResourceCollection;
use App\Models\Discount;
use App\Repositories\Contracts\DiscountInterface;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    private DiscountInterface $interface;

    public function __construct(DiscountInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new DiscountResourceCollection($items);
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
        return new DiscountResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return new DiscountResource($discount);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Discount $discount)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $this->interface->delete($discount);
        return response()->json(null, 204);
    }
}
