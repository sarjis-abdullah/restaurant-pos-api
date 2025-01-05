<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductResourceCollection;
use App\Models\Product;
use App\Repositories\Contracts\ProductInterface;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductInterface $interface;

    public function __construct(ProductInterface $interface)
    {
        $this->interface = $interface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $list = $this->interface->findBy($request->all());
        return new ProductResourceCollection($list);
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
        return new ProductResource($list);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Product $product)
    {
        $list = $this->interface->update($product, $request->all());
        return new ProductResource($list);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $list = $this->interface->delete($product);
        return response()->json(null, 204);
    }
}
