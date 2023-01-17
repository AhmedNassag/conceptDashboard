<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Client;
use App\Models\Competition;
use App\Models\Share;
use App\Models\User;
use App\Notifications\Admin\AddNotification;
use App\Traits\Message;
use App\Traits\NotificationTrait;
=======
use App\Models\Competition;
use App\Models\Share;
use App\Traits\Message;
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;

class ShareController extends Controller
{
    use Message;
<<<<<<< HEAD
    use NotificationTrait;
=======
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8

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
<<<<<<< HEAD
                $newShare =Share::create([
=======
                Share::create([
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
                    'user_id' => $request->user_id,
                    'competition_id' => $request->competition_id,
                    'code' => random_int(1000000, 9999999),
                    'updated_at' => now()
                ]);
<<<<<<< HEAD

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
=======
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
            }
            else
            {
                $share->update([
                    'competition_id' => $request->competition_id,
                    'points' => Null,
                    'code' => random_int(1000000, 9999999),
                ]);
<<<<<<< HEAD
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

=======
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
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
<<<<<<< HEAD
        if($wallet)
        {
            return $this->sendResponse(['wallet' => [$wallet]], trans('message.messageSuccessfully'));
        }
        else
        {
            return $this->sendResponse(['wallet' => []], trans('message.messageSuccessfully'));
        }
=======
        return $this->sendResponse(['wallet' => $wallet], trans('message.messageSuccessfully'));
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
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
<<<<<<< HEAD



    public function sendNotification($id,$message,$image)
    {
        User::whereAuthId(1)
        /*->whereRelation('roles.notify','name','Add OnlineOrder')*/
        ->each(function ($admin) use($id,$message,$image){
            $admin->notify(new AddNotification('',$id,'indexShare',$message,$image));
        });
    }
=======
>>>>>>> aab1b434d94deb2ebdee65b98df25f3a738f40b8
}
