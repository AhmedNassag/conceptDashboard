<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Traits\Message;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    use Message;

    public function province(){

        $provinces = Province::with('areas')->get();

        return $this->sendResponse(['provinces' => $provinces], trans('message.messageSuccessfully'));
    }
}
