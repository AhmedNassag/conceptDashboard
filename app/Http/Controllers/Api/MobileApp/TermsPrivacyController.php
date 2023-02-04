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
        if($termsPrivacy)
        {
            return $this->sendResponse(['termsPrivacy' => $termsPrivacy], trans('message.messageSuccessfully'));
        }
        return $this->sendResponse(['termsPrivacy' => ['term' => '', 'privacy' => '']], trans('message.messageSuccessfully'));
    }
}
