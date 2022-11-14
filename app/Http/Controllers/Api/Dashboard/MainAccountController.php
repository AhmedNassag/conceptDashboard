<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MainAccount;
use App\Models\SubAccount;
use App\Traits\AccountTrait;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MainAccountController extends Controller
{
    use Message;
    use AccountTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mains = MainAccount::get();

        return $this->sendResponse(['mains' => $mains], 'Data exited successfully');
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
                'name' => ['required', Rule::unique('sub_accounts', 'name')],
                'main_account_id' => 'required|integer|exists:main_accounts,id',
                'debit' => 'required'
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','main_account_id','debit']);

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

            $income = SubAccount::find($id);

            return $this->sendResponse(['income' => $income], 'Data exited successfully');

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
        $subAccounts = SubAccount::where('main_account_id',$id)->whereNull('sub_account_id')
            ->withCount('children')
            ->with('mainAccount')->when($request->search, function ($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%');
            })->latest()->paginate(5);

        foreach ($subAccounts as $index=>$subAccount)
        {
            $subAccounts[$index]['debit_amount'] = $this->debitAmount($subAccount);
            $subAccounts[$index]['credit_amount'] = $this->creditAmount($subAccount);
        }

        return $this->sendResponse(['subAccounts' => $subAccounts], 'Data exited successfully');
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
            $subAccount = SubAccount::find($id);

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('sub_accounts', 'name')->whereNot('id', $id)],
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
    public function destroy(MainAccount $mainAccount)
    {
        //
    }
}
