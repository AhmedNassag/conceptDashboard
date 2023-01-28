<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintUser;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintClientController extends Controller
{
    use Message;

    public function getComplaint(){
        $complaints = Complaint::all();
        return $this->sendResponse(['complaints' => $complaints], trans('message.messageSuccessfully'));
    }

    public function complaint(Request $request){
        $v = Validator::make($request->all(),[
            'complaint_id' => 'required|integer|exists:complaints,id',
            'notes' => 'required|string|min:5',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $data = ComplaintUser::create([
            'complaint_id'=> $request->complaint_id,
            'notes'=> $request->notes,
            'user_id'=> auth()->id(),
        ]);
        $id = $data->id;
        $message = "يوجد اقتراح او شكوى من العميل" .auth()->user()->name;
        $image = auth()->user()->image_path;
        User::whereAuthId(1)
            ->whereRelation('roles.notify','name','Add Complaint')
            ->each(function ($admin) use($id,$message,$image){
                $admin->notify(new AddNotification('',$id,'showComplaintClient',$message,$image));
            });

        return $this->sendResponse([], trans('message.messageSuccessfully'));
    }
}
