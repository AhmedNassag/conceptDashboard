<?php

namespace App\Http\Controllers\Api\Representative;

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

class AuthRepresentativeController extends Controller
{
    use Message;

    public function signIn(Request $request){
        // Validator request
        $v = Validator::make($request->all(),[
            'phone' => 'required|string|min:11|exists:users,phone',
            'password' => 'required|string|min:8',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.Please check your phone number and password'),$v->errors(),401);
        }

        //start access token
        $credentials = $request->only("phone", "password");

        if ($token = Auth::guard('api')->attempt($credentials)) {

            $user = Auth::guard('api')->user();

            if($user->status == 1  && $user->auth_id == 1){
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
}
