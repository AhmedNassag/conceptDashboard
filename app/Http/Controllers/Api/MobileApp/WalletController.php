<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Competition;
use App\Models\PointPrice;
use App\Models\Share;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;

class WalletController extends Controller
{
    use Message;
    use NotificationTrait;


    public function welcomePoints()
    {
        $welcomePoints = Wallet::where('user_id', auth()->user()->id)
        ->where('welcome_points','!=',0)
        ->get();
        if ($welcomePoints)
        {
            return $this->sendResponse(['welcomePoints' => $welcomePoints], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['welcomePoints' => []], trans('message.messageSuccessfully'));
        }
    }



    public function productPoints()
    {
        $productPoints = Wallet::where('user_id', auth()->user()->id)
        ->where('order_id','!=',0)
        ->select(DB::raw('SUM(product_points) as product_points'), DB::raw('user_id'))
        ->groupBy('user_id')->orderBy('product_points', 'asc')->get();
        if ($productPoints)
        {
            return $this->sendResponse(['productPoints' => $productPoints], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['productPoints' => []], trans('message.messageSuccessfully'));
        }
    }



    public function pointDetails()
    {
        $pointPrice = PointPrice::first();
        if ($pointPrice)
        {
            return $this->sendResponse(['pointPrice' => $pointPrice], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['pointPrice' => []], trans('message.messageSuccessfully'));
        }
    }



    public function wallet()
    {
        $pointPrice = PointPrice::first();

        $welcomePoints = Wallet::where('user_id', auth()->user()->id)
        ->where('welcome_points', '!=', 0)
        ->get();

        $productPoints = Wallet::where('user_id', auth()->user()->id)
        ->where('order_id', '!=', 0)
        ->select(DB::raw('SUM(product_points) as product_points'), DB::raw('user_id'))
        ->groupBy('user_id')->orderBy('product_points', 'asc')->get();

        return $this->sendResponse([
            'pointPrice' => $pointPrice ? $pointPrice : 0,
            'welcomePoints' => $welcomePoints ? $welcomePoints : 0,
            'productPoints' => $productPoints ? $productPoints : 0,
        ], trans('message.messageSuccessfully'));
    }



    public function sendNotification($id,$message,$image)
    {
        User::whereAuthId(1)
        /*->whereRelation('roles.notify','name','Add OnlineOrder')*/
        ->each(function ($admin) use($id,$message,$image){
            $admin->notify(new AddNotification('',$id,'indexShare',$message,$image));
        });
    }
}
