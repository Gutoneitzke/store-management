<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductHasCategory;
use App\Models\ProductHasEntry;
use App\Models\ProductHasSale;
use App\Models\ProductOutput;
use App\Models\Store;
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
                            ->where('stores_id', $selectedStore['id'])
                            ->select('products_output.*')
                            ->distinct()
                            ->get();
        } else {
            $sales = ProductOutput::join('products_has_sales', 'products_output.id', '=', 'products_has_sales.sales_id')
                            ->join('products', 'products_has_sales.products_id', '=', 'products.id')
                            ->whereIn('stores_id', $this->getMyStores())
                            ->select('products_output.*')
                            ->distinct()
                            ->get();
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
        try{
            // product_entries
            $totalValue = 0;
            $totalQty = 0;
            for($i = 0; $i < count($request['productsToSell']); $i++)
            {
                $totalValue += $request['productsToSell'][$i]['unity_price'] * $request['productsToSell'][$i]['qty'];
                $totalQty   += $request['productsToSell'][$i]['qty'];
            }
            
            if(!empty($request['discount'])){
                $totalValue = $totalValue - ($totalValue * $request['discount'])/100;
            }

            $productOutputData = [
                'description'  => $request['description'],
                'qty'          => $totalQty,
                'total_price'  => $totalValue,
                'customers_id' => $request['customers_id'],
                // 'discount'     => $request['discount'],
            ];
            $productOutput = ProductOutput::create($productOutputData);
            
            // products_has_sales
            for($i = 0; $i < count($request['productsToSell']); $i++){
                for($y = 0; $y < $request['productsToSell'][$i]['qty']; $y++){
                    $productsHasSalesData = [
                        'products_id' => $request['productsToSell'][$i]['products_id'],
                        'sales_id'    => $productOutput->id,
                        'unity_price' => $request['productsToSell'][$i]['unity_price'],
                    ];
                    ProductHasSale::create($productsHasSalesData);
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar a venda!');
        }
        return redirect(route('sales.index'));
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
