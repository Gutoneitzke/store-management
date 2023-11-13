<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Traits\GetCountryStateCityTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuppliersController extends Controller
{
    use GetCountryStateCityTrait;
    use MyStoresTrait;
    use HasSelectedStoreTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $suppliers = Supplier::with('city')
                ->where('stores_id', $selectedStore['id'])
                ->get();
        } else {
            $suppliers = Supplier::with('city')
                        ->whereIn('stores_id', $this->getMyStores())
                        ->get();
        }

        return Inertia::render('StoreManagement/Suppliers/Suppliers',[
            'suppliers' => $suppliers
        ]);
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
