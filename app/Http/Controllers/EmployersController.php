<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class EmployersController extends Controller
{
    use HasSelectedStoreTrait;
    use MyStoresTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $employers = User::where('type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->where('users_has_stores.stores_id', $selectedStore['id'])
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->get();
        } else {
            $employers = User::where('type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->whereIn('users_has_stores.stores_id', $this->getMyStoresTrait())
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->get();
        }

        return Inertia::render('StoreManagement/Employers/Employers',[
            'employers' => $employers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = Store::whereIn('stores.id', $this->getMyStoresTrait())->get();

        return Inertia::render('StoreManagement/Employers/NewEmployer',[
            'countries' => Country::all(),
            'states'    => State::all(),
            'cities'    => City::all(),
            'stores'    => $stores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userData = [
            'name'                 => $request['name'],
            'email'                => $request['email'],
            'cpf'                  => $request['cpf'],
            'type'                 => $request['type'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_number'       => $request['address_number'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_complement'   => $request['address_complement'],
            'password'             => Hash::make($request['password']),
        ];

        try {
            $user = User::create($userData);

            $userStoreData = [
                'users_id'  => $user->id,
                'stores_id' => $request['store_id']
            ];

            UserStore::create($userStoreData);
            
            return redirect(route('employers.index'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar funcionário(a)!');
        }
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
