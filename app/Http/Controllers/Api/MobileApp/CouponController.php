<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    use Message;

    public function checkCoupon(Request $request){

        // Validator request
        $v = Validator::make($request->all(), [
            'code' => 'required|exists:discounts,code',
        ]);

        if ($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }
        $coupon = Discount::where('code',$request->code)->first();
        $errors = [];

        if ($coupon->start_date > now()){
            $errors['code'][] = "هذا الكوبون لم يبدأ بعد";
            return $this->sendError(trans('message.messageError'),$errors );
        }

        if ($coupon->expire_date < now() || $coupon->status == 0 || $coupon->use_times == $coupon->used_times){
            $errors['code'][] = "لقد انتهت صلاحية هذا الكوبون";
            return $this->sendError(trans('message.messageError'),$errors );
        }

        return $this->sendResponse(['coupon' => $coupon], trans('message.messageSuccessfully'));

    }

}
