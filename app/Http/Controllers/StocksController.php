<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use App\Traits\GenerateRandomNumberTrait;
use App\Traits\GetCountryStateCityTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StocksController extends Controller
{
    use GetCountryStateCityTrait;
    use MyStoresTrait;
    use HasSelectedStoreTrait;
    use GenerateRandomNumberTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $products = Product::where('stores_id', $selectedStore['id'])->get();
        } else {
            $products = Product::whereIn('stores_id', $this->getMyStores())->get();
        }

        return Inertia::render('StoreManagement/Stocks/Stocks',[
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::whereIn('stores_id', $this->getMyStores())->get();
        $categories = Category::whereIn('stores_id', $this->getMyStores())->get();
        $suppliers = Supplier::whereIn('stores_id', $this->getMyStores())->get();
        $myStores = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Stocks/NewProduct',[
            'products'   => $products,
            'categories' => $categories,
            'suppliers'  => $suppliers,
            'myStores'   => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            if($request['newProduct']['status'] == '1' && $request['newProduct']['product_id'] == null){
                $productData = [
                    'name'        => $request['name'],
                    'description' => $request['description'],
                    'total_price' => $request['qty'] * $request['unity_price'],
                    'qty_stock'   => $request['qty'],
                    'stores_id'   => $request['stores_id'],
                    'code'        => $this->generateRandomNumber()
                ];
    
                $productId  = Product::create($productData);
                
            } else {
                $productData = [];
                $productId = $request['newProduct']['product_id'];
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o produto!');
        }
        // products -> ok
        // product_entries -> --
        // products_has_entries
        // products_has_categories
        // supplier_has_products
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
