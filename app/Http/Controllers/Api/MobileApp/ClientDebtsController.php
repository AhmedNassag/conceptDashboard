<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\ClientAccount;
use App\Traits\Message;
use Illuminate\Http\Request;

class ClientDebtsController extends Controller
{
    use Message;

    public function clientDebts(){
        $accounts = ClientAccount::where('user_id',auth()->id())->latest()->paginate(15);
        return $this->sendResponse(['accounts' => $accounts], trans('message.messageSuccessfully'));
    }
}
