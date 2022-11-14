<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\Message;
use Illuminate\Http\Request;

class SaleReportController extends Controller
{

    use Message;

    public function saleReport(Request $request)
    {

        $sales = Order::with('orderOtherOffer')->
        with([
            'user' => function ($q){
                $q->with('client');
            },
            'orderOffer',
            'orderTax',
            'orderStoreProduct' => function ($q) {
                $q->with(['sellingMethod:id,name',
                    'measurementUnit:id,name',
                    'storeProduct' => function ($m) {
                        $m->with('product:id,name');
                    }
                ]);
            },
            'store:id,name',
        ])
        ->whereOrderStatus(1)
        ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
            });
        })->where(function ($q) use ($request) {
            $q->when($request->order_id, function ($q) use ($request) {
                $q->where('id', $request->order_id);
            });
        })->
        latest()->paginate(15);

        return $this->sendResponse(['sales' => $sales], 'Data exited successfully');
    }

}
