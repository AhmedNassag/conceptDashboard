<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\FilterWax;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\PeriodicMaintenance;
use App\Models\Product;
use App\Models\User;
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
    {+
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
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'quantity' => 'required',
                'next_maintenance' => 'required',
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users',
                'address' => 'required|string|min:5|max:255',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $code = mt_rand(1000000000, 9999999999);
            // start create user
            $user = User::create([
                "name" => $request->name,
                "auth_id" => 2,
                "role_name" => ['client'],
                "status" => 1,
                "phone" => $request->phone,
                "code" => '+2',
                "user_code" => $code,
                "password" => bcrypt($code),
            ]);
            $user->complement()->create([
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
                'selling_method_id' => 1,
                'device' => 2
            ]);
            $user->client()->create(['address' => $request->address, 'province_id' => $request->province_id, 'area_id' => $request->area_id]);
            $user->clientAccounts()->create([
                'amount' => $request->amount ? $request->amount : 0
            ]);


            $price = FilterWax::where('id', $request->wax_id)->first();
            $periodicMaintenance = PeriodicMaintenance::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $price->name,
                'next_maintenance' => $request->next_maintenance,
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }




    public function oldPeriodicMaintenance(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'quantity' => 'required',
                'next_maintenance' => 'required',
                'wax_id' => 'required|exists:filter_waxes,id'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $price = FilterWax::where('id', $request->wax_id)->first();
            $periodicMaintenance = PeriodicMaintenance::create([
                'user_id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'quantity' => $request->quantity,
                'price' => $price->name,
                'next_maintenance' => $request->next_maintenance,
            ]);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
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
        DB::beginTransaction();
        try {
            $periodicMaintenance = PeriodicMaintenance::where('user_id',auth()->user()->id)->orderBy('next_maintenance','asc')->first();

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
        $periodicMaintenance = PeriodicMaintenance::where('user_id', auth()->user()->id)->where('status',0)->where('next_maintenance','>',now())->orderBy('next_maintenance', 'asc')->first();
        if($periodicMaintenance)
        {
            $price = Product::where('name', $periodicMaintenance->price)/*->with('latestPrice')*/->first();
            if($price)
            {
                return $this->sendResponse(['next_maintenance' => $periodicMaintenance->next_maintenance, 'price' => $price/*->latestPrice*/->price ? $price/*->latestPrice*/->price : '0'], trans('message.messageSuccessfully'));
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



    public function clientFilters()
    {
        $filters = Order::with(['orderDetails.product.filterWax'])
        ->where([
            'user_id' => auth()->user()->id,
            'is_online' => 1,
            'order_status_id' => 5
        ])
        ->get();
        if ($filters) {
            return $this->sendResponse(['filters' => $filters], trans('message.messageSuccessfully'));
        }
        return $this->sendResponse(['filters' => []], trans('message.messageSuccessfully'));
    }



    public function filterWaxes()
    {
        try {
            $filterWaxes = FilterWax::get();
            return $this->sendResponse(['filterWaxes' => $filterWaxes], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
