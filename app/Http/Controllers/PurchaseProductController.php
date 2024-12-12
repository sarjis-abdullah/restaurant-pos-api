<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseProduct\IndexRequest;
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseProduct $purchaseProduct)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseProduct $purchaseProduct)
    {
        //
    }
}
