<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\followLead;
use App\Models\followSellerCategory;
use App\Models\Order;
use App\Models\User;
use App\Notifications\Admin\ChangeEmployeeNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class followLeadController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $orders = Order::with('user.client')->where(function ($query) {
//            $query->where('order_status_id', '=', 5)
//                ->orWhere('order_status_id', '=', 7);
//        })->where('updated_at', '>=', now()->subDays(2)->toDateTimeString())
//            ->get();

        $leads = followLead::with(['employee.user','sellerCategory','comments.employee.user'])->when($request->search, function ($q) use ($request) {
            return $q->where('name','like','%'.$request->search.'%')
            ->orWhere('phone','like','%'.$request->search.'%')
            ->orWhere('email','like','%'.$request->search.'%')
            ->orWhere('address','like','%'.$request->search.'%')
            ->orWhereRelation('employee.user','name','like','%'.$request->search.'%')
            ->orWhereRelation('sellerCategory','name','like','%'.$request->search.'%');
        })->latest()->paginate(15);

        return $this->sendResponse(['leads' => $leads], 'Data exited successfully');
    }

    public function changeEmployeeLead($id)
    {
        $lead = followLead::find($id);

        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
        ->whereHas('job',function ($q){
            $q->where('Allow_adding_to_sales_team',1);
        })->whereRelation('followsellerCategories','seller_categories.id',$lead->seller_category_id)->get();

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
            $lead = followLead::find($id);
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

            $categories = followSellerCategory::all();

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
                'email' => 'nullable|string|email|unique:follow_leads,email',
                'phone' => 'required|string|unique:follow_leads,phone',
                'seller_category_id' => 'required|integer|exists:follow_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','email','phone','seller_category_id']);

            followLead::create($data);

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
            $lead = followLead::find($id);

            $categories = followSellerCategory::all();

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
    public function update(Request $request, Lead $lead)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'address' => 'nullable|string|min:3',
                'description' => 'nullable|string|email|unique:leads,email' . ($lead->id ? ",$lead->id" : ''),
                'phone' => 'required|string|unique:follow_leads,phone' . ($lead->id ? ",$lead->id" : ''),
                'seller_category_id' => 'required|integer|exists:follow_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','address','email','phone','seller_category_id']);

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
    public function destroy(followLead $lead)
    {
        if ($lead->employee)
        {
            return $this->sendError('can not delete');
        }

        $lead->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
