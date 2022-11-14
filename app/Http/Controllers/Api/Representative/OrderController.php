<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Message;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Message;

    // get Representative order start

    public function getRepresentativeOrder(){
        $orders = Order::where([
            ['order_status_id','!=',5],
            ['representative_id',auth()->id()],
            ['representative_status','=',0],
        ])->where([
            ['order_status_id','!=',6],
            ['representative_id',auth()->id()],
            ['representative_status','=',0],
        ])->where([
            ['order_status_id','!=',7],
            ['representative_status','=',0],
        ])->where([
            ['order_status_id','!=',8],
            ['representative_status','=',0],
        ])->with(['orderStatus','orderOtherOffer','orderOffer','orderTax','orderDetails' => function ($q) {
            $q->with(['sellingMethod:id,name',
                'mainMeasurementUnit:id,name',
                'subMeasurementUnit:id,name',
                'product:id,name'
            ]);
        },'user.client'])->latest()->paginate(15);

        return $this->sendResponse(['orders' => $orders], trans('message.messageSuccessfully'));
    }
}
