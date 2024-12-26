<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Stock;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TestApiController extends Controller
{
    /**
     * Display a listing of the resource.
     * @throws Exception
     */
    public function index()
    {
        $menuItemId = 1;
        $menuItem = MenuItem::find($menuItemId);
        $menuItem->load('recipe.ingredients');
        $neStocks = [];
        if (!$menuItem->recipe) {
            throw new Exception("Menu item or recipe not found.");
        }
        foreach ($menuItem->recipe->ingredients as $ingredient) {
            $stockQuery = Stock::where('product_id', $ingredient->product_id)->where('quantity', '>', 0)->orderBy('id', 'asc');;
            $availableStock = $stockQuery->sum('quantity');
            $stocks = $stockQuery->get();
            $deductibleQuantity = $ingredient->quantity;

            if ($availableStock >= $ingredient->quantity) {
                foreach ($stocks as $stock) {
                    if ($deductibleQuantity <= $stock->quantity) {
                        $stock->quantity -= $deductibleQuantity;
                        $neStocks[] = $stock;
                        $stock->save();
                        break;
                    } else {
                        $deductibleQuantity -= $stock->quantity;
                        $stock->quantity = 0;
                        $neStocks[] = $stock;
                        $stock->save();
                    }
                }
            }else {
                throw new Exception("Insufficient stock for product ID: {$ingredient->product_id}");
            }
        }
//        $menuItem = MenuItem::with([
//            'recipe' => function ($query) {
//                $query->select('id');
//            },
//            'recipe.ingredients' => function ($query) {
//                $query->select('*');
//            },
//            'recipe.ingredients.product' => function ($query) {
//                $query->select('id');
//            },
//            'recipe.ingredients.product.stocks' => function ($query) {
//                $query->select('id', 'product_id', 'quantity');
//            }
//        ])->select('id', 'recipe_id')->find($menuItemId);

//        $stocks = [];
//        foreach ($menuItem?->recipe?->ingredients as $ingredient) {
//            if (isset($ingredient?->product?->stocks)) {
//                foreach ($ingredient->product->stocks as $stock) {
//                    $stocks[] = $stock;
//                }
//            }
//        }

//        foreach ($stocks as $stock) {
//            if ($stock instanceof Stock) {
//                dd(111);
//            }
//        }


        return response()->json(['stocks' => $neStocks]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
