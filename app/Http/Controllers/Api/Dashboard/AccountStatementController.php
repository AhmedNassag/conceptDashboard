<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restriction;
use App\Models\SubAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class AccountStatementController extends Controller
{
    use Message;

    public function index(Request $request)
    {
        $restrictions = Restriction::where('sub_account_id',$request->sub_account_id)->with('dailyRestriction')
            ->whereRelation('dailyRestriction',function ($q) use($request){
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('date', ">=", $request->from_date)
                        ->whereDate('date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('description', 'like', '%' . $request->search . '%');
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['restrictions' => $restrictions], 'Data exited successfully');
    }

    public function subAccount($data){
        $subAccount= [];
        foreach ($data as $account){
            if (count($account->children) > 0){
                foreach ($account->children as $one) {
                    if (count($one->children) > 0) {
                        foreach ($one->children as $two) {
                            if (count($two->children) > 0) {
                                foreach ($two->children as $three) {
                                    if (count($three->children) > 0) {
                                        foreach ($three->children as $four) {

                                            $subAccount[]=$four;
                                        }
                                    }else{
                                        $subAccount[]=$three;
                                    }

                                }
                            }else{
                                $subAccount[]=$two;
                            }
                        }
                    }else{
                        $subAccount[]=$one;
                    }
                }
            }else{
                $subAccount[]=$account;
            }

        }

        return $subAccount;
    }

    public function accountDaily(){

        $accounts = SubAccount::where('sub_account_id', null)->get();
        $subAccount = $this->subAccount($accounts);

        return $this->sendResponse(['subAccount' => $subAccount], 'Data exited successfully');
    }
}
