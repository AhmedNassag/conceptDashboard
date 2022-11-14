<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyRestriction;
use App\Models\Restriction;
use App\Models\RestrictionRecord;
use App\Models\SubAccount;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DailyRestrictionController extends Controller
{
    use Message;

    public function index(Request $request){
        $dailies = DailyRestriction::with('restriction','restrictionRecord')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('desc', 'like', '%' . $request->search . '%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('date', ">=", $request->from_date)
                        ->whereDate('date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->id, function ($q) use ($request) {
                    $q->where('id', $request->id);
                });
            })->latest()->paginate(5);

        $debit = $dailies->sum('debits');
        $creditor = $dailies->sum('creditor');

        return $this->sendResponse(['dailies' => $dailies,'debit'=>$debit,'creditor'=>$creditor], 'Data exited successfully');
    }

    public function subAccount($data){
        $subAccount= [];
        foreach ($data as $account){
            if (count($account->children) > 0){
                foreach ($account->children as $one) {
                    if (count($one->children) > 0) {
                        foreach ($one->children as $two) {
                            if (count($two->children) > 0) {
                                foreach ($two->children as $three) {
                                    if (count($three->children) > 0) {
                                        foreach ($three->children as $four) {

                                            $subAccount[]=$four;
                                        }
                                    }else{
                                        $subAccount[]=$three;
                                    }

                                }
                            }else{
                                $subAccount[]=$two;
                            }
                        }
                    }else{
                        $subAccount[]=$one;
                    }
                }
            }else{
                $subAccount[]=$account;
            }

        }

        return $subAccount;
    }

    public function create(){

        $accounts = SubAccount::where('sub_account_id', null)->get();
        $subAccount = $this->subAccount($accounts);

        return $this->sendResponse(['subAccount' => $subAccount], 'Data exited successfully');
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'date' => 'required|date',
                'desc' => 'required|string|min:3',
                'debit' => 'required|array',
                'creditors' => 'required|array',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $dailyRestriction = DailyRestriction::create([
                'date' => $request->date,
                'desc' => $request->desc
            ]);

            foreach ($request->debit as $value){
                Restriction::create([
                    "description" => $value['description'],
                    "amount" => $value['amount'],
                    "sub_account_id" => $value['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    "debit" => 1,
                ]);
                RestrictionRecord::create([
                    "description" => $value['description'],
                    "amount" => $value['amount'],
                    "sub_account_id" => $value['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    "debit" => 1,
                    "is_add" => 1,
                    'date' => $request->date,
                    "user_id" => auth()->user()->id
                ]);
            }

            foreach ($request->creditors as $item){
                Restriction::create([
                    "description" => $item['description'],
                    "amount" => $item['amount'],
                    "sub_account_id" => $item['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                ]);

                RestrictionRecord::create([
                    "description" => $item['description'],
                    "amount" => $item['amount'],
                    "sub_account_id" => $item['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    "is_add" => 1,
                    'date' => $request->date,
                    "user_id" => auth()->user()->id
                ]);
            }

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

            $dailies = DailyRestriction::with('restriction')->find($id)->makeVisible('translations');
            $accounts = SubAccount::where('sub_account_id', null)->get();
            $subAccount = $this->subAccount($accounts);

            return $this->sendResponse(['dailies' => $dailies,'subAccount' => $subAccount], 'Data exited successfully');

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
    public function update(Request $request, DailyRestriction $dailyRestriction)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'date' => 'required|date',
                'desc' => 'required|string|min:3',
                'debit' => 'required|array',
                'creditors' => 'required|array',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $dailyRestriction->update([
                'date' => $request->date,
                'desc' => $request->desc
            ]);

            foreach ($dailyRestriction->restriction as $data){
                $data->delete();
            }

            foreach ($request->debit as $value){

                Restriction::create([
                    "description" => $value['description'],
                    "amount" => $value['amount'],
                    "sub_account_id" => $value['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    "debit" => 1,
                ]);

                RestrictionRecord::create([
                    "description" => $value['description'],
                    "amount" => $value['amount'],
                    "sub_account_id" => $value['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    "debit" => 1,
                    'date' => $request->date,
                    "user_id" => auth()->user()->id
                ]);
            }

            foreach ($request->creditors as $item){

                Restriction::create([
                    "description" => $item['description'],
                    "amount" => $item['amount'],
                    "sub_account_id" => $item['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                ]);

                RestrictionRecord::create([
                    "description" => $item['description'],
                    "amount" => $item['amount'],
                    "sub_account_id" => $item['sub_account_id'],
                    "daily_restriction_id" => $dailyRestriction->id,
                    'date' => $request->date,
                    "user_id" => auth()->user()->id
                ]);
            }

            DB::commit();

            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }
}
