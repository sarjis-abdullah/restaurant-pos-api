<?php

namespace App\Http\Controllers;

use App\Http\Requests\Return\IndexRequest;
use App\Http\Requests\Return\StoreRequest;
use App\Http\Resources\StockReturnResource;
use App\Http\Resources\StockReturnResourceCollection;
use App\Models\StockReturn;
use App\Repositories\Contracts\StockReturnInterface;
use Illuminate\Http\Request;

class StockReturnController extends Controller
{
    private StockReturnInterface $interface;

    public function __construct(StockReturnInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new StockReturnResourceCollection($list);
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
        return new StockReturnResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(StockReturn $stockReturn)
    {
        return new StockReturnResource($stockReturn);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockReturn $stockReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StockReturn $stockReturn)
    {
        $list = $this->interface->update($stockReturn, $request->all());
        return new StockReturnResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockReturn $stockReturn)
    {
        $this->interface->delete($stockReturn);
        return response()->json(null, 204);
    }
}
