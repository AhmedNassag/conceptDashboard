<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\problemLead;
use App\Models\problemSellerCategory;
use App\Models\User;
use App\Notifications\Admin\ChangeEmployeeNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class problemLeadController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leads = problemLead::with(['employee.user','sellerCategory','comments.employee.user'])->when($request->search, function ($q) use ($request) {
            return $q->where('name','like','%'.$request->search.'%')
            ->orWhere('phone','like','%'.$request->search.'%')
            ->orWhere('description','like','%'.$request->search.'%')
            ->orWhere('address','like','%'.$request->search.'%')
            ->orWhereRelation('employee.user','name','like','%'.$request->search.'%')
            ->orWhereRelation('sellerCategory','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        return $this->sendResponse(['leads' => $leads], 'Data exited successfully');
    }

    public function changeEmployeeLead($id)
    {
        $lead = problemLead::find($id);

//        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
//        ->whereHas('job',function ($q){
//            $q->where('Allow_adding_to_sales_team',1);
//        })->whereRelation('problemsellerCategories','seller_categories.id',$lead->seller_category_id)->get();
        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
            ->whereHas('job',function ($q){
                $q->where('Allow_adding_to_sales_team',1);
            })->whereHas('problemsellerCategories')->get();

        return $this->sendResponse(['employees' => $employees,'lead'=>$lead], 'Data exited successfully');
    }

    public function updateEmployeeLead(Request $request,$id)
    {
        try {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(), [
                'employee_id' => 'required|integer|exists:employees,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['employee_id']);
            $lead = problemLead::find($id);
            $lead->update($data);

            $user=Employee::find($request->employee_id)->user;
            // $user->notify(new ChangeEmployeeNotification($lead->seller_category_id));

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }


    public function create()
    {
        try {

            $categories = problemSellerCategory::all();

            return $this->sendResponse(['categories'=>$categories],'Data exited successfully');

        }catch (\Exception $e){

            return $this->sendError('An error occurred in the system');

        }

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
                'description' => 'nullable|string|min:3',
                'phone' => 'required|string',
                'seller_category_id' => 'required|integer|exists:problem_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','description','phone','seller_category_id']);

            problemLead::create($data);

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

            $categories = problemSellerCategory::all();

            return $this->sendResponse(['lead' => $lead, 'categories' => $categories], 'Data exited successfully');

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
    public function show($id)
    {
        //
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
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'address' => 'nullable|string|min:3',
                'description' => 'nullable|string|min:3',
                'phone' => 'required|string',
                'seller_category_id' => 'required|integer|exists:problem_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','description','phone','seller_category_id']);

            $lead->update($data);

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(problemLead $lead)
    {
        if ($lead->employee)
        {
            return $this->sendError('can not delete');
        }

        $lead->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
