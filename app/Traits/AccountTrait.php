<?php

namespace App\Traits;

use App\Models\Restriction;

trait AccountTrait
{
    public function debitAmount($subAccount)
    {
        $num = $this->debitTransaction($subAccount->id);
        foreach ($subAccount->children as $first){
            if (count($first->children)>0)
            {
                foreach ($first->children as $two)
                {
                    if (count($two->children)>0)
                    {
                        foreach ($two->children as $three)
                        {
                            if (count($three->children)>0)
                            {
                                foreach ($three->children as $four)
                                {
                                    if (count($four->children)>0)
                                    {
                                        foreach ($four->children as $five)
                                        {
                                            $num += $this->debitTransaction($five->id);
                                        }
                                    }
                                    $num += $this->debitTransaction($four->id);
                                }
                            }
                            $num += $this->debitTransaction($three->id);
                        }
                    }
                    $num += $this->debitTransaction($two->id);
                }
            }
            $num += $this->debitTransaction($first->id);

        }
        return $num;
    }

    public function creditAmount($subAccount)
    {
        $num = $this->creditTransaction($subAccount->id);
        foreach ($subAccount->children as $first){
            if (count($first->children)>0)
            {
                foreach ($first->children as $two)
                {
                    if (count($two->children)>0)
                    {
                        foreach ($two->children as $three)
                        {
                            if (count($three->children)>0)
                            {
                                foreach ($three->children as $four)
                                {
                                    if (count($four->children)>0)
                                    {
                                        foreach ($four->children as $five)
                                        {
                                            $num += $this->creditTransaction($five->id);
                                        }
                                    }
                                    $num += $this->creditTransaction($four->id);
                                }
                            }
                            $num += $this->creditTransaction($three->id);
                        }
                    }
                    $num += $this->creditTransaction($two->id);
                }
            }
            $num += $this->creditTransaction($first->id);

        }
        return $num;
    }

    public function debitTransaction($id)
    {
        return  Restriction::where([['debit',1],['sub_account_id',$id]])->get()->sum('amount') ;
    }

    public function creditTransaction($id)
    {
        return  Restriction::where([['debit',0],['sub_account_id',$id]])->get()->sum('amount') ;
    }

}
