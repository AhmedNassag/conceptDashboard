<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Client;
use App\Models\Company;
use App\Models\Order;
use App\Models\Supplier;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    use Message;

    public function getDashboard ()
    {

        $company = Company::count();
        $category = Category::count();
        $client = Client::count();
        $supplier = Supplier::count();
        $orderSuccess = Order::whereOrderStatusId(5)->count();
        $orderFailedPartial = Order::whereOrderStatusId(7)->count();
        $orderFailed = Order::whereOrderStatusId(6)->count();

        $orderQauntity = Order::
        select(
            DB::raw('COUNT(id) as orderCount'),
            DB::raw('Month(created_at) as month')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('orderCount','month');

        $orderPrice = Order::
        select(
            DB::raw('sum(total) as price'),
            DB::raw('Month(created_at) as month')
        )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('price','month');

        return $this->sendResponse(
            [
                'company' => $company,
                'category' => $category,
                'supplier' => $supplier,
                'orderSuccess' => $orderSuccess,
                'orderFailedPartial' => $orderFailedPartial,
                'orderFailed' => $orderFailed,
                'orderPrice' => $orderPrice,
                'orderQauntity' => $orderQauntity,
                'client' => $client
            ],
            'Data exited successfully');
    }

    public function getChart(Request $request)
    {

        $orderQauntity = Order::
        select(
            DB::raw('COUNT(id) as orderCount'),
            DB::raw('Month(created_at) as month')
        )
            ->whereYear('created_at', $request->year)
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('orderCount','month');

        $orderPrice = Order::
        select(
            DB::raw('sum(total) as price'),
            DB::raw('Month(created_at) as month')
        )
            ->whereYear('created_at', $request->year)
            ->groupBy(DB::raw('Month(created_at)'))
            ->pluck('price','month');


        return $this->sendResponse(
            [
                'orderPrice' => $orderPrice,
                'orderQauntity' => $orderQauntity,
            ],
            'Data exited successfully');
    }
}
