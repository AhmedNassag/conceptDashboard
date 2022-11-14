<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthMobileController extends Controller
{
    use Message;

    public function signUp(Request $request){

        // check user offline

        $user_offline = User::where([
            ['phone',$request->phone],
            ['password',null]
        ])->first();
        if ($user_offline){
            return $this->checkUserOffline($user_offline,$request->all());
        }

        $v = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:190',
            'trade_name' => 'required|string|min:3|max:190',
            'firebase_token' => 'required|string',
            'phone' => 'required|string|min:11|unique:users,phone',
            'address' => 'required|string|min:3|max:300',
            'location' => 'required|string|min:3|max:300',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'province_id' => 'required|integer|exists:provinces,id',
            'area_id' => 'required|integer|exists:areas,id',
            'selling_method_id' => 'required|integer|exists:selling_methods,id',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $request_data = $request->only(['name','phone','address','location','password','trade_name','province_id','area_id','selling_method_id','firebase_token']);
        $request_data['code'] = "+02";
        $request_data['auth_id'] = 2;
        $request_data['role_name'] = ["client"];
        $request_data['status'] = 0;

        $user = User::create($request_data);
        $user->client()->create($request_data);

        //store image

        $file_size = $request->image->getSize();
        $file_type = $request->image->getMimeType();
        $image = time() . '.' . $request->image->getClientOriginalName();

        // picture move
        $request->image->storeAs('user', $image, 'general');

        $user->media()->create([
            'file_name' => $image,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'file_sort' => 1
        ]);

        $user->clientAccounts()->create([
            'amount' => 0,
        ]);

        $id = $user->id;
        $message = " تم تسجيل عميل جديد باسم " .$user->name;
        $image = $user->image_path;
        User::whereAuthId(1)
            ->whereRelation('roles.notify','name','Add Client')
            ->each(function ($admin) use($id,$message,$image){
                $admin->notify(new AddNotification('',$id,'showClient',$message,$image));
            });

        return $this->sendResponse([],trans('message.waitActive'));
    }

    public function checkUserOffline($user_offline,$request){

        $v = Validator::make($request,[
            'name' => 'required|string|min:3|max:190',
            'trade_name' => 'required|string|min:3|max:190',
            'phone' => 'required|string|min:11',
            'address' => 'required|string|min:3|max:300',
            'location' => 'required|string|min:3|max:300',
            'password' => 'required|string|min:8|confirmed',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'province_id' => 'required|integer|exists:provinces,id',
            'area_id' => 'required|integer|exists:areas,id',
            'selling_method_id' => 'required|integer|exists:selling_methods,id',
            'firebase_token' => 'required|string',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $request_data = $request;
        $request_data['code'] = "+02";
        $request_data['auth_id'] = 2;
        $request_data['role_name'] = ["client"];
        $request_data['status'] = 0;

        $user_offline->update($request_data);
        $user_offline->client()->update([
            'province_id' => $request->province_id,
            'area_id' => $request->area_id ,
            'trade_name' => $request->trade_name,
            'address' => $request->address,
            'location' => $request->location,
            'selling_method_id' => $request->selling_method_id,
            'firebase_token' => $request->firebase_token,
        ]);

        //store image

        if(File::exists('upload/user/'.$user_offline->media->file_name)){
            unlink('upload/user/'. $user_offline->media->file_name);
        }
        $user_offline->media->delete();

        $file_size = $request['image']->getSize();
        $file_type = $request['image']->getMimeType();
        $image = time() . '.' . $request['image']->getClientOriginalName();

        // picture move
        $request['image']->storeAs('user', $image, 'general');

        $user_offline->media()->create([
            'file_name' => $image,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'file_sort' => 1
        ]);

        $id = $user_offline->id;
        $message = " تم تسجيل عميل جديد باسم " .$user_offline->name;
        $image = $user_offline->image_path;
        User::whereAuthId(1)
            ->whereRelation('roles.notify','name','Add Client')
            ->each(function ($admin) use($id,$message,$image){
                $admin->notify(new AddNotification('',$id,'showClient',$message,$image));
            });

        return $this->sendResponse([],trans('message.waitActive'));
    }

    public function signIn(Request $request){
        // Validator request
        $v = Validator::make($request->all(),[
            'phone' => 'required|string|min:11|exists:users,phone',
            'password' => 'required|string|min:8',
            'firebase_token' => 'required|string',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.Please check your phone number and password'),$v->errors(),401);
        }

        //start access token
        $credentials = $request->only("phone", "password");

        if ($token = Auth::guard('api')->attempt($credentials)) {

            $user = Auth::guard('api')->user();

            if($user->status == 1  && $user->auth_id == 2){
                $user->client()->update([
                    'firebase_token' => $request->firebase_token,
                ]);
                return  $this->sendResponse($this->respondWithToken($token),trans('message.messageSuccessfully'));

            }else{
                return $this->sendError(trans('message.NotActive'));
            }

        }else{

            return $this->sendError(trans('message.Please check your phone number and password'));
        }
    }

    public function me(){
        $user_id = \auth()->user()->id;
        $user=User::with(['client'=>function ($q){
            $q->with(['province','area']);
        }])->find($user_id);
        $user['image_path'] = asset('upload/user/'.$user->media->file_name);
        return $this->sendResponse(['user' => $user], trans('message.messageSuccessfully'));
    }

    // create token
    protected function respondWithToken($token)
    {

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource(auth()->guard('api')->user()),
            'permission' =>  PermissionResource::collection((auth()->guard('api')->user()->getAllPermissions()))
        ];

    }//**********end respondWithToken************/

    /**
     * change Password User
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' =>'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendError(trans('message.messageError'), $validator->errors());
        }

        $user = \auth()->user();

        if (Hash::check($request->old_password, $user->password))
        {
            $user->update([
                'password' => $request->input('password'),
            ]);

            return $this->sendResponse([], trans('message.messageSuccessfully'));

        }else{
            return $this->sendError(trans('message.sorry the old password is not correct'));

        }
    }

    /**
     * check Phone User
     */

    public function checkPhone($phone){
        $user = User::where('phone',$phone)->first();
        if ($user == null){
            return $this->sendError(trans('message.messageError'));
        }
        return $this->sendResponse([], trans('message.messageSuccessfully'));
    }
}
