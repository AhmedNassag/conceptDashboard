<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Lead;
use App\Models\Province;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{

    use Message;

    public function leadClientGet(){
        // get user
        $leads = Lead::whereStatus(false)
            ->whereEmployeeId(auth()->user()->employee->id)
            ->paginate(10);

        return $this->sendResponse(['leadClient' => $leads],'Data exited successfully');
    }

    public function leadClient(){
        // get user
        $leads = Lead::with(['province:id,name','area:id,name'])
        ->whereNull('employee_id')
        ->limit(10)
        ->get();

        foreach ($leads as $lead){
            $lead->update(['employee_id' => auth()->user()->employee->id]);
        }

        return $this->sendResponse([],'Data exited successfully');
    }

    public function index(Request $request)
    {
        // get user
        $leads = Lead::
        with(['province:id,name','area:id,name','leadClient'])->
        when($request->search, function ($q) use ($request) {
            return $q->OrWhere('name', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        })->paginate(15);

        return $this->sendResponse(['leads' => $leads],'Data exited successfully');
    }

    public function create(){
        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)->whereHas('job',function ($q){
            $q->where('Allow_adding_to_sales_team',1);
        })->get();

        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validator request
        $v = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|in:merchant,client,company',
            'province_id'  => 'required|integer|exists:provinces,id',
            'area_id'  => 'required|integer|exists:areas,id',
            'phone' => 'required|string|unique:leads,phone',
            'address' => 'required|string|min:8|max:255',
            'employee_id' => 'nullable'
        ]);

        if($v->fails()) {
            return $this->sendError('There is an error in the data',$v->errors());
        }

        // start create user
        Lead::create([
            "name" => $request->name,
            "province_id" => $request->province_id,
            "area_id" => $request->area_id,
            'phone' => $request->phone,
            'address' => $request->address,
            "type" => $request->type,
            "employee_id" => $request->employee_id
        ]);

        return $this->sendResponse([],'Data exited successfully');

    }


    public function storeClient(Request $request)
    {
        // Validator request
        $v = Validator::make($request->all(), [
            'name' => 'required|string',
            'type' => 'required|in:merchant,client,company',
            'province_id'  => 'required|integer|exists:provinces,id',
            'area_id'  => 'required|integer|exists:areas,id',
            'phone' => 'required|string|unique:leads,phone',
            'address' => 'required|string|min:8|max:255',
        ]);

        if($v->fails()) {
            return $this->sendError('There is an error in the data',$v->errors());
        }

        // start create user
        Lead::create([
            "name" => $request->name,
            "province_id" => $request->province_id,
            "area_id" => $request->area_id,
            'phone' => $request->phone,
            'address' => $request->address,
            "type" => $request->type,
            "employee_id" => auth()->user()->employee->id
        ]);

        return $this->sendResponse([],'Data exited successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lead =  Lead::find($id);
        $provinces = Province::select('id','name')->get();

        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)->whereHas('job',function ($q){
            $q->where('Allow_adding_to_sales_team',1);
        })->get();

        return $this->sendResponse([
            'lead' => $lead,
            'provinces' => $provinces,
            'employees' => $employees
        ],'Data exited successfully');
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
        $user = Lead::find($id);
        if($user){
            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'type' => 'required|in:merchant,client,company',
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:leads,phone,'.$user->id,
                'address' => 'required|string|min:8|max:255',
                'employee_id' => 'nullable'
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user->update([
                "name" => $request->name,
                "province_id" => $request->province_id,
                "area_id" => $request->area_id,
                'phone' => $request->phone,
                'address' => $request->address,
                "type" => $request->type,
                "employee_id" => $request->employee_id
            ]);

            return $this->sendResponse([],'Data exited successfully');

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
            $lead = Lead::find($id);
            if ($lead) {

                $lead->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }
}
