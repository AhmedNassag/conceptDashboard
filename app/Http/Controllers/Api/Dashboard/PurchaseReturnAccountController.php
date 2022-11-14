<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Models\ClientIncome;
use App\Models\PurchaseReturn;
use App\Models\SupplierAccount;
use App\Models\SupplierIncome;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnAccountController extends Controller
{
    use Message;

    public function index(Request $request){

        if ($request->is_suppler == 1){
            $purchases = SupplierIncome::where([
                ['income_id',2],
                ['purchase_return_id','!=',null]
            ])->with(['supplier','income','treasury','purchaseReturn'=>function($qpr){
                $qpr->with(['returnProducts'=>function($qu){
                    $qu->with(['product','purchaseProduct']);
                }]);
            },'user'])
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
                    $q->when($request->purchase_return_id, function ($q) use ($request) {
                        $q->where('purchase_return_id', $request->purchase_return_id);
                    });
                })->latest()->paginate(15);
        }else{
            $purchases = ClientIncome::where([
                ['income_id',2],
                ['purchase_return_id','!=',null]
            ])->with(['client','income','treasury','purchaseReturn'=>function($qpr){
                $qpr->with(['returnProducts'=>function($qu){
                    $qu->with(['product','purchaseProduct']);
                }]);
            },'user'])
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
                    $q->when($request->purchase_return_id, function ($q) use ($request) {
                        $q->where('purchase_return_id', $request->purchase_return_id);
                    });
                })->latest()->paginate(15);
        }

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }

    public function purchaseReturnAccount(Request $request){
        // Validator request
        $v = Validator::make($request->all(), [
            'treasury_id' => 'required|integer|exists:treasuries,id',
            'purchase_return_id' => 'required|integer|exists:purchase_returns,id',
            'type_invoice' => 'required|integer',
            'treasury_amount' => 'required|numeric',
            'sender_amount' => 'required|numeric',
            'is_supplier' => 'required|numeric',
        ]);

        if ($v->fails()) {
            return $this->sendError('There is an error in the data', $v->errors());
        }

        if ($request->type_invoice == 0){
            $this->supplierAccount($request->all());
        }elseif ($request->type_invoice == 1){
            $this->treasuryAccount($request->all());
        }else{
            $this->treasuryAccount($request->all());
            $this->supplierAccount($request->all());
        }

        $purchaseReturn = PurchaseReturn::find($request['purchase_return_id']);
        $purchaseReturn->update([
            'is_account' => 1
        ]);
        return $this->sendResponse([],'Data exited successfully');
    }

    public function treasuryAccount($request){
        if ($request['is_supplier'] == 1){
            SupplierIncome::create([
                'supplier_id' => $request['sender_id'],
                'income_id' => 2,
                'treasury_id' => $request['treasury_id'],
                'purchase_return_id' => $request['purchase_return_id'],
                'amount' => $request['treasury_amount'],
                'payment_date' => now(),
                'user_id' => auth()->id(),
            ]);
        }else{
            ClientIncome::create([
                'client_id' => $request['sender_id'],
                'income_id' => 2,
                'treasury_id' => $request['treasury_id'],
                'purchase_return_id' => $request['purchase_return_id'],
                'amount' => $request['treasury_amount'],
                'payment_date' => now(),
                'user_id' => auth()->id(),
            ]);
        }
        return 1;
    }

    public function supplierAccount($request){
        if ($request['is_supplier'] == 1){
            SupplierAccount::create([
                'supplier_id' => $request['sender_id'],
                'purchase_return_id' => $request['purchase_return_id'],
                'amount' => -$request['sender_amount'],
            ]);
        }else{
            ClientAccount::create([
                'user_id' => $request['sender_id'],
                'purchase_return_id' => $request['purchase_return_id'],
                'amount' => -$request['sender_amount'],
            ]);
        }
        return 1;
    }
}
