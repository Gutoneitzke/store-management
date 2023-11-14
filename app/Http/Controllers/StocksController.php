<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductEntry;
use App\Models\ProductHasCategory;
use App\Models\ProductHasEntry;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierHasProduct;
use App\Models\SupplierProduct;
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
            // product
            if($request['newProduct']['status'] == '1' && $request['newProduct']['product_id'] == null){
                $productData = [
                    'name'        => $request['name'],
                    'total_price' => $request['qty'] * $request['unity_price'],
                    'qty_stock'   => $request['qty'],
                    'stores_id'   => $request['stores_id'],
                    'code'        => $this->generateRandomNumber()
                ];
    
                $product  = Product::create($productData);
                
            } else {
                $product = Product::find($request['newProduct']['product_id']);
            }

            // product_entries
            $productEntriesData = [
                'description' => $request['description'],
                'qty'         => $request['qty'],
                'total_price' => $request['qty'] * $request['unity_price'],
                'type'        => $request['type_entrie']
            ];
            $productEntries = ProductEntry::create($productEntriesData);

            // products_has_entries
            $productHasEntriesData = [
                'products_id' => $product->id,
                'entries_id'  => $productEntries->id,
                'unity_price' => $request['unity_price']
            ];
            ProductHasEntry::create($productHasEntriesData);

            // products_has_categories
            $productHasCategoriesData = [
                'products_id'   => $product->id,
                'categories_id' => $request['category_id'],
            ];
            ProductHasCategory::create($productHasCategoriesData);

            // supplier_has_products
            $supplierHasProductsData = [
                'supplier_id' => $request['suppliers_id'],
                'products_id' => $product->id,
            ];
            SupplierHasProduct::create($supplierHasProductsData);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o produto!');
        }
        return redirect(route('stocks.index'));
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
