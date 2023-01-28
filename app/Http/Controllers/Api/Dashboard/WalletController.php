<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\PointPrice;
use App\Models\Wallet;
use App\Traits\Message;
use Illuminate\Http\Request;


class WalletController extends Controller
{

    use Message;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wallets = Wallet::with(['user'])
        ->when($request->search, function ($q) use ($request) {
            return $q->where('product_points', 'like', '%' . $request->search . '%')
                ->orWhere('welcome_points', 'like', '%' . $request->search . '%')
                ->orWhereRelation('user', 'name', 'like', '%' . $request->search . '%');
        })->latest()->paginate(10);

        $value = PointPrice::latest()->paginate(10);

        return $this->sendResponse(['wallets' => $wallets, 'value' => $value], 'Data exited successfully');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
