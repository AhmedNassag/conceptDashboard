<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\problemLead;
use App\Models\problemSellerCategory;
use App\Models\User;
use App\Notifications\Admin\AddLeadNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class problemSalesLeadController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $targetPlan = auth()->user()->employee->problemsellerCategories;

        return $this->sendResponse(['targetPlan' => $targetPlan], 'Data exited successfully');
    }

    public function getTenLead($id)
    {
        $employee_id = auth()->user()->employee->id;
        $leads = problemLead::where([
            ['seller_category_id',$id],
            ['employee_id',null],
        ])->inRandomOrder()->limit(10)->get();
        if (count($leads) == 0){
            return $this->sendError('There are no customers without sales staff at the moment');
        }
        foreach ($leads as $lead)
        {
            $lead->update([
                "employee_id" => $employee_id
            ]);
        }

        return $this->sendResponse(['leads' => $leads], 'Data exited successfully');
    }

    public function create()
    {
        //

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
                'name' => 'required|string|min:3',
                'address' => 'nullable|string|min:3',
                'email' => 'nullable|string|email|unique:problem_leads,email',
                'phone' => 'required|string|unique:problem_leads,phone',
                'seller_category_id' => 'required|integer|exists:problem_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','email','phone','seller_category_id']);
            $data['employee_id'] = auth()->user()->employee->id;

            $lead = problemLead::create($data);

            User::whereAuthId(1)
                ->whereRelation('roles.notify','name','Add Lead BY Sales Staff');
                // ->each(function ($admin) use($lead){
                //     $admin->notify(new AddLeadNotification($lead));
                // });

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function edit($id)
    {
        try {

            $lead = problemLead::find($id);

            return $this->sendResponse(['lead' => $lead], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $leads = problemLead::where([
            ['seller_category_id',$id],
            ['employee_id',auth()->user()->employee->id],
        ])->with('sellerCategory')->when($request->search, function ($q) use ($request) {
            return $q->OrWhere('name','like','%'.$request->search.'%')
                ->OrWhere('phone','like','%'.$request->search.'%')
                ->OrWhere('email','like','%'.$request->search.'%')
                ->OrWhere('address','like','%'.$request->search.'%')
                ->orWhereRelation('employee.user','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        return $this->sendResponse(['leads' => $leads], 'Data exited successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        try
        {
            $lead = problemLead::find($id);
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'address' => 'nullable|string|min:3',
                'email' => 'nullable|string|email|unique:problem_leads,email' . ($lead->id ? ",$lead->id" : ''),
                'phone' => 'required|string|unique:problem_leads,phone' . ($lead->id ? ",$lead->id" : ''),
                'seller_category_id' => 'required|integer|exists:problem_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','email','phone','seller_category_id']);
            $data['employee_id'] = auth()->user()->employee->id;
            $lead->update($data);

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lead = problemLead::find($id);
        $lead->delete();
        return $this->sendResponse([], 'Data exited successfully');
    }
}
