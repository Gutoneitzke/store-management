<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductOutput;
use App\Models\Supplier;
use App\Models\User;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Carbon\Carbon;
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
                        ->select('po.*')
                        ->whereIn('po.id', function ($query) use ($selectedStore) {
                            $query->select(DB::raw('DISTINCT ps.sales_id'))
                                ->from('products_has_sales as ps')
                                ->join('products as p', 'p.id', '=', 'ps.products_id')
                                ->where('stores_id', '=', $selectedStore['id']);
                        })
                        ->get();
            
            $customers = Customer::where('stores_id', $selectedStore['id'])->get();

            $employeers = User::where('users.type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->where('users_has_stores.stores_id', $selectedStore['id'])
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->get();

            $suppliers = Supplier::where('stores_id', $selectedStore['id'])->get();

            $salesToday = DB::table('store_management.products_output as po')
                        ->select('po.*')
                        ->whereIn('po.id', function ($query) use ($selectedStore) {
                            $query->select(DB::raw('DISTINCT ps.sales_id'))
                                ->from('products_has_sales as ps')
                                ->join('products as p', 'p.id', '=', 'ps.products_id')
                                ->where('stores_id', '=', $selectedStore['id']);
                        })
                        ->whereDate('po.created_at', Carbon::today())
                        ->get();

            $salesLastMonth = DB::table('store_management.products_output as po')
                                ->select('po.*')
                                ->whereIn('po.id', function ($query) use ($selectedStore) {
                                    $query->select(DB::raw('DISTINCT ps.sales_id'))
                                        ->from('products_has_sales as ps')
                                        ->join('products as p', 'p.id', '=', 'ps.products_id')
                                        ->where('stores_id', '=', $selectedStore['id']);
                                })
                                ->whereDate('po.created_at', '>=', Carbon::today()->subMonth())
                                ->get();
            
        } else {
            $products = Product::whereIn('stores_id', $this->getMyStores())->get();

            $sales = DB::table('store_management.products_output as po')
                        ->select('po.*')
                        ->whereIn('po.id', function ($query) {
                            $query->select(DB::raw('DISTINCT ps.sales_id'))
                                ->from('products_has_sales as ps')
                                ->join('products as p', 'p.id', '=', 'ps.products_id')
                                ->whereIn('stores_id', $this->getMyStores());
                        })
                        ->get();

            $customers = Customer::whereIn('stores_id', $this->getMyStores())->get();
            
            $employeers = User::where('users.type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->whereIn('users_has_stores.stores_id', $this->getMyStores())
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->get();

            $suppliers = Supplier::whereIn('stores_id', $this->getMyStores())->get();

            $salesToday = DB::table('store_management.products_output as po')
                            ->select('po.*')
                            ->whereIn('po.id', function ($query) {
                                $query->select(DB::raw('DISTINCT ps.sales_id'))
                                    ->from('products_has_sales as ps')
                                    ->join('products as p', 'p.id', '=', 'ps.products_id')
                                    ->whereIn('stores_id', $this->getMyStores());
                            })
                            ->whereDate('po.created_at', Carbon::today())
                            ->get();

            $salesLastMonth = DB::table('store_management.products_output as po')
                            ->select('po.*')
                            ->whereIn('po.id', function ($query) {
                                $query->select(DB::raw('DISTINCT ps.sales_id'))
                                    ->from('products_has_sales as ps')
                                    ->join('products as p', 'p.id', '=', 'ps.products_id')
                                    ->whereIn('stores_id', $this->getMyStores());
                            })
                            ->whereDate('po.created_at', '>=', Carbon::today()->subMonth())
                            ->get();
        }

        $productsInStock = [
            'labels' => $products->pluck('name')->toArray(),
            'values' => $products->pluck('qty_stock')->toArray()
        ];

        return Inertia::render('StoreManagement/Dashboard',[
            'productsInStock' => $productsInStock,
            'qtyProducts'     => $products->sum('qty_stock'),
            'sales'           => $sales,
            'customers'       => $customers,
            'employeers'      => $employeers,
            'supplier'        => $suppliers,
            'salesToday'      => $salesToday,
            'salesLastMonth'  => $salesLastMonth
        ]);
    }
}
