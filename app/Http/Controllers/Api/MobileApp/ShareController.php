<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Competition;
use App\Models\Share;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use App\Traits\NotificationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;

class ShareController extends Controller
{
    use Message;
    use NotificationTrait;

    /**
     * store share and get code
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validator request
            $v = Validator::make($request->all(), [
                'user_id'  => 'required|integer|exists:users,id',
                'competition_id' => 'required|integer|exists:competitions,id',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors(), 401);
            }

            $share = Share::where('user_id',$request->user_id)->first();
            if(!$share)
            {
                $newShare =Share::create([
                    'user_id' => $request->user_id,
                    'competition_id' => $request->competition_id,
                    'code' => random_int(1000000, 9999999),
                    'updated_at' => now()
                ]);

                $firebase_token = Client::where('user_id',auth()->user()->id)->first()->firebase_token;
                $tokens = [];
                $tokens[] = $firebase_token;
                $title = "كونسبت";
                $body = $newShare->code ;
                $type = "send notification";
                $productData = [];
                $this->notification($tokens,$body,$title,$type,$productData);

                $id = $newShare->id;
                $message = " يوجد إشتراك جديد فى مسابقة من " .auth()->user()->name;
                $image = auth()->user()->image_path;
                $this->sendNotification($id,$message,$image);
            }
            else
            {
                $share->update([
                    'competition_id' => $request->competition_id,
                    'points' => Null,
                    'code' => random_int(1000000, 9999999),
                ]);
//                Client::whereNotNull('firebase_token')->get()->pluck('firebase_token')->toArray();
                $firebase_token = Client::where('user_id',auth()->user()->id)->first()->firebase_token;
                $tokens = [];
                $tokens[] = $firebase_token;
                $title = "كونسبت";
                $body = "الكود الخاص بك هو " . $share->code ;
                $type = "send notifications";
                $productData = [];
                $this->notification($tokens,$body,$title,$type,$productData);

                $id = $share->id;
                $message = " يوجد إشتراك جديد فى مسابقة من " .auth()->user()->name;
                $image = auth()->user()->image_path;
                $this->sendNotification($id,$message,$image);
//                $share->user->notify(new AddNotification('',$id,'indexShare',$message,$image));

            }

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }



    public function wallet($id)
    {
        $wallet = Share::where('user_id', $id)->first();
        if($wallet)
        {
            return $this->sendResponse(['wallet' => [$wallet]], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['wallet' => []], trans('message.messageSuccessfully'));
        }
    }


    public function allCompetition()
    {
        $competitions = Competition::with('media:file_name,mediable_id')
        ->where([
            ['status', 1],
        ])
        ->get();
        return $this->sendResponse(['competitions' => $competitions], trans('message.messageSuccessfully'));
    }


    public function getCompetitionByCount($count)
    {
        $competitions = Competition::with('media:file_name,mediable_id')
        ->where([
            ['status', 1],
            ['count', $count],
        ])
        ->get();
        return $this->sendResponse(['competitions' => $competitions], trans('message.messageSuccessfully'));
    }



    public function getCompetitionByCountAndDays($count,$days)
    {
        $competitions = Competition::with('media:file_name,mediable_id')
        ->where([
            ['status', 1],
            ['count', $count],
            ['days', $days],
        ])
        ->get();
        return $this->sendResponse(['competitions' => $competitions], trans('message.messageSuccessfully'));
    }



    public function sendNotification($id,$message,$image)
    {
        User::whereAuthId(1)
        /*->whereRelation('roles.notify','name','Add OnlineOrder')*/
        ->each(function ($admin) use($id,$message,$image){
            $admin->notify(new AddNotification('',$id,'indexShare',$message,$image));
        });
    }
}
