<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Target;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TargetController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
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
                'from' => 'required|integer',
                'to' => 'required|integer',
                'amount' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'percent' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'seller_category_id' => 'required|integer|exists:seller_categories,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            Target::create($request->all());

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function edit($id)
    {
        try
        {
            $target = Target::find($id);
            return $this->sendResponse(['target' => $target], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
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
        $targetPlan = Target::where('seller_category_id',$id)->with('sellerCategory')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('from','like','%'.$request->search.'%')
            ->orWhere('to','like','%'.$request->search.'%')
            ->orWhere('amount','like','%'.$request->search.'%')
            ->orWhere('percent','like','%'.$request->search.'%')
            ->orwhereRelation('sellerCategory', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);

        return $this->sendResponse(['targetPlan' => $targetPlan], 'Data exited successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Target $target)
    {
        try
        {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'from' => 'required|integer',
                'to' => 'required|integer',
                'amount' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'percent' => 'numeric|regex:/^\d+(\.\d{1,2})?$/',
                'seller_category_id' => 'required|integer|exists:seller_categories,id',
            ]);

            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $target->update($request->all());

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
    public function destroy(Target $target)
    {
        $target->delete();
    }
}
