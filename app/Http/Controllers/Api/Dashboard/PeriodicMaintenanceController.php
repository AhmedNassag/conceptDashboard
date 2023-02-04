<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\ClientIncome;
use App\Models\Employee;
use App\Models\FilterWax;
use App\Models\KnowledgeWay;
use App\Models\PeriodicMaintenance;
use App\Models\Province;
use App\Models\User;
use App\Traits\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PeriodicMaintenanceController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $periodicMaintenances = PeriodicMaintenance::with(['user.client.area','order.user','employee.user'])
        ->when($request->search, function ($q) use ($request) {
            return $q->where('quantity', 'like', '%' . $request->search . '%')
            ->orWhere('name','like','%'.$request->search.'%')
            ->orWhere('price','like','%'.$request->search.'%')
            ->orWhere('collector','like','%'.$request->search.'%')
            ->orWhere('next_maintenance','like','%'.$request->search.'%')
            ->orWhere('note','like','%'.$request->search.'%')
            ->orWhereRelation('order','id','like','%'.$request->search.'%')
            ->orWhereRelation('user.client.area','name','like','%'.$request->search.'%')
            ->orWhereRelation('employee.user', 'name', 'like', '%' . $request->search . '%');
        })
        ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('next_maintenance', ">=", $request->from_date)
                ->whereDate('next_maintenance', "<=", $request->to_date);
            });
        })
        ->where(function ($q) use ($request) {
            $q->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        })
        ->orderBy('next_maintenance','asc')->latest()->paginate(10);

        return $periodicMaintenances;
        return $this->sendResponse([
            'periodicMaintenances' => $periodicMaintenances,
        ], 'Data exited successfully');
    }


    public function activationPeriodic($id)
    {
        $periodicMaintenance = PeriodicMaintenance::find($id);

        if ($periodicMaintenance->status == 1)
        {
            $periodicMaintenance->update([
                "status" => 0
            ]);
        }else{
            $periodicMaintenance->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }



    public function create()
    {
        try
        {
            $provinces = Province::select('id', 'name')->get();
            $waxes = FilterWax::select('id', 'name')->get();
            return $this->sendResponse([
                'provinces' => $provinces,
                'waxes' => $waxes,
            ], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
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
                'name' => ['required','string'],
                'quantity' => 'required',
                'next_maintenance' => 'required',
                //
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users',
                'address' => 'required|string|min:5|max:255',
                //
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $code = mt_rand(1000000000, 9999999999);
            // start create user
            $user = User::create([
                "name" => $request->name,
                "auth_id" => 2,
                "role_name"=> ['client'],
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


            $price = FilterWax::where('id',$request->wax_id)->first();
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



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $periodicMaintenance = PeriodicMaintenance::find($id);
            $user = User::with('client')->find($periodicMaintenance->user_id);
            $provinces = Province::select('id', 'name')->get();
            $areas = Area::select('id', 'name')->get();
            $waxes = FilterWax::select('id', 'name')->get();
            return $this->sendResponse([
                'periodicMaintenance' => $periodicMaintenance,
                'user' => $user,
                'provinces' => $provinces,
                'areas' => $areas,
                'waxes' => $waxes,
            ], 'Data exited successfully');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $periodicMaintenance = PeriodicMaintenance::find($id);
            $user = User::with('client')->find($periodicMaintenance->user_id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'quantity' => 'required',
                'next_maintenance' => 'required',
                //
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users,phone,'.$user->id,
                //
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','quantity','next_maintenance','note']);
            $periodicMaintenance->update($data);

            // start update user
            $user->update([
                "name" => $request->name,
                "password" => $request->password,
                'phone' => $request->phone,
            ]);
            $user->complement()->update([
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
                'selling_method_id' => 1,
            ]);
            $user->client()->update([
                'address' => $request->address,
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
            ]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        }catch (\Exception $e){

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
        try {
            $periodicMaintenance = PeriodicMaintenance::find($id);
            if ($periodicMaintenance) {

                $periodicMaintenance->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }



    public function nearPeriodic(Request $request)
    {
        $periodicMaintenances = PeriodicMaintenance::with(['user.client.area','order.user','employee.user'])
        ->where('status',0)
        ->where('next_maintenance', '<', Carbon::now()->addDays(1))
        /*->where('next_maintenance', '>', Carbon::now()->subDays(1))*/
        ->when($request->search, function ($q) use ($request) {
            return $q->where('quantity', 'like', '%' . $request->search . '%')
            ->orWhere('name', 'like', '%' . $request->search.'%')
            ->orWhere('price', 'like', '%' . $request->search.'%')
            ->orWhere('next_maintenance', 'like', '%' . $request->search.'%')
            ->orWhereRelation('user.client.area','name','like','%'.$request->search.'%');
        })
        ->orderBy('name','Asc')->latest()->paginate(10);

        return $this->sendResponse([
            'periodicMaintenances' => $periodicMaintenances,
        ], 'Data exited successfully');
    }



    public function confirmPeriodic(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $periodicMaintenance = PeriodicMaintenance::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                // 'name' => ['required','string'],
                // 'quantity' => 'required',
                // 'price' => 'required',
                // 'collector' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }


            $period = FilterWax::where('name', $periodicMaintenance->price)->first();
            $data = now()->addDays($period->period);
            $periodicMaintenance->update([
                'status' => 1,
            ]);

            $newPeriodicMaintenance = PeriodicMaintenance::create([
                'order_id'         => $periodicMaintenance->order_id ? : Null,
                'user_id'          => $periodicMaintenance->user_id,
                'name'             => $periodicMaintenance->name,
                'quantity'         => $periodicMaintenance->quantity,
                'price'            => $periodicMaintenance->price,
                'next_maintenance' => $data
            ]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        }catch (\Exception $e){
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function changeEmployeeLead($id)
    {
        $lead = PeriodicMaintenance::find($id);
        $employees = Employee::with('user:id,name')->whereRelation('user', 'status', 1)
        ->whereHas('job', function ($q) {
            $q->where('Allow_adding_to_sales_team', 1);
        })->get();

        return $this->sendResponse(['employees' => $employees, 'lead' => $lead], 'Data exited successfully');
    }


    public function updateEmployeeLead(Request $request, $id)
    {
        try
        {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(), [
                'employee_id' => 'required|integer|exists:employees,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['employee_id']);
            $lead = PeriodicMaintenance::find($id);
            $lead->update($data);

            $user = Employee::find($request->employee_id)->user;
            // $user->notify(new ChangeEmployeeNotification($lead->seller_category_id));

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

}
