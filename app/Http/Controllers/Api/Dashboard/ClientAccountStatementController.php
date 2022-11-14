<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;

class ClientAccountStatementController extends Controller
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
        $supplierAccounts = ClientAccount::where('user_id',$id)->with(['order','purchase','purchaseReturn','clientExpense','clientIncome'])
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })->latest()->paginate(15);

        $suppliers = User::where('status',1)->whereAuthId(2)->whereJsonContains('role_name','client')->get();

        return $this->sendResponse(['suppliers'=>$suppliers,'supplierAccounts'=>$supplierAccounts], 'Data exited successfully');
    }
}
