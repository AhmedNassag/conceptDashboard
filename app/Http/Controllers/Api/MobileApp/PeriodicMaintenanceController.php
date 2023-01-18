<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\FilterWax;
use App\Models\Order;
use App\Models\PeriodicMaintenance;
use App\Models\Product;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeriodicMaintenanceController extends Controller
{
    use Message;
    public $selling_method;

    public function __construct()
    {
        $this->selling_method = auth()->user()->client->selling_method_id;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'quantity' => 'required',
                // 'price' => 'required',
                'next_maintenance' => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors(), 401);
            }

            // $data = $request->only(['name', 'quantity', 'price', 'next_maintenance']);
            // $periodicMaintenance = PeriodicMaintenance::create($data);

<<<<<<< HEAD
            $price = FilterWax::where('id',$request->wax_id)->first();
            $periodicMaintenance = PeriodicMaintenance::create([
                'user_id' =>auth()->user()->id,
=======
            $price = FilterWax::first();
            $periodicMaintenance = PeriodicMaintenance::create([
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $price->name,
                'next_maintenance' => $request->next_maintenance
            ]);

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        }

        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_order = Order::where('user_id', auth()->user()->id)->first();
        DB::beginTransaction();
        try {
<<<<<<< HEAD
            $periodicMaintenance = PeriodicMaintenance::where('user_id',auth()->user()->id)->orderBy('next_maintenance','asc')->first();
=======

            $periodicMaintenance = PeriodicMaintenance::where('order_id',$user_order->id)->orderBy('next_maintenance','asc')->first();
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8

            // Validator request
            $v = Validator::make($request->all(), [
                // 'name' => ['required', 'string'],
                // 'quantity' => 'required',
                // 'price' => 'required',
                'next_maintenance' => 'required',
                'note' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only([/*'name', 'quantity', 'price', */'next_maintenance', 'note']);

            $periodicMaintenance->update($data);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    public function nextMaintenance()
    {
<<<<<<< HEAD
        $periodicMaintenance = PeriodicMaintenance::where('user_id', auth()->user()->id)->where('next_maintenance','>',now())->orderBy('next_maintenance', 'asc')->first();
        if($periodicMaintenance)
        {
            $price = Product::where('name', $periodicMaintenance->price)->with('latestPrice')->first();
            if($price)
            {
                return $this->sendResponse(['next_maintenance' => $periodicMaintenance->next_maintenance, 'price' => $price->latestPrice->price], trans('message.messageSuccessfully'));
            }
            else
            {
                return $this->sendResponse(['next_maintenance' => '0', 'price' =>'0'], trans('message.messageSuccessfully'));
            }
        }
        else
        {
            return $this->sendResponse(['next_maintenance' => '0', 'price' =>'0'], trans('message.messageSuccessfully'));
        }
    }



    public function filterWaxes()
    {
        try {
            $filterWaxes = FilterWax::get();
            return $this->sendResponse(['filterWaxes' => $filterWaxes], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
=======
        $user_order = Order::where('user_id', auth()->user()->id)->first();
        if($user_order)
        {
            $periodicMaintenance = PeriodicMaintenance::where('order_id', $user_order->id)->where('next_maintenance','>',now())->orderBy('next_maintenance', 'asc')->first();
            return $this->sendResponse(['next_maintenance' => $periodicMaintenance->next_maintenance], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['next_maintenance' => '0'], trans('message.messageSuccessfully'));
        }
        // $user_product = Product::where('name',$periodicMaintenance->price)->with('productPrice')->first();
        // return $this->sendResponse(['user_product' => $user_product], trans('message.messageSuccessfully'));
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
    }
}
