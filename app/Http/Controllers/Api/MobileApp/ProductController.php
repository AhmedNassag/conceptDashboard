<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Store;
use App\Traits\Message;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use Message;
    use StoreTrait;

    public $selling_method ;

    public function __construct()
    {
        $this->selling_method = auth()->user()->client->selling_method_id;
    }



    public function productCompany($id){
        $store_id = $this->store();
        $products = Product::where([
            ['status',1],
            ['company_id',$id],
            ['sell_app',1],
        ])
        ->whereHas('selling_method',function ($q) use($store_id){
            $q->where('status',1);
            $q->where('selling_methods.id',$this->selling_method);
        })
        ->whereHas('productPrice',function ($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
        })
        ->whereHas('storeProducts',function ($q) use ($store_id){
            $q->where('store_id',$store_id);
            $q->where('sub_quantity_order','>=',1);
        })
        ->with(['media','productPrice'=>function($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
            $q->with('sellingMethod','measurementUnit');
        }])
        ->latest()->paginate(15);

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function products(Request $request)
    {
        $store_id = $this->store();
        $products = Product::
        where([
            ['status',1],
            ['sell_app',1],
        ])
        ->whereHas('selling_method',function ($q) use($store_id){
            $q->where('status',1);
            $q->where('selling_methods.id',$this->selling_method);
        })
        ->whereHas('productPrice',function ($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
        })
        ->whereHas('storeProducts',function ($q) use ($store_id){
            $q->where('store_id',$store_id);
            $q->where('sub_quantity_order','>=',1);
        })
        ->with(['media','productPrice'=>function($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
            $q->with('sellingMethod','measurementUnit');
        }])
        ->where(function ($q) use($request){
            $q->when($request->search,function ($q) use ($request){
                return $q->where('name','like',"%". $request->search ."%")
                ->orWhere('barcode','like',"%". $request->search ."%");
            });
        })
        ->latest("products.created_at")->paginate(15);

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function filters(Request $request)
    {
        $store_id = $this->store();
        $products = Product::where([
                ['status', 1],
                ['sell_app', 1],
                ['category_id',1]
            ])
            ->whereHas('selling_method', function ($q) use ($store_id) {
                $q->where('status', 1);
                $q->where('selling_methods.id', $this->selling_method);
            })
            ->whereHas('productPrice', function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
            })
            ->whereHas('storeProducts', function ($q) use ($store_id) {
                $q->where('store_id', $store_id);
                $q->where('sub_quantity_order', '>=', 1);
            })
            ->with(['media', 'productPrice' => function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
                $q->with('sellingMethod', 'measurementUnit');
            }])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('name', 'like', "%" . $request->search . "%")
                    ->orWhere('barcode', 'like', "%" . $request->search . "%");
                });
            })
            ->latest("products.created_at")->paginate(15);

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function waxes(Request $request)
    {
        $store_id = $this->store();
        $products = Product::where([
            ['status', 1],
            ['sell_app', 1],
            ['category_id', 2]
        ])
            ->whereHas('selling_method', function ($q) use ($store_id) {
                $q->where('status', 1);
                $q->where('selling_methods.id', $this->selling_method);
            })
            ->whereHas('productPrice', function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
            })
            ->whereHas('storeProducts', function ($q) use ($store_id) {
                $q->where('store_id', $store_id);
                $q->where('sub_quantity_order', '>=', 1);
            })
            ->with(['media', 'productPrice' => function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
                $q->with('sellingMethod', 'measurementUnit');
            }])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('name', 'like', "%" . $request->search . "%")
                        ->orWhere('barcode', 'like', "%" . $request->search . "%");
                });
            })
            ->latest("products.created_at")->paginate(15);

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function spareParts(Request $request)
    {
        $store_id = $this->store();
        $products = Product::where([
            ['status', 1],
            ['sell_app', 1],
            ['category_id', 3]
        ])
            ->whereHas('selling_method', function ($q) use ($store_id) {
                $q->where('status', 1);
                $q->where('selling_methods.id', $this->selling_method);
            })
            ->whereHas('productPrice', function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
            })
            ->whereHas('storeProducts', function ($q) use ($store_id) {
                $q->where('store_id', $store_id);
                $q->where('sub_quantity_order', '>=', 1);
            })
            ->with(['media', 'productPrice' => function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', $this->selling_method);
                $q->with('sellingMethod', 'measurementUnit');
            }])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('name', 'like', "%" . $request->search . "%")
                        ->orWhere('barcode', 'like', "%" . $request->search . "%");
                });
            })
            ->latest("products.created_at")->paginate(15);

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function getProductByBarcode($barcode)
    {
        $store_id = $this->store();
        $products = Product::where([
            ['status',1],
            ['sell_app',1],
            ['barcode',$barcode],
        ])
        ->whereHas('selling_method',function ($q) use($store_id){
            $q->where('status',1);
            $q->where('selling_methods.id',$this->selling_method);
        })
        ->whereHas('productPrice',function ($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
        })
        ->whereHas('storeProducts',function ($q) use ($store_id){
            $q->where('store_id',$store_id);
            $q->where('sub_quantity_order','>=',1);
        })
        ->with(['media','productPrice'=>function($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
            $q->with('sellingMethod','measurementUnit');
        }])
        ->get();

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function pestProduct()
    {
        $store_id = $this->store();
        $products = Product::where([
            ['status',1],
            ['sell_app',1],
        ])
        ->whereHas('selling_method',function ($q) use ($store_id){
            $q->where('status',1);
            $q->where('selling_methods.id',$this->selling_method);
        })
        ->whereHas('productPrice',function ($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
        })
        ->whereHas('storeProducts',function ($q) use ($store_id){
            $q->where('store_id',$store_id);
            $q->where('sub_quantity_order','>=',1);
        })
        ->with(['media','productPrice'=>function($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
            $q->with('sellingMethod','measurementUnit');
        }])
        ->inRandomOrder()->limit(10)->get();

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }



    public function similarProducts($id)
    {
        $store_id = $this->store();
        $products = Product::where([
            ['status',1],
            ['sell_app',1],
            ['sub_category_id',$id],
        ])
        ->whereHas('selling_method',function ($q) use($store_id){
            $q->where('status',1);
            $q->where('selling_methods.id',$this->selling_method);
        })
        ->whereHas('productPrice',function ($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
        })
        ->whereHas('storeProducts',function ($q) use ($store_id){
            $q->where('store_id',$store_id);
            $q->where('sub_quantity_order','>=',1);
        })
        ->with(['media','productPrice'=>function($q){
            $q->where('active',1);
            $q->where('selling_method_id',$this->selling_method);
            $q->with('sellingMethod','measurementUnit');
        }])
        ->inRandomOrder()->limit(3)->get();

        return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
    }

}
