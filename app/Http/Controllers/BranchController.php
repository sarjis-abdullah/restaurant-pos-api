<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\IndexRequest;
use App\Http\Requests\Branch\StoreRequest;
use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Http\Resources\BranchResource;
use App\Http\Resources\BranchResourceCollection;
use App\Models\Branch;
use App\Repositories\Contracts\BranchInterface;

class BranchController extends Controller
{
    private BranchInterface $interface;

    public function __construct(BranchInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $items = $this->interface->findBy($request->all());
        return new BranchResourceCollection($items);
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
        $items = Branch::create($request->all());
        return new BranchResource($items);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Update $request, Branch $branch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
