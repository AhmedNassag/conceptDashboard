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
        }
        return $this->sendResponse([], 'Data exited successfully');
    }

    public function index(Request $request)
    {
        // get user
        $users = User::whereAuthId(2)->whereJsonContains('role_name','client')->where('type','client')
        ->with(['client','complement.sellingMethod'])->where(function ($q) use($request) {
            $q->when($request->search, function ($q) use ($request) {
                return $q->OrWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
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
                'email' => 'required|string|email|unique:users,email',
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users',
                'address' => 'required|string|min:5|max:255',
                'amount' => 'nullable|numeric'
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "auth_id" => 2,
                'role_name'=> ['client'],
                "status" => 0,
                'phone' => $request->phone,
                "code" => '+2'
            ]);

            $user->complement()->create([
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
                'selling_method_id' => 1,
                'device' => 2
            ]);

            $user->client()->create(['address' => $request->address, 'province_id' => $request->province_id, 'area_id' =>$request->area_id]);

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
        $user = User::with(['client','complement' => function ($q){
            $q->with(['area','province','sellingMethod']);
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
        $user =  User::with(['client','complement','clientAccounts'])->find($id);
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
                    'province_id'  => 'required|integer|exists:provinces,id',
                    'area_id'  => 'required|integer|exists:areas,id',
                    'email' => 'nullable|string|email|unique:users,email,'.$user->id,
                    'phone' => 'required|string|unique:users,phone,'.$user->id,                    'address' => 'required|string|min:8|max:300',
                    'amount' => 'nullable|numeric'
                ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                'phone' => $request->phone,
            ]);

            $user->complement()->update([
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
                'selling_method_id' => 1,
            ]);

            $user->client()->update(['address' => $request->address]);

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
