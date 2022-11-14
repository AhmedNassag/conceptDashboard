<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Traits\Message;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    use Message;

    public function shippingPrice(){

        $area = auth()->user()->client->area;

        return $this->sendResponse(['area' => $area], trans('message.messageSuccessfully'));
    }
}
