<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseProduct\IndexRequest;
use App\Http\Resources\PurchaseProductResource;
use App\Http\Resources\PurchaseProductResourceCollection;
use App\Models\PurchaseProduct;
use App\Repositories\Contracts\PurchaseProductInterface;
use Illuminate\Http\Request;

class PurchaseProductController extends Controller
{
    private PurchaseProductInterface $interface;

    /**
     * @param PurchaseProductInterface $interface
     */
    public function __construct(PurchaseProductInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new PurchaseProductResourceCollection($items);
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
        $items = $this->interface->save($request->all());
        return new PurchaseProductResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseProduct $purchaseProduct)
    {
        return new PurchaseProductResource($purchaseProduct);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseProduct $purchaseProduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseProduct $purchaseProduct)
    {
        $items = $this->interface->update($purchaseProduct, $request->all());
        return new PurchaseProductResource($items);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseProduct $purchaseProduct)
    {
        $this->interface->delete($purchaseProduct);
        return response()->json([], 204);
    }
}
