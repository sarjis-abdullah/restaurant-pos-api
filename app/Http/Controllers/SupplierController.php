<?php

namespace App\Http\Controllers;

use App\Http\Requests\Supplier\IndexRequest;
use App\Http\Requests\Supplier\StoreRequest;
use App\Http\Requests\Supplier\UpdateRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\SupplierResourceCollection;
use App\Models\Supplier;
use App\Repositories\Contracts\SupplierInterface;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private SupplierInterface $interface;

    /**
     * @param SupplierInterface $interface
     */
    public function __construct(SupplierInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new SupplierResourceCollection($list);
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
        $list = $this->interface->save($request->all());
        return new SupplierResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        return new SupplierResource($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $list = $this->interface->update($supplier, $request->all());
        return new SupplierResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $list = $this->interface->delete($supplier);
        return response()->json(null, 204);
    }
}
