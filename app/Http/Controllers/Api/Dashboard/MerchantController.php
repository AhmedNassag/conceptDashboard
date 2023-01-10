<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Province;
use App\Models\User;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{

    use Message;

    public function activationMerchant($id)
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
        $users = User::whereAuthId(2)->whereJsonContains('role_name','merchant')
            ->with(['merchant','complement.sellingMethod'])->where(function ($q) use($request) {
                $q->when($request->search, function ($q) use ($request) {
                    return $q->OrWhere('name', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })->latest('id','ASC')->paginate(15);

        return $this->sendResponse(['users' => $users],'Data exited successfully');

    }


    public function store(Request $request)
    {

        // Validator request
        $v = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'province_id'  => 'required|integer|exists:provinces,id',
            'area_id'  => 'required|integer|exists:areas,id',
            'phone' => 'required|string|unique:users',
            'address' => 'required|string|min:8|max:300',
            'nameCompany' => 'required|string|unique:complements',
            'delegateCard' => 'required|file|mimes:png,jpg,jpeg',
            'commercialRegister' => 'required|file|mimes:png,jpg,jpeg',
            'taxCard' => 'required|file|mimes:png,jpg,jpeg',
            'valueAdded' => 'required|file|mimes:png,jpg,jpeg',
        ]);

        if($v->fails()) {
            return $this->sendError('There is an error in the data',$v->errors());
        }

        // start create user
        $user =  User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => $request->password,
            "auth_id" => 2,
            //
            'role_name'=> ['merchant']/*['client']*/,
            'type' => 'merchant',
            //
            "status" => 0,
            'phone' => $request->phone,
            "code" => '+2'
        ]);

        $user->complement()->create([
            'nameCompany' => $request->nameCompany,
            'province_id' => $request->province_id,
            'area_id' => $request->area_id,
            'selling_method_id' => 2,
            'device' => ($request->device  == 1 ? 1:0)
        ]);

        $merchant = Merchant::create([
            'address' => $request->address,
            'user_id' => $user->id
        ]);

        //
        // start create user
        // $user->client()->create(['address' => $request->address, 'province_id' => $request->province_id, 'area_id' => $request->area_id, 'selling_method_id' => 2]);
        // $user->clientAccounts()->create([
        //     'amount' => $request->amount ? $request->amount : 0
        // ]);
        //

        if ($request->hasFile('delegateCard')) {

            $file_size = $request->delegateCard->getSize();
            $file_type = $request->delegateCard->getMimeType();
            $image = time() . 1 . '.' . $request->delegateCard->getClientOriginalName();

            // picture move
            $request->delegateCard->storeAs('merchant', $image, 'general');

            $merchant->media()->create([
                'file_name' => asset('upload/merchant/' . $image),
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 1
            ]);

        }

        if ($request->hasFile('commercialRegister')) {

            $file_size = $request->commercialRegister->getSize();
            $file_type = $request->commercialRegister->getMimeType();
            $image = time() . 2 . '.' . $request->commercialRegister->getClientOriginalName();

            // picture move
            $request->commercialRegister->storeAs('merchant', $image, 'general');

            $merchant->media()->create([
                'file_name' => asset('upload/merchant/' . $image),
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 2
            ]);

        }

        if ($request->hasFile('taxCard')) {

            $file_size = $request->taxCard->getSize();
            $file_type = $request->taxCard->getMimeType();
            $image = time() . 3 . '.' . $request->taxCard->getClientOriginalName();

            // picture move
            $request->taxCard->storeAs('merchant', $image, 'general');

            $merchant->media()->create([
                'file_name' => asset('upload/merchant/' . $image),
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 3
            ]);

        }

        if ($request->hasFile('valueAdded')) {

            $file_size = $request->valueAdded->getSize();
            $file_type = $request->valueAdded->getMimeType();
            $image = time() . 4 . '.' . $request->valueAdded->getClientOriginalName();

            // picture move
            $request->valueAdded->storeAs('merchant', $image, 'general');

            $merchant->media()->create([
                'file_name' => asset('upload/merchant/' . $image),
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 4
            ]);

        }

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
        $user = User::with(['merchant','complement' => function ($q){
            $q->with(['area','province','sellingMethod']);
        }])->findOrFail($id);
        return $user;

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
        $user =  User::with(['merchant','complement','clientAccounts'])->find($id);
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
                'email' => 'required|string|email|unique:users,email,'.$user->id,
                'province_id'  => 'required|integer|exists:provinces,id',
                'area_id'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users,phone,'.$user->id,
                'address' => 'required|string|min:8|max:300',
                'nameCompany' => 'required|string|unique:complements,nameCompany,'.$user->complement->id,
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                'phone' => $request->phone,
            ]);

            $merchant = Merchant::whereUserId($user->id)->update([
                'address' => $request->address,
                'user_id' => $user->id
            ]);

            //
            // start create user
            // $user->client()->update(['address' => $request->address, 'province_id' => $request->province_id, 'area_id' => $request->area_id, 'selling_method_id' => 2]);
            // $user->clientAccounts()->create([
            //     'amount' => $request->amount ? $request->amount : 0
            // ]);
            //

            return $this->sendResponse([],'Data exited successfully');

        }

    }


}
