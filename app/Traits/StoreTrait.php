<?php

namespace App\Traits;

use App\Models\Store;

trait StoreTrait
{
    public function store()
    {
        if (auth()->check()) {
            if (auth()->user()->auth_id == 2) {
                if(auth()->user()->type == 'client')
                {
                    if (count(auth()->user()->client->area->store) > 0) {
                        $store_id = auth()->user()->client->area->store[0]->id;
                    } else {
                        $store_id = Store::where('main', 1)->first()->id;
                    }
                }
                if(auth()->user()->type == 'company')
                {
//                    if (count(auth()->user()->userCompany->area->store) > 0) {
//                        $store_id = auth()->user()->userCompany->area->store[0]->id;
//                    } else {
//                        $store_id = Store::where('main', 1)->first()->id;
//                    }
                    $store_id = Store::where('main', 1)->first()->id;
                }
            } else {
                $store_id = Store::where('main', 1)->first()->id;
            }
        } else {
            $store_id = Store::where('main', 1)->first()->id;
        }
        return $store_id ;
    }


}
