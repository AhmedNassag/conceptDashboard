<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PointWelcome;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointWelcomeController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pointWelcomes = PointWelcome::when($request->search,function ($q) use ($request){
            return $q->where('points','like',"%". $request->search ."%")
            ->orWhere('start_date', 'like', '%' . $request->search . '%')
            ->orWhere('expire_date', 'like', '%' . $request->search . '%');
        })
        ->latest('id','ASC')
        ->paginate(10);
        return $this->sendResponse(['pointWelcomes' => $pointWelcomes], 'Data exited successfully');
    }



    public function activationPointWelcome($id)
    {
        $pointWelcome = PointWelcome::find($id);
        if ($pointWelcome->status == 1)
        {
            $pointWelcome->update([
                "status" => 0
            ]);
        }
        else
        {
            $pointWelcome->update([
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
                'points'      => 'required',
                'start_date'  => 'required|date_format:Y-m-d',
                'expire_date' => 'required_with:start_date|date_format:Y-m-d',
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['points','start_date','expire_date']);
            PointWelcome::create($data);
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
            $pointWelcome = PointWelcome::find($id);
            return $this->sendResponse(['pointWelcome' => $pointWelcome], 'Data exited successfully');
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
            $pointWelcome = PointWelcome::find($id);
            // Validator request
            $v = Validator::make($request->all(), [
                'points'      => 'required',
                'start_date'  => 'required|date_format:Y-m-d',
                'expire_date' => 'required_with:start_date|date_format:Y-m-d',
            ]);
            if ($v->fails())
            {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['points','start_date','expire_date']);
            $pointWelcome->update($data);
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
            $pointWelcome = PointWelcome::find($id);
            if ($pointWelcome)
            {
                $pointWelcome->delete();
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
