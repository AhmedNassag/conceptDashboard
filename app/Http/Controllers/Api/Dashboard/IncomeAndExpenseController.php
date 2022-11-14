<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\IncomeAndExpense;
use App\Models\Treasury;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class IncomeAndExpenseController extends Controller
{
    use Message;

    // get calculate income
    public function calcIncome(Request $request)
    {
        $incomeAndExpense = IncomeAndExpense::with('income', 'user')->whereNotNull('income_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('income', 'name', 'like', '%' . $request->search . '%');
                });

            })->latest()->paginate(5);

        return $this->sendResponse(['calcIncome' => $incomeAndExpense], 'Data exited successfully');
    }

    // get calculate expense

    public function calcExpense(Request $request)
    {
        $incomeAndExpense = IncomeAndExpense::with('expense', 'user')->whereNotNull('expense_id')
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhere('payment_date', 'like', '%' . $request->search . '%')
                        ->orWhere('payer', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('expense', 'name', 'like', '%' . $request->search . '%');
                });

            })->latest()->paginate(5);

        return $this->sendResponse(['calcExpense' => $incomeAndExpense], 'Data exited successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                'payment_date' => 'required|date',
                'income_id' => 'nullable|exists:incomes,id',
                'expense_id' => 'nullable|exists:expenses,id',
                'notes' => 'required|string|min:3',
                'payer' => 'required|string|min:3',
                'treasury_id' => 'required|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $request_data = $request->all();
            $request_data['user_id'] = auth()->user()->id;

            IncomeAndExpense::create($request_data);

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

            $income = IncomeAndExpense::find($id);

            $mainIncome = Income::where('active', 1)->get();
            $treasuries = Treasury::where('active', 1)->get();

            return $this->sendResponse(['income' => $income, 'mainIncome' => $mainIncome,'treasuries' => $treasuries], 'Data exited successfully');

        } catch (\Exception $e) {

            return $this->sendError('An error occurred in the system');

        }
    }

    public function editExpense($id)
    {
        try {

            $expense = IncomeAndExpense::find($id);

            $mainExpense = Income::where('active', 1)->get();
            $treasuries = Treasury::where('active', 1)->get();

            return $this->sendResponse(['expense' => $expense, 'mainExpense' => $mainExpense,'treasuries' => $treasuries], 'Data exited successfully');

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
    public function update(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'payment_date' => 'required|date',
                'income_id' => 'nullable|exists:incomes,id',
                'expense_id' => 'nullable|exists:expenses,id',
                'notes' => 'required|string|min:3',
                'payer' => 'required|string|min:3',
                'treasury_id' => 'required|exists:treasuries,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $income_and_expense = IncomeAndExpense::find($id);

            $request_data = $request->all();
            $request_data['user_id'] = auth()->user()->id;

            $income_and_expense->update($request_data);

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
        $income_and_expense = IncomeAndExpense::find($id);
        $income_and_expense->delete();
        return $this->sendResponse([], 'Deleted successfully');

    }
}
