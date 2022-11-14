<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\PopupAds;
use App\Traits\Message;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    use Message;
    public $selling_method ;

    public function __construct(){
        $this->selling_method = auth()->user()->client->selling_method_id;
    }

    public function popupAds(){

        $ads = PopupAds::with(['product' => function ($q){
            $q->with(['media','productPrice'=>function($q){
                $q->where('active',1);
                $q->where('selling_method_id',$this->selling_method);
                $q->with('sellingMethod','measurementUnit');
                $q->whereHas('sellingMethod',function ($q) {
                    $q->where('status',1);
                    $q->where('selling_methods.id',$this->selling_method);
                });
            }]);
        }])->get();
        return $this->sendResponse(['ads' => $ads], trans('message.messageSuccessfully'));
    }

    public function slidersAds(){

        $top = Ad::where('place',1)->with(['product' => function ($q){
            $q->with(['media','productPrice'=>function($q){
                $q->where('active',1);
                $q->where('selling_method_id',$this->selling_method);
                $q->with('sellingMethod','measurementUnit');
                $q->whereHas('sellingMethod',function ($q) {
                    $q->where('status',1);
                    $q->where('selling_methods.id',$this->selling_method);
                });
            }]);
        }])->get();

        $bottom = Ad::where('place',2)->with(['product' => function ($q){
            $q->with(['media','productPrice'=>function($q){
                $q->where('active',1);
                $q->where('selling_method_id',$this->selling_method);
                $q->with('sellingMethod','measurementUnit');
                $q->whereHas('sellingMethod',function ($q) {
                    $q->where('status',1);
                    $q->where('selling_methods.id',$this->selling_method);
                });
            }]);
        }])->get();

        return $this->sendResponse(['top' => $top,'bottom'=>$bottom], trans('message.messageSuccessfully'));
    }
}
