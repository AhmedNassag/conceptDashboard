<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restriction;
use App\Models\SubAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class FinancialCenterController extends Controller
{
    use Message;

    public function debitRestriction($id ,$date)
    {
        return  Restriction::where([['debit',1],['sub_account_id',$id]])->whereHas('dailyRestriction', function ($q) use ($date) {
            $q->whereDate('date', "<=", $date);
        })->get()->sum('amount');
    }

    public function creditRestriction($id,$date)
    {
        return   Restriction::where([['debit',0],['sub_account_id',$id]])->whereHas('dailyRestriction', function ($q) use ($date) {
            $q->whereDate('date', "<=", $date);
        })->get()->sum('amount');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $assets = [];
        $data_assets = SubAccount::where([['main_account_id', 1], ['sub_account_id', null]])->get();

        foreach ($data_assets as $indexOne=>$asset){
            $num_debit_one = 0;
            $num_credit_one = 0;
            if (count($asset->children) > 0)
            {
                foreach ($asset->children as $indexTwo=>$childOne){
                    $num_debit = 0;
                    $num_credit = 0;
                    if (count($childOne->children) > 0)
                    {
                        foreach ($childOne->children as $indexThree=>$childTwo){

                            if (count($childTwo->children) > 0)
                            {
                                foreach ($childTwo->children as $indexFour=>$childThree){
                                    $num_debit += $this->debitRestriction($childThree->id,$request->to_date);
                                    $num_credit += $this->creditRestriction($childThree->id,$request->to_date);
                                }
                            }
                            $num_debit += $this->debitRestriction($childTwo->id,$request->to_date);
                            $num_credit += $this->creditRestriction($childTwo->id,$request->to_date);
                        }
                    }
                    $childOne->sumDebit = $this->debitRestriction($childOne->id,$request->to_date) + $num_debit;
                    $num_debit += $this->debitRestriction($childOne->id,$request->to_date);

                    $childOne->sumCredit = $this->creditRestriction($childOne->id,$request->to_date) + $num_credit;
                    $num_credit += $this->creditRestriction($childOne->id,$request->to_date);
                    $num_debit_one += $num_debit;
                    $num_credit_one += $num_credit;
                    $assets[$indexOne][$indexTwo] = $childOne;
                }
                $asset->sumDebit = $this->debitRestriction($asset->id,$request->to_date) + $num_debit_one;

                $asset->sumCredit = $this->creditRestriction($asset->id,$request->to_date) + $num_credit_one;
                $assets[$indexOne] = $asset;
            }else{
                $asset->sumDebit = $this->debitRestriction($asset->id,$request->to_date);

                $asset->sumCredit = $this->creditRestriction($asset->id,$request->to_date);
                $assets[$indexOne] = $asset;
            }

        }

        // start  liabilities

        $liabilities = [];
        $data_liabilities = SubAccount::where([['main_account_id', 2], ['sub_account_id', null]])->get();

        foreach ($data_liabilities as $indexOne=>$liability){
            $num_debit_one = 0;
            $num_credit_one = 0;
            if (count($liability->children) > 0)
            {
                foreach ($liability->children as $indexTwo=>$childOne){
                    $num_debit = 0;
                    $num_credit = 0;
                    if (count($childOne->children) > 0)
                    {
                        foreach ($childOne->children as $indexThree=>$childTwo){

                            if (count($childTwo->children) > 0)
                            {
                                foreach ($childTwo->children as $indexFour=>$childThree){
                                    $num_debit += $this->debitRestriction($childThree->id,$request->to_date);
                                    $num_credit += $this->creditRestriction($childThree->id,$request->to_date);
                                }
                            }
                            $num_debit += $this->debitRestriction($childTwo->id,$request->to_date);
                            $num_credit += $this->creditRestriction($childTwo->id,$request->to_date);
                        }
                    }
                    $childOne->sumDebit = $this->debitRestriction($childOne->id,$request->to_date) + $num_debit;
                    $num_debit += $this->debitRestriction($childOne->id,$request->to_date);

                    $childOne->sumCredit = $this->creditRestriction($childOne->id,$request->to_date) + $num_credit;
                    $num_credit += $this->creditRestriction($childOne->id,$request->to_date);
                    $num_debit_one += $num_debit;
                    $num_credit_one += $num_credit;
                    $liabilities[$indexOne][$indexTwo] = $childOne;
                }
                $liability->sumDebit = $this->debitRestriction($liability->id,$request->to_date) + $num_debit_one;

                $liability->sumCredit = $this->creditRestriction($liability->id,$request->to_date) + $num_credit_one;
                $liabilities[$indexOne] = $liability;
            }else{
                $liability->sumDebit = $this->debitRestriction($liability->id,$request->to_date);

                $liability->sumCredit = $this->creditRestriction($liability->id,$request->to_date);
                $liabilities[$indexOne] = $liability;
            }

        }

        return $this->sendResponse(['assets' => $assets, 'liabilities' => $liabilities], 'Data exited successfully');
    }
}
