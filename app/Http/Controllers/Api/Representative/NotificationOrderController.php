<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use Illuminate\Http\Request;

class NotificationOrderController extends Controller
{
    use Message;

    public function orderDelivered($id){
        $order = Order::find($id);
        $order->update([
            'representative_status'=>1
        ]);

        $message = "المندوب " .auth()->user()->name . " سلم الطلب رقم " . $id . " الى العميل " . $order->user->name;
        $image = asset('upload/representative_profile/'.auth()->user()->media->file_name);
        $this->sendNotification($id,$message,$image);

        return $this->sendResponse([], trans('message.messageSuccessfully'));
    }

    public function orderReturned($id){
        $order = Order::find($id);
        $order->update([
            'representative_status'=>2
        ]);
        $message = " العميل " . $order->user->name . " استرجع الطلب رقم " . $id . " مع المندوب " . auth()->user()->name ;
        $image = asset('upload/representative_profile/'.auth()->user()->media->file_name);
        $this->sendNotification($id,$message,$image);

        return $this->sendResponse([], trans('message.messageSuccessfully'));
    }

    public function sendNotification($id,$message,$image){
        User::whereAuthId(1)
            ->whereRelation('roles.notify','name','Add OnlineOrder')
            ->each(function ($admin) use($id,$message,$image){
                $admin->notify(new AddNotification('',$id,'showOrderOnline',$message,$image));
            });
    }
}
