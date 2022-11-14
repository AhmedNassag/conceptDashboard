<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Supplier;
use App\Models\SupplierExpense;
use App\Models\Treasury;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierExpensesController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $purchases = SupplierExpense::with(['supplier','expense','treasury','purchase.purchaseProducts.product','user','supplierAccount'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                        ->orWhereRelation('expense','name','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }

    public function create(){

        $suppliers = Supplier::where('status', 1)->get();
        $treasuries = Treasury::where('active',1)->get();
        $expenses = Expense::where('active',1)->get();
        return $this->sendResponse(['suppliers'=>$suppliers,'treasuries'=>$treasuries,'expenses'=>$expenses], 'Data exited successfully');
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
                'treasury_id' => 'required|integer|exists:treasuries,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'expense_id' => 'required|integer|exists:expenses,id',
                'purchase_id' => 'nullable|integer|exists:purchases,id',
                'notes' => 'nullable|string|min:5',
                'amount' => 'required|numeric',
                'payment_date' => 'required|date',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['treasury_id','supplier_id','expense_id','purchase_id','notes','amount','payment_date']);
            $data['user_id'] = auth()->id();
            $supplierAccount = SupplierExpense::create($data);
            $supplierAccount->supplierAccount()->create([
                'supplier_expense_id' => $supplierAccount->id,
                'supplier_id' => $request->supplier_id,
                'amount' => - $request->amount,
            ]);
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
            $supplier = SupplierExpense::find($id);
            $suppliers = Supplier::where('status', 1)->get();
            $treasuries = Treasury::where('active',1)->get();
            $expenses = Expense::where('active',1)->get();
            return $this->sendResponse(['suppliers'=>$suppliers,'supplier'=>$supplier,'treasuries'=>$treasuries,'expenses'=>$expenses], 'Data exited successfully');

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
                'treasury_id' => 'required|integer|exists:treasuries,id',
                'supplier_id' => 'required|integer|exists:suppliers,id',
                'expense_id' => 'required|integer|exists:expenses,id',
                'purchase_id' => 'nullable|integer|exists:purchases,id',
                'notes' => 'nullable|string|min:5',
                'amount' => 'required|numeric',
                'payment_date' => 'required|date',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $data = $request->only(['treasury_id','supplier_id','expense_id','purchase_id','notes','amount','payment_date']);

            $supplierAccount = SupplierExpense::find($id);
            $supplierAccount->update($data);
            $supplierAccount->supplierAccount()->update([
                'supplier_expense_id' => $supplierAccount->id,
                'supplier_id' => $request->supplier_id,
                'amount' => -$request->amount,
            ]);
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
        $supplierAccount = SupplierExpense::find($id);
        $supplierAccount->supplierAccount()->delete();
        $supplierAccount->delete();
        return $this->sendResponse([], 'Data exited successfully');
    }
}
