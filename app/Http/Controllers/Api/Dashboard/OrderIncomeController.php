<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClientIncome;
use App\Traits\Message;
use Illuminate\Http\Request;

class OrderIncomeController extends Controller
{
    use Message;

    public function index (Request $request){
        $purchases = ClientIncome::where([
            ['income_id',3],
            ['order_id','!=',null]
        ])->with(['client','income','treasury','order'=>function($qpr){
            $qpr->with(['orderStatus','representative:id,name','orderOtherOffer','user' => function ($q){
                $q->with('client');
            },'orderOffer','orderTax','orderDetails' => function ($q) {
                $q->with(['sellingMethod:id,name',
                    'mainMeasurementUnit:id,name',
                    'subMeasurementUnit:id,name',
                    'product:id,name'
                ]);
            },
                'store:id,name',
            ]);
        },'user'])
            ->where(function ($q) use ($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->where('notes', 'like', '%' . $request->search . '%')
                        ->orWhere('amount', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('client','name','like','%'.$request->search.'%')
                        ->orWhereRelation('treasury','name','like','%'.$request->search.'%')
                        ->orWhereRelation('user','name','like','%'.$request->search.'%');
                });
            })->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('payment_date', ">=", $request->from_date)
                        ->whereDate('payment_date', "<=", $request->to_date);
                });
            })->where(function ($q) use ($request) {
                $q->when($request->order_id, function ($q) use ($request) {
                    $q->where('order_id', $request->order_id);
                });
            })->latest()->paginate(15);

        return $this->sendResponse(['purchases' => $purchases], 'Data exited successfully');
    }
}
