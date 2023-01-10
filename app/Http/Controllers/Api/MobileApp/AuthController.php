<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ComplementResource;
use App\Http\Resources\MerchantResource;
use App\Http\Resources\UserResource;
use App\Models\Client;
use App\Models\Complement;
use App\Models\Merchant;
use App\Models\User;
use App\Models\UserCompany;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as RulesPassword;


class AuthController extends Controller
{
    use Message;

    //start login
    public function login(Request $request)
    {
        try{
            // Validator request
            $v = Validator::make($request->all(),[
                'email' => 'required|string',
                'password' => 'required|string',
            ]);
            if($v->fails()) {
                return $this->sendError(trans('general.forget'),$v->errors(),401);
            }
            $credentials = [];
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                //start access token
                $credentials = $request->only("email", "password");
            }else{
                $phone = User::wherePhone($request->email)->first();
                if($phone){
                    $credentials = ["email" => $phone->email, "password" => $request->password];
                }
            }
            //start access token
            if ($token = Auth::guard('api')->attempt($credentials)) {
                $user = Auth::guard('api')->user();
                if( $user->auth_id == 2){
                    if($user->status  == 1){
                        return  $this->sendResponse($this->respondWithToken($token),'Data exited successfully');
                    }else {
                        return $this->sendError(trans('general.approved'));
                    }
                }else{
                    return $this->sendError(trans('general.forget'));
                }
            }else{
                return $this->sendError(trans('general.forget'));
            }
        }catch (\Exception $e){
            return $this->sendError(trans('general.forget'));
        }
    }//**********end login************//



    //start logout
    public function logout(Request $request)
    {
        auth()->guard('api')->logout();
        return $this->sendResponse([],'User successfully signed out');
    }//**********end logout************/



    //start userByPhone
    public function userByPhone(Request $request)
    {
        try {
            // Validator request
            $v = Validator::make($request->all(), [
                'phone' => 'required',
            ]);
            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }
            $user = User::where('phone', $request->phone)->first();
            if ($user == null) {
                return $this->sendError(trans('message.messageError'));
            }
            return $this->sendResponse(['user' => $user], trans('message.messageSuccessfully'));
        } catch (\Exception $e) {
            return $this->sendError('An error occurred in the system');
        }
    }//**********end userByPhone************/



    //start forgotPassword
    public function forgotPassword(Request $request)
    {
        try {
            // Validator request
            $v = Validator::make($request->all(),[
                'email' => 'required|string|email',
            ]);
            if($v->fails()) {
                return $this->sendError('Your Email is wrong',$v->errors(),401);
            }
            $user = User::whereEmail($request->only('email'))->first();
            if ($user) {
                $number = rand(1000,9999);
                Complement::whereUserId($user->id)->update(['active_code' => $number]);
//                Mail::send('mailer', $data, function ($message) use ($data,$user) {
//                    $message->from('');
//                    $message->to($user['email']);
//                    $message->subject($data['subject']);
//                });
                return  $this->sendResponse([],'we have emailed your password reset link!');
            }
            return $this->sendError('Your Email is wrong',401);
        }catch(\Exception $e){
            return $this->sendError('An error occurred in the system',[],401);
        }
    }//**********end forgotPassword************//



    //start confirmOtp
    public function confirmOtp(Request $request)
    {
        try {
            // Validator request
            $v = Validator::make($request->all(),[
                'email' => 'required|string|email',
                'opt' => 'required|string',
            ]);
            if($v->fails()) {
                return $this->sendError('Your Email is wrong',$v->errors(),401);
            }
            $user = User::whereEmail($request->only('email'))->first();
            if ($user) {
                if($user->complement->active_code == $request->opt){
                    $user->complement()->update(['active_code' => '']);
                    return  $this->sendResponse([],'الكود الذي ادخله صحيح');
                }else{
                    return $this->sendError('الكود الذي ادخله غير صحيح',401);
                }
            }
            return $this->sendError('Your Email is wrong',401);
        }catch(\Exception $e){
            return $this->sendError('An error occurred in the system',[],401);
        }
    }//**********end confirmOtp************//



    //start reset
    public function reset(Request $request)
    {
        // Validator request
        $v = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => ['required','min:8','max:16','confirmed',RulesPassword::defaults()],
        ]);
        if($v->fails()) {
            return $this->sendError('Your Email is wrong',$v->errors(),401);
        }
        $user = User::whereEmail($request->only('email'))->first();
        if ($user) {
            $user->update(['password' => $request->password]);
            return  $this->sendResponse([],'Password reset successfully!');
        }
        return $this->sendError('An error occurred in the system',[],401);
    }//**********end reset************//



    //start authorizeUser
    public function authorizeUser()
    {
        $user = auth()->guard('api')->check();
        if($user){
            return  $this->sendResponse(['success' => true],'Data exited successfully');
        }else{
            return $this->sendError(['error' => true]);
        }
    }//**********end authorizeUser************/



    // create token (company,desgin,advertiser)
    protected function respondWithToken($token)
    {
        $user = auth()->guard('api')->user();
        $complement = Complement::whereUserId($user->id)->first();
        if($user->role_name[0]/*type*/ == 'company'){
            $partner = new CompanyResource(UserCompany::whereUserId($user->id)->first());
        }elseif ($user->role_name[0]/*type*/ == 'client'){
            $partner = new ClientResource(Client::whereUserId($user->id)->first());
        }elseif ($user->role_name[0]/*type*/ == 'merchant'){
            $partner = new MerchantResource(Merchant::whereUserId($user->id)->first());
        }
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new UserResource($user),
            'complement'=> new ComplementResource($complement),
            'partner'=> $partner
        ];
    }//**********end respondWithToken************/
}
