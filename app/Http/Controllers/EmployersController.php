<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Models\UserStore;
use App\Traits\GetCountryStateCityTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class EmployersController extends Controller
{
    use HasSelectedStoreTrait;
    use MyStoresTrait;
    use GetCountryStateCityTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $selectedStore = $this->getSelectedStore();

        if($selectedStore){
            $employers = User::where('users.type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->where('users_has_stores.stores_id', $selectedStore['id'])
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->orderBy('id', 'DESC')
                            ->get();
        } else {
            $employers = User::where('users.type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->whereIn('users_has_stores.stores_id', $this->getMyStores())
                            ->where('users.id', '!=', auth()->id())
                            ->select('users.*')
                            ->orderBy('id', 'DESC')
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
        $stores = Store::whereIn('stores.id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Employers/NewEmployer',[
            'locales' => $this->getLocales(),
            'stores'  => $stores
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
                'stores_id' => $request['store_id'],
                'type'      => 'EMPLOYEE',
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
        $employee = User::where('id',$id)
                        ->where('type', 'EMPLOYEE')
                        ->first();

        if($employee->count() === 0){
            return redirect(route('employer.index'));
        } else {
            $stores    = Store::whereIn('stores.id', $this->getMyStores())->get();
            $userStore = UserStore::join('users', 'users.id', '=', 'users_has_stores.users_id')
                            ->where('users.id', $id)
                            ->where('users_has_stores.type', 'EMPLOYEE')
                            ->select('users_has_stores.*')
                            ->first();

            return Inertia::render('StoreManagement/Employers/EditEmployer',[
                'locales'   => $this->getLocales(),
                'employee'  => $employee,
                'stores'    => $stores,
                'userStore' => $userStore
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
        ];

        if(!empty($request['password'])){
            $userData['password'] = Hash::make($request['password']);
        }

        try {
            User::where('id', $id)->update($userData);

            $userStoreData = [
                'users_id'  => $request['user_original_store']['users_id'],
                'stores_id' => $request['store_id']
            ];

            UserStore::where('users_id', $request['user_original_store']['users_id'])
                    ->where('stores_id', $request['user_original_store']['stores_id'])
                    ->where('type', 'EMPLOYEE')
                    ->update($userStoreData);
            
            return redirect(route('employers.index'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao editar funcionário(a)!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $userStore = UserStore::where('users_id', $id)
                            ->where('type', 'EMPLOYEE');
            $userStore->delete();
            
            $employee = User::find($id);
            $employee->delete();
            
            Session::flash('message', 'Employee Deleted Successfully');
        } catch (\Exception $e) {
            Session::flash('message', 'Error to delete');
        }

        return redirect(route('employers.index'));
    }
}
