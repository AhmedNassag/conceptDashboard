<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PointPrice;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointPriceController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pointPrices = PointPrice::when($request->search,function ($q) use ($request){
            return $q->where('value','like',"%". $request->search ."%")
            ->orWhere('expire_date', 'like', '%' . $request->search . '%');
        })
        ->latest('id','ASC')
        ->paginate(10);
        return $this->sendResponse(['pointPrices' => $pointPrices], 'Data exited successfully');
    }



    public function activationPointPrice($id)
    {
        $pointPrice = PointPrice::find($id);
        if ($pointPrice->status == 1)
        {
            $pointPrice->update([
                "status" => 0
            ]);
        }
        else
        {
            $pointPrice->update([
                "status" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            // Validator request
            $v = Validator::make($request->all(), [
                'value'       => 'required',
                'expire_date' => 'required|date_format:Y-m-d',
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            // $data = $request->only(['value','expire_date']);
            PointPrice::create([
                'value'       => $request->value,
                'expire_date' => $request->expire_date,
                'updated_at'  => date("Y-m-d H:i:s"),

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



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try
        {
            $pointPrice = PointPrice::find($id);
            return $this->sendResponse(['pointPrice' => $pointPrice], 'Data exited successfully');
        }
        catch (\Exception $e)
        {
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
        try
        {
            $pointPrice = PointPrice::find($id);
            // Validator request
            $v = Validator::make($request->all(), [
                'value'       => 'required',
                'expire_date' => 'required|date_format:Y-m-d',
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['value','expire_date']);
            $pointPrice->update($data);
            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
            $pointPrice = PointPrice::find($id);
            if ($pointPrice)
            {
                $pointPrice->delete();
                return $this->sendResponse([],'Deleted successfully');
            }
            else
            {
                return $this->sendError('ID is not exist');
            }
        }
        catch (\Exception $e)
        {
            return $this->sendError('An error occurred in the system');
        }
    }
}
