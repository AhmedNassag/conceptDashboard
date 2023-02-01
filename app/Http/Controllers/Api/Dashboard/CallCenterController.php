<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\PeriodicMaintenance;
use App\Models\problemLead;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CallCenterController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function callCenter(Request $request)
    {
        $callCenters = Order::with([
            'user',
            'store.area.province',
            'orderDetails',
            'orderStatus',
            'representative.user',
            'orderReturn',
            'periodicMaintenance'
        ])
        ->where(function ($q) use ($request) {
            $q->when($request->search, function ($q) use ($request) {
                return $q->whereRelation('user', 'name', 'like', '%' . $request->search . '%')
                ->orwhereRelation('user', 'user_code', 'like', $request->search . '%')
                ->orwhereRelation('user', 'phone', 'like', $request->search . '%');
            });
        })
        ->where(function ($q) use ($request) {
            $q->when($request->search2, function ($q) use ($request) {
                return $q->whereRelation('store', 'name', 'like', $request->search2 . '%')
                ->orwhereRelation('store.area.province', 'name', 'like', $request->search2 . '%')
                ->orwhereRelation('store.area', 'name', 'like', $request->search2 . '%');
            });
        })

        ->latest()->paginate(10);

        return $this->sendResponse(['callCenters' => $callCenters], 'Data exited successfully');
    }

    /**
     * get active Category
     */
    public function profileClient($id)
    {
        $user = User::with(['client', 'complement' => function ($q) {
            $q->with(['area', 'province', 'sellingMethod']);
        }])->findOrFail($id);


        $orders = Order::where('user_id',$id)
        ->with([
            'user',
            'store.area.province',
            'orderDetails',
            'orderStatus',
            'representative.user',
            'orderReturn',
            'periodicMaintenance'
        ])->get();

        $maintenances = PeriodicMaintenance::where('user_id', $id)
        ->with([
            'order.representative.user',
            'user',
            'employee'
        ])->get();

        $problems = problemLead::where('name', $user->name)
        ->with([
            'employee.user',
            'sellerCategory',
            'comments'
        ])->get();

        return $this->sendResponse([
            'user' => $user,
            'orders' => $orders,
            'maintenances' => $maintenances,
            'problems' => $problems
        ], 'Data exited successfully');
    }

}
