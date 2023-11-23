<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Supplier;
use App\Traits\GetCountryStateCityTrait;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
                            ->orderBy('id', 'DESC')
                            ->get();
        } else {
            $suppliers = Supplier::with('city')
                        ->whereIn('stores_id', $this->getMyStores())
                        ->orderBy('id', 'DESC')
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
        $myStores = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Suppliers/NewSupplier',[
            'locales'  => $this->getLocales(),
            'myStores' => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $supplierData = [
            'name'                 => $request['name'],
            'description'          => $request['description'],
            'phone'                => $request['phone'],
            'email'                => $request['email'],
            'cpf_cnpj'             => $request['cpf_cnpj'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_number'       => $request['address_number'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_complement'   => $request['address_complement'],
            'stores_id'            => $request['stores_id'],
        ];

        try {
            $supplier = Supplier::create($supplierData);
            
            if($supplier){
                return redirect(route('suppliers.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar o fornecedor!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::where('id',$id)->first();
        $myStores = Store::whereIn('id', $this->getMyStores())->get();

        if($supplier->count() === 0){
            return redirect(route('suppliers.index'));
        } else {
            return Inertia::render('StoreManagement/Suppliers/EditSupplier',[
                'locales'  => $this->getLocales(),
                'supplier' => $supplier,
                'myStores' => $myStores
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplierData = [
            'name'                 => $request['name'],
            'description'          => $request['description'],
            'phone'                => $request['phone'],
            'email'                => $request['email'],
            'cpf_cnpj'             => $request['cpf_cnpj'],
            'cities_id'            => $request['city'],
            'address_street'       => $request['address_street'],
            'address_number'       => $request['address_number'],
            'address_neighborhood' => $request['address_neighborhood'],
            'address_complement'   => $request['address_complement'],
            'stores_id'            => $request['stores_id'],
        ];

        try {
            $supplier = Supplier::where('id', $id)->update($supplierData);
            
            if($supplier){
                return redirect(route('suppliers.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao editar o fornecedor!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = Supplier::find($id);

            $supplier->delete();
            
            Session::flash('message', 'Fornecedor deletado com sucesso');
        } catch (\Exception $e) {
            Session::flash('message', 'Error to delete');
        }

        return redirect(route('suppliers.index'));
    }
}
