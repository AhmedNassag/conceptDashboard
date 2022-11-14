<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    use Message;

    /**
     * get active Income
     */
    public function activeExpense()
    {
        $expenses = Expense::where('active', 1)->get();
        return $this->sendResponse(['expenses' => $expenses], 'Data exited successfully');
    }

    /**
     * get main Expense
     */
    public function mainExpense()
    {
        $expenses = Expense::where([
            ['expense_id', null],
            ['active', 1],
        ])->get();
        return $this->sendResponse(['expenses' => $expenses], 'Data exited successfully');
    }

    /**
     * activation Expense
     */
    public function activationExpense($id)
    {
        $expense = Expense::find($id);

        if ($expense->active == 1)
        {
            $expense->update([
                "active" => 0
            ]);
        }else{
            $expense->update([
                "active" => 1
            ]);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $expenses = Expense::with('parent')->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);

        return $this->sendResponse(['expenses' => $expenses], 'Data exited successfully');
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
                'name' => 'required|unique:expenses,name',
                'expense_id' => 'nullable|integer|exists:expenses,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','expense_id']);

            Expense::create($data);

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
            $expense = Expense::find($id);

            $mainExpense = Expense::where([
                ['expense_id', null],
                ['active', 1],
            ])->get();

            return $this->sendResponse(['expense' => $expense, 'mainExpense' => $mainExpense], 'Data exited successfully');

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
    public function update(Request $request, Expense $expense)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:expenses,name',
                'expense_id' => 'nullable|integer|exists:expenses,id' . ($expense->id ? ",$expense->id" : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','expense_id']);
            $expense->update($data);

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
    public function destroy(Expense $expense)
    {
        if (count($expense->incomeAndExpense) > 0)
        {
            return $this->sendError('can not delete');
        }

        $expense->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
