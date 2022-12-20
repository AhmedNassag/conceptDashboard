<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientIncome;
use App\Models\PeriodicMaintenance;
use App\Traits\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PeriodicMaintenanceController extends Controller
{
    use Message;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $periodicMaintenances = PeriodicMaintenance::with('order.user')
        ->when($request->search, function ($q) use ($request) {
            return $q->where('quantity', 'like', '%' . $request->search . '%')
            ->orWhere('name','like','%'.$request->search.'%')
            ->orWhere('price','like','%'.$request->search.'%')
            ->orWhere('collector','like','%'.$request->search.'%')
            ->orWhere('next_maintenance','like','%'.$request->search.'%')
            ->orWhere('note','like','%'.$request->search.'%')
            ->orWhereRelation('order','id','like','%'.$request->search.'%');
        })
        ->latest()->paginate(10);

        return $this->sendResponse(['periodicMaintenances' => $periodicMaintenances], 'Data exited successfully');
    }


    public function activationPeriodic($id)
    {
        $periodicMaintenance = PeriodicMaintenance::find($id);

        if ($periodicMaintenance->status == 1)
        {
            $periodicMaintenance->update([
                "status" => 0
            ]);
        }else{
            $periodicMaintenance->update([
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
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'quantity' => 'required',
                'price' => 'required',
                'next_maintenance' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','quantity','price','next_maintenance']);

            $periodicMaintenance = PeriodicMaintenance::create($data);

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

            $periodicMaintenance = PeriodicMaintenance::find($id);
            return $this->sendResponse(['periodicMaintenance' => $periodicMaintenance], 'Data exited successfully');

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

            $periodicMaintenance = PeriodicMaintenance::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'quantity' => 'required',
                'price' => 'required',
                'next_maintenance' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','quantity','price','next_maintenance','note']);

            $periodicMaintenance->update($data);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $periodicMaintenance = PeriodicMaintenance::find($id);
            if ($periodicMaintenance) {

                $periodicMaintenance->delete();
                return $this->sendResponse([], 'Deleted successfully');
            } else {
                return $this->sendError('ID is not exist');
            }

        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }


    public function nearPeriodic(Request $request)
    {
        $periodicMaintenances = PeriodicMaintenance::where('status',1)
            ->where('next_maintenance','<=',Carbon::now()->addDays(2))
            ->when($request->search, function ($q) use ($request) {
                return $q->where('quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('name','like','%'.$request->search.'%')
                    ->orWhere('price','like','%'.$request->search.'%')
                    ->orWhere('next_maintenance','like','%'.$request->search.'%');
            })
            ->latest()->paginate(10);

        return $this->sendResponse(['periodicMaintenances' => $periodicMaintenances], 'Data exited successfully');
    }


    public function confirmPeriodic(Request $request, $id)
    {
        DB::beginTransaction();
//        try {

            $periodicMaintenance = PeriodicMaintenance::find($id);

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required','string'],
                'quantity' => 'required',
                'price' => 'required',
                'collector' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['name','quantity','price','collector']);
            $data['next_maintenance'] = now()->addDays($periodicMaintenance->order->orderDetails[0]->product->maintenance->period);
            $periodicMaintenance->update($data);


        //earn the maintenance money to the treasure
        $supplierAccount = ClientIncome::create([
            'treasury_id' => 1,
            'client_id' => $periodicMaintenance->order->user_id,
            'income_id' => 5,
            'amount' => $request->collector,
            'payment_date' => now(),
            'user_id' => auth()->id(),
        ]);
        $supplierAccount->clientAccount()->create([
            'user_id' => $periodicMaintenance->order->user_id,
            'amount' => $request->collector,
        ]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

//        }catch (\Exception $e){
//
//            DB::rollBack();
//            return $this->sendError('An error occurred in the system');
//        }
    }

}
