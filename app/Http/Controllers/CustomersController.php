<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Traits\GetCountryStateCityTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomersController extends Controller
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
            $customers = Customer::with('city')
                ->where('stores_id', $selectedStore['id'])
                ->get();
        } else {
            $customers = Customer::with('city')
                        ->whereIn('stores_id', $this->getMyStoresTrait())
                        ->get();
        }

        return Inertia::render('StoreManagement/Customers/Customers',[
            'customers' => $customers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myStores = Store::whereIn('id', $this->getMyStoresTrait())->get();

        return Inertia::render('StoreManagement/Customers/NewCustomer',[
            'locales'  => $this->getLocales(),
            'myStores' => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $customerData = [
            'name'                 => $request['name'],
            'email'                => $request['email'],
            'cpf_cnpj'             => $request['cpf_cnpj'],
            'gender'               => $request['gender'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_number'       => $request['address_number'],
            'address_complement'   => $request['address_complement'],
            'stores_id'            => $request['stores_id'],
        ];

        try {
            $customer = Customer::create($customerData);
            
            if($customer){
                return redirect(route('customers.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o cliente!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::where('id',$id)->first();
        $myStores = Store::whereIn('id', $this->getMyStoresTrait())->get();

        if($customer->count() === 0){
            return redirect(route('customers.index'));
        } else {
            return Inertia::render('StoreManagement/Customers/EditCustomer',[
                'locales'  => $this->getLocales(),
                'customer' => $customer,
                'myStores' => $myStores
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customerData = [
            'name'                 => $request['name'],
            'email'                => $request['email'],
            'cpf_cnpj'             => $request['cpf_cnpj'],
            'gender'               => $request['gender'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_number'       => $request['address_number'],
            'address_complement'   => $request['address_complement'],
            'stores_id'            => $request['stores_id'],
        ];

        try {
            $customer = Customer::where('id', $id)->update($customerData);
            
            if($customer){
                return redirect(route('customers.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao editar o cliente!');
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
