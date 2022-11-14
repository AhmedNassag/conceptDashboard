<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class IncomeController extends Controller
{
    use Message;

    /**
     * get active Income
     */
    public function activeIncome()
    {
        $incomes = Income::where('active', 1)->get();
        return $this->sendResponse(['incomes' => $incomes], 'Data exited successfully');
    }


    /**
     * get main Income
     */
    public function mainIncome()
    {
        $incomes = Income::where([
            ['income_id', null],
            ['active', 1],
        ])->get();
        return $this->sendResponse(['incomes' => $incomes], 'Data exited successfully');
    }

    /**
     * activation Income
     */
    public function activationIncome($id)
    {
        $income = Income::find($id);

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $incomes = Income::with('parent')->when($request->search, function ($q) use ($request) {
            return $q->where('name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(5);

        return $this->sendResponse(['incomes' => $incomes], 'Data exited successfully');
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
                'name' => 'required|unique:incomes,name',
                'income_id' => 'nullable|integer|exists:incomes,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','income_id']);

            Income::create($data);

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

            $income = Income::find($id);

            $mainIncome = Income::where([
                ['income_id', null],
                ['active', 1],
            ])->get();

            return $this->sendResponse(['income' => $income, 'mainIncome' => $mainIncome], 'Data exited successfully');

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
    public function update(Request $request, Income $income)
    {
        try {

            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|unique:incomes,name',
                'income_id' => 'nullable|integer|exists:incomes,id' . ($income->id ? ",$income->id" : ''),
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['name','income_id']);

            $income->update($data);

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
    public function destroy(Income $income)
    {
        if (count($income->incomeAndExpense) > 0)
        {
            return $this->sendError('can not delete');
        }

        $income->delete();

        return $this->sendResponse([], 'Data exited successfully');
    }
}
