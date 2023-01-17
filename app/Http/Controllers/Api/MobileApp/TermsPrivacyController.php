<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\TermsPrivacy;
use App\Traits\Message;


class TermsPrivacyController extends Controller
{
    use Message;

    public function index()
    {
        $termsPrivacy = TermsPrivacy::first();
        return $this->sendResponse(['termsPrivacy' => $termsPrivacy], trans('message.messageSuccessfully'));
    }
}
