<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SubAccount;
use App\Traits\AccountTrait;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubAccountController extends Controller
{
    use Message;
    use AccountTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$main,$id)
    {
        $subAccounts = SubAccount::where([['main_account_id',$main],['sub_account_id',$id]])->with(['parent','mainAccount'])->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhereRelation('parent','name','like','%'.$request->search.'%')
                ->orWhereRelation('mainAccount','name','like','%'.$request->search.'%');
        })->latest('sub_accounts.id')->paginate(5);
        $subAccounts[0]['parent']['debit_amount'] = $this->debitAmount($subAccounts[0]['parent']);
        $subAccounts[0]['parent']['credit_amount'] = $this->creditAmount($subAccounts[0]['parent']);

        foreach ($subAccounts as $index=>$subAccount)
        {
            $subAccounts[$index]['debit_amount'] = $this->debitAmount($subAccount);
            $subAccounts[$index]['credit_amount'] = $this->creditAmount($subAccount);
        }

        $data = [];
        $sub = SubAccount::find($id);

        $data[]=$sub;

        $sub_account_id = $sub->sub_account_id;

        for ($i = 1;$i<=10;$i++){
            $main = SubAccount::find($sub_account_id);
            if ($main){
                array_unshift($data,$main);
                if ($main->sub_account_id == null)
                {
                    break;
                }
                $sub_account_id = $main->sub_account_id;
            }
        }

        return $this->sendResponse(['subAccounts' => $subAccounts,'data' => $data], 'Data exited successfully');
    }

    public function getMainSub($id){

        $sub = SubAccount::find($id);

        $data[]=$sub;

        $sub_account_id = $sub->sub_account_id;
        for ($i = 1;$i<=10;$i++){
            $main = SubAccount::find($sub_account_id);
            if ($main){
                array_unshift($data,$main);
                if ($main->sub_account_id == null)
                {
                    break;
                }
                $sub_account_id = $main->sub_account_id;
            }
        }

        return $this->sendResponse(['data' => $data], 'Data exited successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$main,$id)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('sub_accounts', 'name')],
                'debit' => 'required'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','main_account_id','debit']);
            $data['main_account_id'] = $main;
            $data['sub_account_id'] = $id;

            SubAccount::create($data);

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
            $sub = SubAccount::find($id);

            $data=[];

            $sub_account_id = $sub->sub_account_id;

            for ($i = 1;$i<=10;$i++){
                $main = SubAccount::find($sub_account_id);
                if ($main){
                    array_unshift($data,$main);
                    if ($main->sub_account_id == null)
                    {
                        break;
                    }
                    $sub_account_id = $main->sub_account_id;
                }
            }

            return $this->sendResponse(['income' => $sub, 'data' => $data], 'Data exited successfully');

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
    public function update(Request $request,$id)
    {
        $subAccount = SubAccount::find($id);
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('sub_accounts', 'name')->whereNot('id', $subAccount->id)],
                'debit' => 'required'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','debit']);

            $subAccount->update($data);

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
        //
    }
}
