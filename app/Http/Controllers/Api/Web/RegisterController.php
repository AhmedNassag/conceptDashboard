<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Merchant;
use App\Models\Province;
use App\Models\User;
use App\Models\UserCompany;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use Message;

    public function clientRegister(Request $request)
    {

        DB::beginTransaction();

        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'repeat_password' => 'required|same:password',
                'province'  => 'required|integer|exists:provinces,id',
                'area'  => 'required|integer|exists:areas,id',
                'phone' => 'required|string|unique:users',
                'address' => 'required|string|min:8|max:300',
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
                'role_name'=> ['client'],
                "status" => 0,
                'phone' => $request->phone,
                "code" => '+2'
            ]);

            $user->complement()->create([
                'province_id' => $request->province,
                'area_id' => $request->area,
                'selling_method_id' => 1,
                'device' => ($request->device  == 1 ? 1:0)
            ]);

            $user->client()->create(['address' => $request->address]);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        }
        catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');

        }

    }// end companyRegister

    public function merchantRegister(Request $request)
    {

        DB::beginTransaction();

        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'repeat_password' => 'required|same:password',
                'province'  => 'required|integer|exists:provinces,id',
                'area'  => 'required|integer|exists:areas,id',
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
                'role_name'=> ['merchant'],
                "status" => 0,
                'phone' => $request->phone,
                "code" => '+2'
            ]);

            $user->complement()->create([
                'nameCompany' => $request->nameCompany,
                'province_id' => $request->province,
                'area_id' => $request->area,
                'selling_method_id' => 2,
                'device' => ($request->device  == 1 ? 1:0)
            ]);

            $merchant = Merchant::create([
                'address' => $request->address,
                'user_id' => $user->id
            ]);

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

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        }catch(\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');

        }
    }// end designRegister

    public function companyRegister(Request $request)
    {

        DB::beginTransaction();

        try {


            // Validator request
            $v = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:8',
                'repeat_password' => 'required|same:password',
                'province'  => 'required|integer|exists:provinces,id',
                'area'  => 'required|integer|exists:areas,id',
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

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            // start create user
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password,
                "auth_id" => 2,
                'role_name' => ['company'],
                "status" => 0,
                'phone' => $request->phone,
                "code" => $request->code
            ]);

            $user->complement()->create([
                'nameCompany' => $request->nameCompany,
                'province_id' => $request->province,
                'area_id' => $request->area,
                'selling_method_id' => 2,
                'device' => ($request->device  == 1 ? 1:0)
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

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');

        }catch(\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }// end advertiserRegister

    public function province()
    {
        $province = Province::select('id','name')->get();
        return $this->sendResponse(['provinces' => $province],'Data exited successfully');
    }

    public function area($id)
    {
        $area = Area::select('id','name','province_id')->whereProvinceId($id)->get();
        return $this->sendResponse(['areas' => $area],'Data exited successfully');
    }

}
