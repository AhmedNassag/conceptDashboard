<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CapitalOwnerAccount;
use App\Models\ClientExpense;
use App\Models\ClientIncome;
use App\Models\IncomeAndExpense;
use App\Models\SupplierExpense;
use App\Models\SupplierIncome;
use App\Models\Treasury;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TreasuryController extends Controller
{
    use Message;
    /**
     * get treasuries expense
     */
    public function treasuriesExpense(Request $request,$id)
    {
        $incomeAndExpense = IncomeAndExpense::with('expense', 'user')->whereNotNull('expense_id')->where('treasury_id',$id)
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->OrWhere('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('expense.translations', 'name', 'like', '%' . $request->search . '%');
                });

            })->latest()->paginate(5);

        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    /**
     * get client expense
     */
    public function clientExpense(Request $request,$id)
    {
        $clientExpenses = ClientExpense::where('treasury_id',$id)->with(['client','expense','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('client','name','like','%'.$request->search.'%')
                        ->orWhereRelation('expense','name','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['clientExpenses' => $clientExpenses], 'Data exited successfully');
    }

    /**
     * get supplier expense
     */
    public function supplierExpense(Request $request,$id)
    {
        $supplierExpenses = SupplierExpense::where('treasury_id',$id)->with(['supplier','expense','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                        ->orWhereRelation('expense','name','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['supplierExpenses'=>$supplierExpenses], 'Data exited successfully');
    }

    /**
     * get owner expense
     */
    public function ownerExpense(Request $request,$id)
    {
        $ownerExpenses = CapitalOwnerAccount::where('treasury_id',$id)->whereNotNull('expense_id')->with(['expense','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['ownerExpenses'=>$ownerExpenses], 'Data exited successfully');
    }

    /**
     * get treasuries income
     */
    public function treasuriesIncome(Request $request,$id)
    {
        $incomeAndExpense = IncomeAndExpense::with('income', 'user')->whereNotNull('income_id')->where('treasury_id',$id)
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhere('income', 'name', 'like', '%' . $request->search . '%');
                });

            })->latest()->paginate(15);


        return $this->sendResponse(['incomes' => $incomeAndExpense], 'Data exited successfully');
    }

    /**
     * get client income
     */
    public function clientIncomes(Request $request,$id)
    {
        $clientIncomes = ClientIncome::where('treasury_id',$id)->with(['client','income','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('client','name','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);


        return $this->sendResponse(['clientIncomes' => $clientIncomes], 'Data exited successfully');
    }

    /**
     * get supplier income
     */
    public function supplierIncomes(Request $request,$id)
    {
        $supplierIncomes = SupplierIncome::where('treasury_id',$id)->with(['supplier','income','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['supplierIncomes'=>$supplierIncomes], 'Data exited successfully');
    }

    /**
     * get owner income
     */
    public function ownerIncomes(Request $request,$id)
    {
        $ownerIncomes = CapitalOwnerAccount::where('treasury_id',$id)->whereNotNull('income_id')->with(['income','treasury','user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['ownerIncomes'=>$ownerIncomes], 'Data exited successfully');
    }

    /**
     * get active treasury
     */
    public function activeTreasury()
    {
        $treasuries = Treasury::where('active', 1)->get();
        return $this->sendResponse(['treasuries' => $treasuries], 'Data exited successfully');
    }

    /**
     * activation Income
     */
    public function activationTreasury($id)
    {
        $income = Treasury::find($id);

        if ($income->active == 1)
        {
            $income->update([
                "active" => 0
            ]);
        }else{
            $income->update([
                "active" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    /**
     * get main treasury
     */
    public function mainTreasury()
    {
        $treasuries = Treasury::where([
            ['treasury_id', null],
            ['active', 1],
        ])->get();
        return $this->sendResponse(['treasuries' => $treasuries], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $treasuries = Treasury::with('treasuryParent')->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
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
                'name' => 'required|unique:treasuries,name',
                'treasury_id' => 'nullable|integer|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','treasury_id']);

            Treasury::create($data);

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

            $treasury = Treasury::find($id)->makeVisible('translations');

            $mainTreasury = Treasury::where([
                ['treasury_id', null],
                ['active', 1],
            ])->get();

            return $this->sendResponse(['treasury' => $treasury, 'mainTreasury' => $mainTreasury], 'Data exited successfully');

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
    public function update(Request $request, Treasury $treasury)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:treasuries,name' . ($treasury->id ? ",$treasury->id" : ''),
                'treasury_id' => 'nullable|integer|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','treasury_id']);
            $treasury->update($data);

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
    public function destroy(Treasury $treasury)
    {
        if (count($treasury->incomeAndExpense) > 0)
        {
            return $this->sendError('can not delete');
        }

        $treasury->delete();
        return $this->sendResponse([],'Deleted successfully');
    }
}
