<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TransferringTreasury;
use App\Models\Treasury;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransferringTreasuryController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $treasuries = TransferringTreasury::with('user','fromTreasury','toTreasury')->when($request->search, function ($q) use ($request) {
            return $q->whereRelation('fromTreasury', 'name', 'like', '%' . $request->search . '%')
                ->orWhereRelation('toTreasury', 'name', 'like', '%' . $request->search . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%');
        })->paginate(5);

        return $this->sendResponse(['treasuries' => $treasuries], 'Data exited successfully');
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
                'from_treasury_id' => 'required|integer|exists:treasuries,id',
                'to_treasury_id' => 'required|integer|exists:treasuries,id',
                'amount' => 'required',
                'notes' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['user_id','from_treasury_id','to_treasury_id','amount','notes']);
            $data['user_id']=auth()->user()->id;
            TransferringTreasury::create($data);

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

            $treasury = TransferringTreasury::find($id);
            $treasuries = Treasury::where('active', 1)->get();

            return $this->sendResponse(['treasury' => $treasury,'treasuries'=>$treasuries], 'Data exited successfully');

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
    public function update(Request $request, TransferringTreasury $transferringTreasury)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'from_treasury_id' => 'required|integer|exists:treasuries,id',
                'to_treasury_id' => 'required|integer|exists:treasuries,id',
                'amount' => 'required',
                'notes' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['user_id','from_treasury_id','to_treasury_id','amount','notes']);
            $data['user_id']=auth()->user()->id;
            $transferringTreasury->update($data);

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
    public function destroy(TransferringTreasury $transferringTreasury)
    {
        $transferringTreasury->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
