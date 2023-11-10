<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\UserStore;
use App\Traits\GetCountryStateCityTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class StoresController extends Controller
{
    use GetCountryStateCityTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $stores = Store::with('city')
            ->join('users_has_stores', 'stores.id', '=', 'users_has_stores.stores_id')
            ->where('users_has_stores.users_id', $userId)
            ->join('users', 'users.id', '=', 'users_has_stores.users_id')
            ->whereIn('users.type', ['ADMIN','STORE_OWNER'])
            ->select('stores.*')
            ->get();

        return Inertia::render('StoreManagement/Stores/Stores',[
            'stores' => $stores
        ]);
    }

    /**
     * Set selected store to show data
     */
    public function selectedStore(Request $request){
        $storeId = $request->id;

        if(!empty($storeId)){
            $store = Store::find($storeId);
            session(['mySelectedStore' => $store]);
        } else {
            Session::forget('mySelectedStore');
        }

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('StoreManagement/Stores/NewStore',[
            'locales' => $this->getLocales()
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

            $userStoreData = [
                'users_id'  => Auth::id(),
                'stores_id' => $store->id,
                'type'      => 'STORE_OWNER',
            ];

            $userStore = UserStore::create($userStoreData);
            
            if($store && $userStore){
                return redirect(route('stores.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar a loja!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = Store::where('id', $id)->first();

        if($store->count() === 0){
            return redirect(route('stores.index'));
        } else {
            return Inertia::render('StoreManagement/Stores/EditStore',[
                'locales' => $this->getLocales(),
                'store'   => $store
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
            $store = Store::where('id', $id)->update($storeData);
            
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
        try {
            $userStore = UserStore::where('stores_id', $id);
            $store = Store::find($id);

            $userStore->delete();
            $store->delete();
            
            Session::flash('message', 'Store Deleted Successfully');
        } catch (\Exception $e) {
            Session::flash('message', 'Error to delete');
        }

        return redirect(route('stores.index'));
    }
}
