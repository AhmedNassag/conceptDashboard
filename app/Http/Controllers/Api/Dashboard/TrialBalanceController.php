<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SubAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subAccount = SubAccount::with('restriction')->whereHas('restriction',function ($q) use ($request){
            $q->whereHas('dailyRestriction',function ($q) use ($request){
                $q->whereDate('date', ">=", $request->from_date)
                    ->whereDate('date', "<=", $request->to_date);
            });
        })->get();

        return $this->sendResponse(['subAccount' => $subAccount], 'Data exited successfully');
    }
}
