<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Product;
use App\Models\Province;
use App\Models\Store;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $stores = Store::
        with('province:id,name', 'area:id,name')
            ->when($request->search, function ($q) use ($request) {

                return $q->where('name', 'like', "%" . $request->search . "%");

            })->latest()->paginate(10);

        return $this->sendResponse(['stores' => $stores], 'Data exited successfully');
    }

    /**
     * get active Store
     */
    public function activeStore()
    {
        $stores = Store::where('status', 1)->get();
        return $this->sendResponse(['stores' => $stores], 'Data exited successfully');
    }

    public function activationStore($id)
    {
        $coupon = Store::find($id);

        if ($coupon->status == 1) {
            $coupon->update([
                "status" => 0
            ]);
        } else {
            $coupon->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    // make main

    public function MainStore($id)
    {
        $store = Store::find($id);

        if ($store->main == 1) {
            return $this->sendError('There is an error in the data', []);
        } else {
            $checkMain = Store::where('main',1)->first();
           if ($checkMain){$checkMain->update(["main" => 0]);}
            $store->update([
                "main" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    public function create()
    {
        try {

            $provinces = Province::select('id', 'name')->get();
            $areas = Area::whereDoesntHave('store')->get();

            return $this->sendResponse([
                'provinces' => $provinces,
                'areas' => $areas,
            ], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }

    public function show($id,Request $request)
    {
        $products = Product::with(['company', 'category', 'subCategory', 'storeProducts' => function ($q) {
            $q->with('productStatus');
        }, 'mainMeasurementUnit', 'subMeasurementUnit'])->whereHas('storeProducts', function ($q) use ($id) {
            $q->where('store_id', $id);
        })->when($request->search, function ($q) use ($request) {

            return $q->where('name', 'like', "%" . $request->search . "%")
                ->orWhere('barcode', 'like', "%" . $request->search . "%")
                ->orWhereRelation('company','name','like','%'.$request->search.'%')
                ->orWhereRelation('category','name','like','%'.$request->search.'%')
                ->orWhereRelation('subCategory','name','like','%'.$request->search.'%');

        })->latest()->paginate(10);

        return $this->sendResponse(['products' => $products], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:stores,name',
                'email' => 'nullable|unique:stores,email',
                'phone' => 'required|unique:stores,phone',
                'province_id' => 'required|required|integer|exists:provinces,id',
                'area_id' => 'required|required|integer|exists:areas,id',
                'address' => 'nullable|string',
                'location' => 'nullable|string',
                'areas.*' => 'required|exists:areas,id'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $store = Store::create($request->all());

            $store->supportedAreas()->attach($request->areas);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $store = Store::with('supportedAreas')->find($id);
            $provinces = Province::select('id', 'name')->get();
            $areas = Area::whereDoesntHave('store')->get();
            foreach ($store->supportedAreas as $area) {
                $areas[] = $area;
            }

            return $this->sendResponse([
                'store' => $store,
                'areas' => $areas,
                'provinces' => $provinces
            ], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $store = Store::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:stores,name,' . $store->id,
                'email' => 'nullable|unique:stores,email,' . $store->id,
                'phone' => 'required|unique:stores,phone,' . $store->id,
                'province_id' => 'required|required|integer|exists:provinces,id',
                'area_id' => 'required|required|integer|exists:areas,id',
                'address' => 'nullable|string',
                'location' => 'nullable|string',
                'areas.*' => 'required|exists:areas,id'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->all();

            $store->update($data);
            $store->supportedAreas()->sync($request->areas);
            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

}
