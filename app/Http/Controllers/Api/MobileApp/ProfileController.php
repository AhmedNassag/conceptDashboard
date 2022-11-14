<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use Message;

    public function updateProfile (Request $request){
        $v = Validator::make($request->all(),[
            'name' => 'required|string|min:3|max:190',
            'trade_name' => 'required|string|min:3|max:190',
            'address' => 'required|string|min:3|max:300',
            'location' => 'required|string|min:3|max:300',
            'image' => 'nullable' . ($request->hasFile('image') ? '|mimes:jpeg,jpg,png,gif|max:10000' : ''),
            'province_id' => 'required|integer|exists:provinces,id',
            'area_id' => 'required|integer|exists:areas,id',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $user = auth()->user();
        $user->update($request->all());
        $user->client()->update([
            'province_id' => $request->province_id,
            'area_id' => $request->area_id ,
            'trade_name' => $request->trade_name,
            'address' => $request->address,
            'location' => $request->location,
        ]);

        if ($request->hasFile('image')) {
            if (File::exists('upload/user/' . $user->media->file_name)) {
                unlink('upload/user/' . $user->media->file_name);
            }
            $user->media->delete();
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
        }

        return $this->sendResponse($user,trans('message.messageSuccessfully'));
    }
}
