<?php

namespace App\Http\Controllers\Api\MobileApp;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Share;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Random;

class ShareController extends Controller
{
    use Message;

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
                Share::create([
                    'user_id' => $request->user_id,
                    'competition_id' => $request->competition_id,
                    'code' => random_int(1000000, 9999999),
                    'updated_at' => now()
                ]);
            }
            else
            {
                $share->update([
                    'competition_id' => $request->competition_id,
                    'points' => Null,
                    'code' => random_int(1000000, 9999999),
                ]);
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
        return $this->sendResponse(['wallet' => $wallet], trans('message.messageSuccessfully'));
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
}
