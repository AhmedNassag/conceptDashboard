<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Order;
use App\Models\SuggestionUser;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AreaReportController extends Controller
{

    use Message;

    public function storeReport(Request $request)
    {

        $order = $request->order == 'asc' ? 'asc' : 'desc';

        $clients = Order::
             join('stores','stores.id','orders.store_id')
            ->whereOrderStatusId(5)
            ->select(DB::raw('SUM(orders.total) as price,stores.id,stores.name'))
            ->groupBy(['stores.id','stores.name'])
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

    public function suggestionReport(Request $request)
    {
        $clients = SuggestionUser::
           with(['user:id,name','suggestion:id,name'])
           ->where(function ($q) use ($request) {
                $q->when($request->from_date && $request->to_date, function ($q) use ($request) {
                    $q->whereDate('created_at', ">=", $request->from_date)
                        ->whereDate('created_at', "<=", $request->to_date);
                });
            })
            ->paginate(15);

        return $this->sendResponse(['clients' => $clients], 'Data exited successfully');

    }

}
