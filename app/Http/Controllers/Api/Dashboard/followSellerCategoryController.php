<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\followSellerCategory;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class followSellerCategoryController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $employees = Employee::with('user:id,name','followsellerCategories')->whereHas('followsellerCategories')
            ->where(function ($q) use($request){
                $q->when($request->search,function ($q) use($request){
                    return $q->whereRelation('user','name','like','%'.$request->search.'%')
                    ->orWhereRelation('followsellerCategories', 'name', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(5);

        return $this->sendResponse(['employees' => $employees], 'Data exited successfully');
    }

    public function create()
    {
        $employees = Employee::with('user:id,name')->whereRelation('user','status',1)
            ->whereHas('job',function ($q){
            $q->where('Allow_adding_to_sales_team',1);
        })->whereDoesntHave('followsellerCategories')->get();

        $categories = followSellerCategory::all();

        return $this->sendResponse(['employees' => $employees,'categories' => $categories], 'Data exited successfully');
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
                'seller_category_id.*' => 'required|integer|exists:follow_seller_categories,id',
                'employee_id' => 'required|integer|exists:employees,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['seller_category_id','employee_id']);

            $employee = Employee::find($request['employee_id']);

            $employee->followsellerCategories()->attach($data['seller_category_id']);

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
            $employee = Employee::with('user:id,name')->find($id);
            $categories = followSellerCategory::all();
            $targets = $employee->followsellerCategories;

            return $this->sendResponse(['employee'=>$employee,'targets' => $targets,'categories' => $categories], 'Data exited successfully');

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
       //
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
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'seller_category_id.*' => 'required|integer|exists:follow_seller_categories,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['seller_category_id']);
            $employee = Employee::find($id);

            $employee->followsellerCategories()->sync($data['seller_category_id']);

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
        $employee = Employee::find($id);

        $employee->followsellerCategories()->detach();
    }
}
