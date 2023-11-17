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
use App\Traits\GenerateRandomNumberTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StocksController extends Controller
{
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
        $products              = Product::whereIn('stores_id', $this->getMyStores())->get();
        $productsHasCategories = ProductHasCategory::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $categories            = Category::whereIn('stores_id', $this->getMyStores())->get();
        $suppliers             = Supplier::whereIn('stores_id', $this->getMyStores())->get();
        $myStores              = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Stocks/NewProduct',[
            'products'              => $products,
            'productsHasCategories' => $productsHasCategories,
            'categories'            => $categories,
            'suppliers'             => $suppliers,
            'myStores'              => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // product
            if($request['newProduct']['status'] == '1'){
                // new product
                $productData = [
                    'name'           => $request['name'],
                    'total_price'    => $request['qty'] * $request['unity_price'],
                    'qty_stock'      => $request['qty'],
                    'stores_id'      => $request['stores_id'],
                    'win_percentage' => $request['win_percentage'],
                    'code'           => $this->generateRandomNumber()
                ];
    
                $product = Product::create($productData);

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
                
            } else {
                // add and update in exists product
                $product = Product::find($request['newProduct']['product_id']);

                $productData = [
                    'total_price' => $product->total_price + ($request['qty'] * $request['unity_price']),
                    'qty_stock'   => $product->qty_stock + $request['qty'],
                ];

                $product->update($productData);
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

            for($i = 0; $i < $request['qty']; $i++){
                ProductHasEntry::create($productHasEntriesData);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o produto!');
        }
        return redirect(route('stocks.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $stocks = Product::where('products.id',$id)
            ->join('products_has_categories', 'products.id', '=', 'products_has_categories.products_id')
            ->join('products_has_entries', 'products.id', '=', 'products_has_entries.products_id')
            ->join('products_entries', 'products_has_entries.entries_id', '=', 'products_entries.id')
            ->join('supplier_has_products', 'products.id', '=', 'supplier_has_products.products_id')
            ->select('products.*','products_has_categories.*','products_has_entries.unity_price','products_entries.id as products_entries_id','products_entries.type','products_entries.description','supplier_has_products.*')
            ->first();

        $categories = Category::whereIn('stores_id', $this->getMyStores())->get();
        $suppliers  = Supplier::whereIn('stores_id', $this->getMyStores())->get();
        $myStores   = Store::whereIn('id', $this->getMyStores())->get();

        if($stocks->count() === 0){
            return redirect(route('suppliers.index'));
        } else {
            return Inertia::render('StoreManagement/Stocks/EditProduct',[
                'stocks'                => $stocks,
                'categories'            => $categories,
                'suppliers'             => $suppliers,
                'myStores'              => $myStores
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            // product
            $productData = [
                'name'           => $request['name'],
                'total_price'    => $request['qty'] * $request['unity_price'],
                'stores_id'      => $request['stores_id'],
                'qty_stock'      => $request['qty'],
                'win_percentage' => (int)$request['win_percentage'],
            ];

            $product = Product::find($id);
            $product->update($productData);

            // product_entries
            $productEntriesData = [
                'description' => $request['description'],
                'qty'         => $request['qty'],
                'total_price' => $request['qty'] * $request['unity_price'],
                'type'        => $request['type_entrie']
            ];
            $productEntries = ProductEntry::find($request['stocks_data']['products_entries_id']);
            $productEntries->update($productEntriesData);

            // products_has_entries
            // delete
            ProductHasEntry::where('products_id',$id)->delete();

            // create
            $productHasEntriesData = [
                'products_id' => $id,
                'entries_id'  => $productEntries->id,
                'unity_price' => $request['unity_price']
            ];

            for($i = 0; $i < (int)$request['qty']; $i++){
                ProductHasEntry::create($productHasEntriesData);
            }

            // products_has_categories
            $productHasCategoriesData = [
                'categories_id' => $request['category_id'],
            ];

            ProductHasCategory::where('products_id',$id)->update($productHasCategoriesData);

            // supplier_has_products
            $supplierHasProductsData = [
                'supplier_id' => $request['suppliers_id'],
            ];
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o produto!');
        }
        return redirect(route('stocks.index'));
    }
}
