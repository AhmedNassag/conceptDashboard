<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Restriction;
use App\Models\SubAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class IncomeListController extends Controller
{
    use Message;

    public function debitRestriction($id ,$from_date,$to_date)
    {
        return  Restriction::where([['debit',1],['sub_account_id',$id]])->whereHas('dailyRestriction', function ($q) use ($from_date,$to_date) {
            $q->whereDate('date', ">=", $from_date);
            $q->whereDate('date', "<=", $to_date);
        })->get()->sum('amount');
    }

    public function creditRestriction($id,$from_date,$to_date)
    {
        return   Restriction::where([['debit',0],['sub_account_id',$id]])->whereHas('dailyRestriction', function ($q) use ($from_date,$to_date) {
            $q->whereDate('date', ">=", $from_date);
            $q->whereDate('date', "<=", $to_date);
        })->get()->sum('amount');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $incomes = [];
        $data_incomes = SubAccount::where([['main_account_id', 4], ['sub_account_id', null]])->get();

        foreach ($data_incomes as $indexOne=>$income){
            $num_debit_one = 0;
            $num_credit_one = 0;
            if (count($income->children) > 0)
            {
                foreach ($income->children as $indexTwo=>$childOne){
                    $num_debit = 0;
                    $num_credit = 0;
                    if (count($childOne->children) > 0)
                    {
                        foreach ($childOne->children as $indexThree=>$childTwo){

                            if (count($childTwo->children) > 0)
                            {
                                foreach ($childTwo->children as $indexFour=>$childThree){
                                    $num_debit += $this->debitRestriction($childThree->id,$request->from_date,$request->to_date);
                                    $num_credit += $this->creditRestriction($childThree->id,$request->from_date,$request->to_date);
                                }
                            }
                            $num_debit += $this->debitRestriction($childTwo->id,$request->from_date,$request->to_date);
                            $num_credit += $this->creditRestriction($childTwo->id,$request->from_date,$request->to_date);
                        }
                    }
                    $childOne->sumDebit = $this->debitRestriction($childOne->id,$request->from_date,$request->to_date) + $num_debit;
                    $num_debit += $this->debitRestriction($childOne->id,$request->from_date,$request->to_date);

                    $childOne->sumCredit = $this->creditRestriction($childOne->id,$request->from_date,$request->to_date) + $num_credit;
                    $num_credit += $this->creditRestriction($childOne->id,$request->from_date,$request->to_date);
                    $num_debit_one += $num_debit;
                    $num_credit_one += $num_credit;
                    $incomes[$indexOne][$indexTwo] = $childOne;
                }
                $income->sumDebit = $this->debitRestriction($income->id,$request->from_date,$request->to_date) + $num_debit_one;

                $income->sumCredit = $this->creditRestriction($income->id,$request->from_date,$request->to_date) + $num_credit_one;
                $incomes[$indexOne] = $income;
            }else{
                $income->sumDebit = $this->debitRestriction($income->id,$request->from_date,$request->to_date);

                $income->sumCredit = $this->creditRestriction($income->id,$request->from_date,$request->to_date);
                $incomes[$indexOne] = $income;
            }

        }

        // start  liabilities

        $expenses = [];
        $data_expenses = SubAccount::where([['main_account_id', 3], ['sub_account_id', null]])->get();

        foreach ($data_expenses as $indexOne=>$expens){
            $num_debit_one = 0;
            $num_credit_one = 0;
            if (count($expens->children) > 0)
            {
                foreach ($expens->children as $indexTwo=>$childOne){
                    $num_debit = 0;
                    $num_credit = 0;
                    if (count($childOne->children) > 0)
                    {
                        foreach ($childOne->children as $indexThree=>$childTwo){

                            if (count($childTwo->children) > 0)
                            {
                                foreach ($childTwo->children as $indexFour=>$childThree){
                                    $num_debit += $this->debitRestriction($childThree->id,$request->from_date,$request->to_date);
                                    $num_credit += $this->creditRestriction($childThree->id,$request->from_date,$request->to_date);
                                }
                            }
                            $num_debit += $this->debitRestriction($childTwo->id,$request->from_date,$request->to_date);
                            $num_credit += $this->creditRestriction($childTwo->id,$request->from_date,$request->to_date);
                        }
                    }
                    $childOne->sumDebit = $this->debitRestriction($childOne->id,$request->from_date,$request->to_date) + $num_debit;
                    $num_debit += $this->debitRestriction($childOne->id,$request->from_date,$request->to_date);

                    $childOne->sumCredit = $this->creditRestriction($childOne->id,$request->from_date,$request->to_date) + $num_credit;
                    $num_credit += $this->creditRestriction($childOne->id,$request->from_date,$request->to_date);
                    $num_debit_one += $num_debit;
                    $num_credit_one += $num_credit;
                    $expenses[$indexOne][$indexTwo] = $childOne;
                }
                $expens->sumDebit = $this->debitRestriction($expens->id,$request->from_date,$request->to_date) + $num_debit_one;

                $expens->sumCredit = $this->creditRestriction($expens->id,$request->from_date,$request->to_date) + $num_credit_one;
                $expenses[$indexOne] = $expens;
            }else{
                $expens->sumDebit = $this->debitRestriction($expens->id,$request->from_date,$request->to_date);

                $expens->sumCredit = $this->creditRestriction($expens->id,$request->from_date,$request->to_date);
                $expenses[$indexOne] = $expens;
            }

        }

        return $this->sendResponse(['incomes' => $incomes, 'expenses' => $expenses], 'Data exited successfully');
    }
}
