<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use App\Models\SuggestionUser;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuggestionClientController extends Controller
{
    use Message;

    public function getSuggestion(){
        $suggestions = Suggestion::all();
        return $this->sendResponse(['suggestions' => $suggestions], trans('message.messageSuccessfully'));
    }

    public function suggestion(Request $request){
        $v = Validator::make($request->all(),[
            'suggestion_id' => 'required|integer|exists:suggestions,id',
            'notes' => 'required|string|min:5',
        ]);

        if($v->fails()) {
            return $this->sendError(trans('message.messageError'), $v->errors());
        }

        $data = SuggestionUser::create([
            'suggestion_id'=> $request->suggestion_id,
            'notes'=> $request->notes,
            'user_id'=> auth()->id(),
        ]);
        $id = $data->id;
        $message = "يوجد اقتراح او شكوى من العميل" .auth()->user()->name;
        $image = auth()->user()->image_path;
        User::whereAuthId(1)
            ->whereRelation('roles.notify','name','Add Suggestion')
            ->each(function ($admin) use($id,$message,$image){
                $admin->notify(new AddNotification('',$id,'showSuggestionClient',$message,$image));
            });

        return $this->sendResponse([], trans('message.messageSuccessfully'));
    }
}
