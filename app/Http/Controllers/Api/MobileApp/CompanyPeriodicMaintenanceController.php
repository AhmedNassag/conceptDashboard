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

class CompanyPeriodicMaintenanceController extends Controller
{
    use Message;
    public $selling_method;

    public function __construct()
    {
        $this->selling_method = 2;
    }

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
                'product_id' => 'required',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors(), 401);
            }

            // $data = $request->only(['name', 'quantity', 'price', 'next_maintenance']);
            // $periodicMaintenance = PeriodicMaintenance::create($data);

            $price = FilterWax::where('id',$request->product_id)->first();
            $periodicMaintenance = PeriodicMaintenance::create([
                'user_id' =>auth()->user()->id,
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




    public function filterWaxes()
    {
        try {
            $filterWaxes = FilterWax::get();
            return $this->sendResponse(['products' => $filterWaxes], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
