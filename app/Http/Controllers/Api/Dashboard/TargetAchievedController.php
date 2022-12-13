<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\TargetAchieved;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TargetAchievedController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
            ->whereHas('job',function ($q){
                $q->where('Allow_adding_to_sales_team',1);
            })->whereRelation('sellerCategories','seller_categories.id',$request->id)->get();

        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
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
                'amount' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'count' => 'numeric',
                'date' => 'required',
                'seller_category_id' => 'required|integer|exists:seller_categories,id',
                'employee_id' => 'required|integer|exists:employees,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            TargetAchieved::create($request->all());

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
            $target = TargetAchieved::find($id);
            $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
                ->whereHas('job',function ($q){
                    $q->where('Allow_adding_to_sales_team',1);
                })->whereRelation('sellerCategories','seller_categories.id',$target->seller_category_id)->get();

            return $this->sendResponse(['target' => $target,'employees'=>$employees], 'Data exited successfully');

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
        $target = TargetAchieved::where('seller_category_id',$id)->with('sellerCategory','employee.user')->when($request->search, function ($q) use ($request) {
            return $q->Where('amount','like','%'.$request->search.'%')
            ->orWhere('count','like','%'.$request->search.'%')
            ->orWhereRelation('employee.user', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(15);

        return $this->sendResponse(['target' => $target], 'Data exited successfully');
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
            $target = TargetAchieved::find($id);
            // Validator request
            $v = Validator::make($request->all(), [
                'amount' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'count' => 'numeric',
                'date' => 'required',
                'seller_category_id' => 'required|integer|exists:seller_categories,id',
                'employee_id' => 'required|integer|exists:employees,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $target->update($request->all());

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
    public function destroy($id)
    {
        $target = TargetAchieved::find($id);
        $target->delete();
        return $this->sendResponse([], 'Data exited successfully');
    }
}
