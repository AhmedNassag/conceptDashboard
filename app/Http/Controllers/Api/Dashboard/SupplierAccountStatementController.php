<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class SupplierAccountStatementController extends Controller
{
    use Message;

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request)
    {
        $supplierAccounts = SupplierAccount::where('supplier_id',$id)->with(['supplierExpense','supplierIncome'])
            ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
            });
        })->latest()->paginate(15);

        $suppliers = Supplier::where('status', 1)->get();

        return $this->sendResponse(['suppliers'=>$suppliers,'supplierAccounts'=>$supplierAccounts], 'Data exited successfully');
    }

}
