<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class ClientAccountController extends Controller
{
    use Message;
    public function clientAccount(){

        $clientAccount = ClientAccount::where('user_id',auth()->id());

        return $this->sendResponse(['clientAccount' => $clientAccount], trans('message.messageSuccessfully'));
    }
}
