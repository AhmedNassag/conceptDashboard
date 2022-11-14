<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\SellingMethod;
use App\Traits\Message;
use Illuminate\Http\Request;

class SellingMethodController extends Controller
{
    use Message;

    public function sellingMethod(){

        $selling_methods = SellingMethod::where('status',1)->get();

        return $this->sendResponse(['selling_methods' => $selling_methods], trans('message.messageSuccessfully'));
    }
}
