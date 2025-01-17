<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Store;
use App\Traits\HasSelectedStoreTrait;
use App\Traits\MyStoresTrait;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriesController extends Controller
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
            $categories = Category::where('stores_id', $selectedStore['id'])
                            ->orderBy('id', 'DESC')
                            ->get();
        } else {
            $categories = Category::whereIn('stores_id', $this->getMyStores())
                            ->orderBy('id', 'DESC')
                            ->get();
        }

        return Inertia::render('StoreManagement/Categories/Categories',[
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $myStores = Store::whereIn('id', $this->getMyStores())->get();

        return Inertia::render('StoreManagement/Categories/NewCategory',[
            'myStores' => $myStores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $categoryData = [
            'name'        => $request['name'],
            'description' => $request['description'],
            'stores_id'   => $request['stores_id'],
        ];

        try {
            $category = Category::create($categoryData);
            
            if($category){
                return redirect(route('categories.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao registrar a categoria!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('id',$id)->first();
        $myStores = Store::whereIn('id', $this->getMyStores())->get();

        if($category->count() === 0){
            return redirect(route('categories.index'));
        } else {
            return Inertia::render('StoreManagement/Categories/EditCategory',[
                'category' => $category,
                'myStores' => $myStores
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoryData = [
            'name'        => $request['name'],
            'description' => $request['description'],
            'stores_id'   => $request['stores_id'],
        ];

        try {
            $category = Category::where('id', $id)->update($categoryData);
            
            if($category){
                return redirect(route('categories.index'));
            }
            new Exception();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao editar a categoria!');
        }
    }
}
