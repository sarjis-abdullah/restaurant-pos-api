<?php

namespace App\Http\Controllers;

use App\Http\Requests\Purchase\IndexRequest;
use App\Http\Requests\Purchase\StoreRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\PurchaseResourceCollection;
use App\Models\Purchase;
use App\Repositories\Contracts\PurchaseInterface;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private PurchaseInterface $interface;

    public function __construct(PurchaseInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new PurchaseResourceCollection($list);
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
        return new PurchaseResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return new PurchaseResource($purchase);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $list = $this->interface->update($purchase, $request->all());
        return new PurchaseResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $this->interface->delete($purchase);
        return response()->json(null, 204);
    }
}
