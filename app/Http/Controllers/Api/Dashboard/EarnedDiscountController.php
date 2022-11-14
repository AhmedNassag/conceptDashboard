<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\EarnedDiscount;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EarnedDiscountController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $discounts = EarnedDiscount::with(['company:id,name'])
            ->when($request->search,function ($q) use ($request){
                return $q->where('discount','like',"%". $request->search ."%")
                    ->orWhere('note','like',"%". $request->search ."%")
                    ->orWhere('to_date','like',"%". $request->search ."%")
                    ->orWhereRelation('company','name','like','%'.$request->search.'%');
            })->latest()->paginate(10);

        return $this->sendResponse(['discounts' => $discounts], 'Data exited successfully');
    }

    public function create()
    {
        try {
            $companies = Company::where('status',1)->select('id','name')->get();
            return $this->sendResponse([
                'companies' => $companies
            ], 'Data exited successfully');
        } catch (\Exception $e) {
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
                'company_id' => 'required|integer|exists:companies,id',
                'purchase_id' => 'nullable|integer|exists:purchases,id',
                'note' => 'required|string',
                'discount' => 'required|numeric',
                'to_date' => 'required|date'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            EarnedDiscount::create($request->all());

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
            $companies = Company::where('status',1)->select('id','name')->get();
            $earnedDiscount = EarnedDiscount::find($id);
            return $this->sendResponse([
                'companies' => $companies,
                'earnedDiscount' => $earnedDiscount
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

            $earnedDiscount = EarnedDiscount::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'company_id' => 'required|integer|exists:companies,id',
                'purchase_id' => 'nullable|integer|exists:purchases,id',
                'note' => 'required|string',
                'discount' => 'required|numeric',
                'to_date' => 'required|date'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $earnedDiscount->update($request->all());

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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $earnedDiscount = EarnedDiscount::find($id);
        $earnedDiscount->delete();
        return $this->sendResponse([],'Deleted successfully');
    }
}
