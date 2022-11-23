<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\User;
use App\Models\UserCompany;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserCompanyController extends Controller
{

    use Message;

    public function activationCompany($id)
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
        $users = User::whereAuthId(2)->whereJsonContains('role_name','company')
            ->with(['userCompany','complement.sellingMethod'])->where(function ($q) use($request) {
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
            'phone_second' => 'present|different:phone',
            'address' => 'required|string|min:8|max:300',
            'job' => 'required|string',
            'nameCompany' => 'required|string|unique:complements',
            "facebook" => 'nullable|url',
            "linkedin" => 'nullable|url',
            "website" => 'nullable|url',
            "whatsapp" => 'nullable|url'
        ]);

        if($v->fails()) {
            return $this->sendError('There is an error in the data',$v->errors());
        }

        // start create user
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "auth_id" => 2,
            'role_name' => ['company'],
            "status" => 0,
            'phone' => $request->phone,
            "code" => '+2'
        ]);

        $user->complement()->create([
            'nameCompany' => $request->nameCompany,
            'province_id' => $request->province_id,
            'area_id' => $request->area_id,
            'selling_method_id' => 2,
            'device' => 2
        ]);

        UserCompany::create([
            'user_id' => $user->id,
            'job' => $request->job,
            'address' => $request->address,
            'phone_second' => $request->phone_second,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'website' => $request->website,
            'whatsapp' => $request->whatsapp
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
        $user = User::with(['userCompany','complement' => function ($q){
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
        $user =  User::with(['userCompany','complement'])->find($id);
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
                'phone_second' => 'present|different:phone',
                'address' => 'required|string|min:8|max:300',
                'job' => 'required|string',
                'nameCompany' => 'required|string|unique:complements,nameCompany,'.$user->complement->id,
                "facebook" => 'nullable|url',
                "linkedin" => 'nullable|url',
                "website" => 'nullable|url',
                "whatsapp" => 'nullable|url'
            ]);

            if($v->fails()) {
                return $this->sendError('There is an error in the data',$v->errors());
            }

            // start create user
            $user->update([
                "name" => $request->name,
                "email" => $request->email,
                'phone' => $request->phone,
                "code" => '+2'
            ]);

            $user->complement()->first()->update([
                'nameCompany' => $request->nameCompany,
                'province_id' => $request->province_id,
                'area_id' => $request->area_id,
            ]);

            UserCompany::whereUserId($user->id)->first()->update([
                'job' => $request->job,
                'address' => $request->address,
                'phone_second' => $request->phone_second,
                'facebook' => $request->facebook,
                'linkedin' => $request->linkedin,
                'website' => $request->website,
                'whatsapp' => $request->whatsapp
            ]);

            return $this->sendResponse([],'Data exited successfully');

        }

    }


}
