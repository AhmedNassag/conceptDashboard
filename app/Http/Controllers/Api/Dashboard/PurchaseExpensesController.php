<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientExpense;
use App\Models\SupplierExpense;
use App\Traits\Message;
use Illuminate\Http\Request;

class PurchaseExpensesController extends Controller
{
    use Message;

    public function index(Request $request){

        if ($request->is_suppler == 1){
            $purchases = SupplierExpense::where([
                ['expense_id',1],
                ['purchase_id','!=',null]
            ])->with(['supplier','expense','treasury','purchase.purchaseProducts.product','user'])
                ->where(function ($q) use ($request) {
                    $q->when($request->search, function ($q) use ($request) {
                        return $q->where('notes', 'like', '%' . $request->search . '%')
                            ->orWhere('amount', 'like', '%' . $request->search . '%')
                            ->orWhereRelation('supplier','name_supplier','like','%'.$request->search.'%')
                            ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                            ->orWhereRelation('user','name','like','%'.$request->search.'%');
                    });
                })->where(function ($q) use ($request) {
                    $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                        $q->whereDate('payment_date', ">=", $request->from_date)
                            ->whereDate('payment_date', "<=", $request->to_date);
                    });
                })->where(function ($q) use ($request) {
                    $q->when($request->purchase_id, function ($q) use ($request) {
                        $q->where('purchase_id', $request->purchase_id);
                    });
                })->latest()->paginate(15);
        }else{
            $purchases = ClientExpense::where([
                ['expense_id',1],
                ['purchase_id','!=',null]
            ])->with(['client','expense','treasury','purchase.purchaseProducts.product','user'])
                ->where(function ($q) use ($request) {
                    $q->when($request->search, function ($q) use ($request) {
                        return $q->where('notes', 'like', '%' . $request->search . '%')
                            ->orWhere('amount', 'like', '%' . $request->search . '%')
                            ->orWhereRelation('client','name','like','%'.$request->search.'%')
                            ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                            ->orWhereRelation('user','name','like','%'.$request->search.'%');
                    });
                })->where(function ($q) use ($request) {
                    $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                        $q->whereDate('payment_date', ">=", $request->from_date)
                            ->whereDate('payment_date', "<=", $request->to_date);
                    });
                })->where(function ($q) use ($request) {
                    $q->when($request->purchase_id, function ($q) use ($request) {
                        $q->where('purchase_id', $request->purchase_id);
                    });
                })->latest()->paginate(15);
        }

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }
}
