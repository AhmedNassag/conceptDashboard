<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Traits\Message;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    use Message;

    public function getTaxes(){
        $taxes = Tax::where([
            ['status',1],
            ['app',1],
        ])->get();

        return $this->sendResponse(['taxes' => $taxes], trans('message.messageSuccessfully'));
    }
}
