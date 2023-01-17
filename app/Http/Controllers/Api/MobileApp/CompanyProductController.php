<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Store;
use App\Traits\Message;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyProductController extends Controller
{
    use Message;
    use StoreTrait;

    public $selling_method ;

    public function __construct()
    {
        $this->selling_method = 2;
    }



    public function productCompany(Request $request)
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
                $q->where('selling_method_id', 2);
            })
            ->whereHas('storeProducts', function ($q) use ($store_id) {
                $q->where('store_id', $store_id);
                $q->where('sub_quantity_order', '>=', 1);
            })
            ->with(['media','orderDetails', 'productPrice' => function ($q) {
                $q->where('active', 1);
                $q->where('selling_method_id', 2);
                $q->with('sellingMethod', 'measurementUnit');
            }])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('name', 'like', "%" . $request->search . "%")
                        ->orWhere('barcode', 'like', "%" . $request->search . "%");
                });
            })
            ->latest("products.created_at")->get();
        if($products)
        {
            return $this->sendResponse(['products' => $products], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['products' => []], trans('message.messageSuccessfully'));
        }
    }

}
