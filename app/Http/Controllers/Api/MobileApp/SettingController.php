<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\Message;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use Message;

    public function setting (){

        $setting = Setting::first();
        $setting['order_amount'] = 0 ;
        if (auth()->check()){
            $setting['order_amount'] = auth()->user()->client->sellingMethod->order_amount;
        }
        return $this->sendResponse(['setting' => $setting], trans('message.messageSuccessfully'));
    }
}
