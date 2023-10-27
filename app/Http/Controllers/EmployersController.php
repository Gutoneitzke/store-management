<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserStore;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Illuminate\Http\Request;
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
                            ->get();
        } else {
            $employers = User::where('type', 'EMPLOYEE')
                            ->join('users_has_stores', 'users.id', '=', 'users_has_stores.users_id')
                            ->whereIn('users_has_stores.stores_id', $this->getMyStoresTrait())
                            ->where('users.id', '!=', auth()->id())
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
