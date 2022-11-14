<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\Province;
use App\Models\User;
use App\Traits\Message;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    use Message;
    use NotificationTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function activationClient($id)
    {
        $user = User::find($id);

        if ($user->status == 1)
        {
            $user->update([
                "status" => 0
            ]);
        }else{
            $user->update([
                "status" => 1
            ]);

            $tokens = [];
            $tokens[] = $user->client->firebase_token;
            $body = "تم تفعيل حسابك بنجاح";
            $type = "active client";
            $productData = $user;
            $this->notification($tokens,$body,$type,$productData);
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    public function index(Request $request)
    {
        // get user
        $users = User::whereAuthId(2)->whereJsonContains('role_name','client')
            ->with(['client'=>function ($q){
            $q->with(['sellingMethod']);
        }])->where(function ($q) use($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->OrWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%')
                        ->orWhereRelation('client','trade_name', 'like', '%' . $request->search . '%');
                });
            })->latest('id','ASC')->paginate(15);

        return $this->sendResponse(['users' => $users],'Data exited successfully');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'trade_name' => 'required|string',
                'email' => 'nullable|string|email|unique:users,email',
                'phone' => 'required|string|unique:users,phone',
                'address' => 'required|string',
                'location' => 'nullable|string',
                'province_id' => 'required|integer|exists:provinces,id',
                'area_id' => 'required|integer|exists:areas,id',
                'amount' => 'nullable|numeric',
                'selling_method_id' => 'required|integer|exists:selling_methods,id',
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user =  User::create([
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
                "code" => '+02',
                "auth_id" => 2,
                'role_name'=> ['client'],
            ]);

            $user->client()->create([
                "address" => $request->address,
                "trade_name" => $request->trade_name,
                "province_id" => $request->province_id,
                "area_id" => $request->area_id,
                "location" => $request->location,
                "selling_method_id" => $request->selling_method_id,
            ]);

            $user->clientAccounts()->create([
                'amount' => $request->amount ? $request->amount : 0
            ]);

            return $this->sendResponse([],'Data exited successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['client'=>function ($q){
                $q->with(['province','area','sellingMethod']);
            }])->findOrFail($id);

        return $this->sendResponse(['user' => $user],'Data exited successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user =  User::with(['client','clientAccounts'])->find($id);
        $provinces = Province::select('id','name')->get();

        return $this->sendResponse(['user' => $user,'provinces' => $provinces],'Data exited successfully');
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
        $user = User::find($id);
        if($user){
            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'trade_name' => 'required|string',
                'email' => 'nullable|string|email|unique:users,email,'.$user->id,
                'phone' => 'required|string|unique:users,phone,'.$user->id,
                'address' => 'required|string',
                'location' => 'nullable|string',
                'province_id' => 'required|integer|exists:provinces,id',
                'area_id' => 'required|integer|exists:areas,id',
                'amount' => 'nullable|numeric',
                'selling_method_id' => 'required|integer|exists:selling_methods,id',
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "phone" => $request->phone,
            ]);

            $user->client()->update([
                "address" => $request->address,
                "trade_name" => $request->trade_name,
                "province_id" => $request->province_id,
                "area_id" => $request->area_id,
                "location" => $request->location,
                "selling_method_id" => $request->selling_method_id,
            ]);

            $user->clientAccounts()->first()->update([
                'amount' => $request->amount ? $request->amount : 0
            ]);

            return $this->sendResponse([],'Data exited successfully');

        }

    }

    public function sendClientNotification(Request $request){
        $v = Validator::make($request->all(), [
            'notification' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if($v->fails()) {
            return $this->sendError('There is an error in the data',$v->errors());
        }

        $firebase_token = Client::where('user_id',$request->user_id)->first()->firebase_token;

        $tokens = [];
        $tokens[] = $firebase_token;
        $title = "شهبندر";
        $body = $request->notification ;
        $type = "send notification";
        $productData = [];

        $this->notification($tokens,$body,$title,$type,$productData);
        return $this->sendResponse([],'Data exited successfully');

    }

}
