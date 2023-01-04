<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OrderDetails;
use App\Models\OrderStoreProduct;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientReport extends Controller
{
    use Message;

    public function clientOldNew(Request $request)
    {
        $clients = User::whereAuthId(2)->whereJsonContains('role_name','client')
        ->with('client')
        ->where(function ($q) use ($request) {
            $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                $q->whereDate('created_at', ">=", $request->from_date)
                ->whereDate('created_at', "<=", $request->to_date);
            });
        })
        ->where(function ($q) use ($request) {
            $q->when($request->password, function ($q) use ($request) {
                $q->whereNull('password');
            });
        })
            ->paginate(10);

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');

    }

    public function clientQty(Request $request)
    {

        $order = $request->order == 'asc' ? 'asc' : 'desc';

        $clients = OrderDetails::
            join('orders','orders.id','order_details.order_id')
            ->join('users','users.id','orders.user_id')
            ->where('orders.order_status_id',5)
            ->select(DB::raw('SUM(quantity) as amount,SUM(sub_quantity) as sub_amount, users.id, users.name, users.phone'))
            ->groupBy(['users.id','users.name','users.phone'])
            ->orderBy('amount',$order)
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->take(10)
            ->get();

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');

    }

    public function clientPrice(Request $request)
    {

        $order = $request->order == 'asc' ? 'asc' : 'desc';

        $clients = OrderDetails::
        join('orders','orders.id','order_details.order_id')
            ->join('users','users.id','orders.user_id')
            ->where('orders.order_status_id',5)
            ->select(DB::raw('SUM(orders.total) as price,users.id,users.name,users.phone'))
            ->groupBy(['users.id','users.name','users.phone'])
            ->orderBy('price',$order)
            ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                    ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->take(10)
            ->get();

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');

    }

}
