<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductOutput;
use App\Models\Supplier;
use App\Models\User;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
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
            $products = Product::where('stores_id', $selectedStore['id'])->get();

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
                        ->get();
            
            $customers = Customer::where('stores_id', $selectedStore['id'])->get();

            $employeers = User::join('users_has_stores as us', 'users.id', '=', 'us.users_id')
                            ->where('stores_id', $selectedStore['id'])->get();

            $suppliers = Supplier::where('stores_id', $selectedStore['id'])->get();
            
        } else {
            $products = Product::whereIn('stores_id', $this->getMyStores())->get();

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
                        ->get();

            $customers = Customer::whereIn('stores_id', $this->getMyStores())->get();
            
            $employeers = User::join('users_has_stores as us', 'users.id', '=', 'us.users_id')
                            ->whereIn('stores_id', $this->getMyStores())->get();

            $suppliers = Supplier::whereIn('stores_id', $this->getMyStores())->get();
        }

        $productsInStock = [
            'labels' => $products->pluck('name')->toArray(),
            'values' => $products->pluck('qty_stock')->toArray()
        ];

        return Inertia::render('StoreManagement/Dashboard',[
            'productsInStock' => $productsInStock,
            'qtyProducts' => $products->sum('qty_stock'),
            'qtySales' => count($sales),
            'qtyCustomers' => count($customers),
            'qtyEmployeers' => count($employeers),
            'qtySupplier' => count($suppliers),
        ]);
    }
}
