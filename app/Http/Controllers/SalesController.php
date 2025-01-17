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
use App\Traits\HasProductsInStockTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SalesController extends Controller
{
    use MyStoresTrait;
    use HasSelectedStoreTrait;
    use HasProductsInStockTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $sales = DB::table('store_management.products_output as po')
                        ->select('po.*', DB::raw('(SELECT GROUP_CONCAT(DISTINCT ps.sales_id) FROM products_has_sales ps
                            JOIN products p ON p.id = ps.products_id
                            WHERE stores_id = ' . $selectedStore['id'] . ') AS products_has_sales_id'))
                        ->whereIn('po.id', function ($query) use ($selectedStore) {
                            $query->select(DB::raw('DISTINCT ps.sales_id'))
                                ->from('products_has_sales as ps')
                                ->join('products as p', 'p.id', '=', 'ps.products_id')
                                ->where('stores_id', '=', $selectedStore['id']);
                        })
                        ->orderBy('id', 'DESC')
                        ->get();


            $productsHasSales = ProductHasSale::join('products', 'products_has_sales.products_id', '=', 'products.id')
                                ->where('stores_id', $selectedStore['id'])
                                ->select('products.*','products_has_sales.sales_id as products_has_sales_id')
                                ->get();
        } else {
            $sales = DB::table('store_management.products_output as po')
                        ->select('po.*', DB::raw('(SELECT GROUP_CONCAT(DISTINCT ps.sales_id) FROM products_has_sales ps
                            JOIN products p ON p.id = ps.products_id
                            WHERE stores_id IN (' . implode(', ', $this->getMyStores()) . ')) AS products_has_sales_id'))
                        ->whereIn('po.id', function ($query) {
                            $query->select(DB::raw('DISTINCT ps.sales_id'))
                                ->from('products_has_sales as ps')
                                ->join('products as p', 'p.id', '=', 'ps.products_id')
                                ->whereIn('stores_id', $this->getMyStores());
                        })
                        ->orderBy('id', 'DESC')
                        ->get();


            $productsHasSales = ProductHasSale::join('products', 'products_has_sales.products_id', '=', 'products.id')
                                ->whereIn('stores_id', $this->getMyStores())
                                ->select('products.*','products_has_sales.sales_id as products_has_sales_id')
                                ->get();
        }

        return Inertia::render('StoreManagement/Sales/Sales',[
            'sales'            => $sales,
            'productsHasSales' => $productsHasSales
        ]);
    }

    /**
     * 
     * Show the form for creating a new resource.
     * @param int $id
     * 
     */
    public function createAccordingQrcode($id){
        $product               = Product::where('code', $id)
                                    ->join('products_has_entries', 'products.id', '=', 'products_has_entries.products_id')
                                    ->select('products.*','products_has_entries.unity_price')
                                    ->first();
        $products              = Product::whereIn('stores_id', $this->getMyStores())->get();
        $productsHasCategories = ProductHasCategory::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $productsHasEntries    = ProductHasEntry::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $categories            = Category::whereIn('stores_id', $this->getMyStores())->get();
        $customers             = Customer::whereIn('stores_id', $this->getMyStores())->get();
        $myStores              = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Sales/NewSale',[
            'product'               => $product,
            'products'              => $products,
            'productsHasCategories' => $productsHasCategories,
            'productsHasEntries'    => $productsHasEntries,
            'categories'            => $categories,
            'customers'             => $customers,
            'myStores'              => $myStores
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
            'product'               => null,
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
                if(!$this->hasQtyInStock($request['productsToSell'][$i])){
                    throw new \Exception('Quantidade de produtos maior que a disponível no estoque');
                }

                $this->removeQtyFromProduct($request['productsToSell'][$i]['products_id'],$request['productsToSell'][$i]['qty']);

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
                'discount'     => $request['discount'],
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
        $sales                 = ProductOutput::join('products_has_sales', 'products_output.id', '=', 'products_has_sales.sales_id')
                                    ->join('products', 'products.id', '=', 'products_has_sales.products_id')
                                    ->where('products_output.id', $id)
                                    ->select('products_output.*','products.name','products.qty_stock','products.stores_id','products_has_sales.unity_price','products_has_sales.products_id')
                                    ->get();
        $products              = Product::whereIn('stores_id', $this->getMyStores())->get();
        $productsHasCategories = ProductHasCategory::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $productsHasEntries    = ProductHasEntry::whereIn('products_id', $products->pluck('id')->toArray())->get();
        $categories            = Category::whereIn('stores_id', $this->getMyStores())->get();
        $customers             = Customer::whereIn('stores_id', $this->getMyStores())->get();
        $myStores              = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Sales/EditSale',[
            'sales'                 => $sales,
            'products'              => $products,
            'productsHasCategories' => $productsHasCategories,
            'productsHasEntries'    => $productsHasEntries,
            'categories'            => $categories,
            'customers'             => $customers,
            'myStores'              => $myStores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        try{
            // product_entries
            $totalValue = 0;
            $totalQty = 0;

            for($i = 0; $i < count($request['productsToSell']); $i++)
            {
                if(!$this->hasQtyInStock($request['productsToSell'][$i])){
                    throw new \Exception('Quantidade de produtos maior que a disponível no estoque');
                }

                $this->removeQtyFromProduct($request['productsToSell'][$i]['products_id'],$request['productsToSell'][$i]['qty']);

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
                'discount'     => $request['discount'],
            ];
            $productOutput = ProductOutput::find($id);
            $productOutput->update($productOutputData);
            
            // products_has_sales
            // delete
            ProductHasSale::where('sales_id',$id)->delete();

            // create
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
            return redirect()->back()->with('error', 'Falha ao editar a venda! '.$e);
        }
        return redirect(route('sales.index'));
    }
}

