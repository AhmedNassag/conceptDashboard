<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Store;
use App\Traits\Message;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use Message;
    Use StoreTrait;

    public $selling_method ;

    public function __construct(){
        $this->selling_method = auth()->user()->client->selling_method_id;
    }


    public function companyBySubCategoryId($id){
        $store_id = $this->store();
        $companies = Company::where('status', 1)
            ->whereHas('products',function ($q) use($id,$store_id) {
            $q->where('sub_category_id',$id);
            $q->where('status',1);
            $q->where('sell_app',1);
            $q->whereHas('selling_method',function ($q) use($store_id){
                $q->where('status',1);
                $q->where('selling_methods.id',$this->selling_method);
            });
            $q->whereHas('productPrice',function ($q) use($store_id){
                $q->where('active',1);
                $q->where('selling_method_id',$this->selling_method);
            });
            $q->whereHas('storeProducts',function ($q) use($store_id){
                $q->where('store_id',$store_id);
                $q->where('sub_quantity_order','>=',1);
            });
        })->get();

        return $this->sendResponse(['companies' => $companies], trans('message.messageSuccessfully'));
    }

    public function company(){
        $store_id = $this->store();
        $companies = Company::where('status', 1)
            ->whereRelation('products',function ($q) use($store_id) {
            $q->where('status',1);
            $q->where('sell_app',1);
            $q->whereHas('selling_method',function ($q) use($store_id){
                $q->where('status',1);
                $q->where('selling_methods.id',$this->selling_method);
            });
            $q->whereHas('productPrice',function ($q) use($store_id){
                $q->where('active',1);
                $q->where('selling_method_id',$this->selling_method);
            });
            $q->whereRelation('storeProducts',function ($q) use($store_id){
                $q->where('store_id',$store_id);
                $q->where('sub_quantity_order','>=',1);
            });
        })->latest()->paginate(10);

        return $this->sendResponse(['companies' => $companies], trans('message.messageSuccessfully'));
    }

    /**
     * get company Home
     */

    public function companyHome(){
        $store_id = $this->store();
        $companies = Company::where('status', 1)
            ->whereRelation('products',function ($q) use($store_id) {
                $q->where('status',1);
                $q->where('sell_app',1);
                $q->whereHas('selling_method',function ($q) use($store_id){
                    $q->where('status',1);
                    $q->where('selling_methods.id',$this->selling_method);
                });
                $q->whereHas('productPrice',function ($q) use($store_id){
                    $q->where('active',1);
                    $q->where('selling_method_id',$this->selling_method);
                });
                $q->whereRelation('storeProducts',function ($q) use($store_id){
                    $q->where('store_id',$store_id);
                    $q->where('sub_quantity_order','>=',1);
                });
            })->inRandomOrder()->limit(6)->get();

        return $this->sendResponse(['companies' => $companies], trans('message.messageSuccessfully'));
    }
}
