<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Interfaces\SettingInterface;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use Message;

    protected $settingRepository;

    public function __construct(SettingInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function index()
    {
        return $this->sendResponse(['setting' => $this->settingRepository->getSetting()], 'Data exited successfully');
    }

    public function edit($id)
    {
        return $this->sendResponse(['setting' => $this->settingRepository->getSettingById($id)], 'Data exited successfully');
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validator request
            $v = Validator::make($request->all(), [
                'close' => 'required|boolean',
                'show_price' => 'required|boolean',
                'facebook' => 'required|string|min:1',
                'phone' => 'required|regex:/(01)[0-9]{9}/',
                'hotLine' => 'required',
                'wats_app' => 'required|regex:/(01)[0-9]{9}/',
                'twitter' => 'required',
                'instgram' => 'required',
                'linkedIn' => 'required',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['close','facebook','twitter','instgram','linkedIn','phone','hotLine','wats_app','show_price']);
            $this->settingRepository->updateSetting($id, $data);

            DB::commit();
            return $this->sendResponse([], 'Data exited successfully');
        } catch (\Exception $e) {

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

}
