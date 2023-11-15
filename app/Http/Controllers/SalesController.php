<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductHasCategory;
use App\Models\ProductHasEntry;
use App\Models\ProductOutput;
use App\Models\Store;
use App\Models\Supplier;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    use MyStoresTrait;
    use HasSelectedStoreTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $sales = ProductOutput::join('products_has_sales', 'products_output.id', '=', 'products_has_sales.sales_id')
                            ->join('products', 'products_has_sales.products_id', '=', 'products.id')
                            ->where('stores_id', $selectedStore['id'])->get();
        } else {
            $sales = ProductOutput::join('products_has_sales', 'products_output.id', '=', 'products_has_sales.sales_id')
                            ->join('products', 'products_has_sales.products_id', '=', 'products.id')
                            ->whereIn('stores_id', $this->getMyStores())->get();
        }

        return Inertia::render('StoreManagement/Sales/Sales',[
            'sales' => $sales
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products              = Product::whereIn('stores_id', $this->getMyStores())->get();
        $productsHasCategories = ProductHasCategory::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $productsHasEntries    = ProductHasEntry::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $categories            = Category::whereIn('stores_id', $this->getMyStores())->get();
        $customers             = Customer::whereIn('stores_id', $this->getMyStores())->get();
        $myStores              = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Sales/NewSale',[
            'products'              => $products,
            'productsHasCategories' => $productsHasCategories,
            'productsHasEntries'    => $productsHasEntries,
            'categories'            => $categories,
            'customers'             => $customers,
            'myStores'              => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
