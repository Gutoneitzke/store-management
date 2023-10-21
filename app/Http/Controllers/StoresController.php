<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Store;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('StoreManagement/Stores/Stores',[
            'stores' => Store::with('city')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('StoreManagement/Stores/NewStore',[
            'cities' => City::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = [
            'name'                 => $request['name'],
            'cnpj'                 => $request['cnpj'],
            'description'          => $request['description'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_number'       => $request['address_number'],
            'address_complement'   => $request['address_complement'],
        ];

        try {
            $store = Store::create($storeData);
            
            if($store){
                return redirect(route('stores.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar a loja!');
        }
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
        $cities = City::all();
        $store = Store::where('id','=',$id)->first();

        if($store->count() === 0){
            return redirect(route('stores.index'));
        } else {
            return Inertia::render('StoreManagement/Stores/EditStore',[
                'cities' => $cities,
                'store'  => $store
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $storeData = [
            'name'                 => $request['name'],
            'cnpj'                 => $request['cnpj'],
            'description'          => $request['description'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_number'       => $request['address_number'],
            'address_complement'   => $request['address_complement'],
        ];

        try {
            $store = Store::where('id', '=', $id)->update($storeData);
            
            if($store){
                return redirect(route('stores.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao editar a loja!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
