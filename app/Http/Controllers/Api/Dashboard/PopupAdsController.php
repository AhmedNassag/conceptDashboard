<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Interfaces\PopupAdsInterface;
use App\Traits\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PopupAdsController extends Controller
{
    use Message;
    protected $adsRepository;

    public function __construct(PopupAdsInterface $adsRepository)
    {
        $this->adsRepository = $adsRepository;
    }

    public function index(Request $request)
    {
        return $this->sendResponse(['ads' => $this->adsRepository->getPopupAds($request)],'Data exited successfully');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // Validator request
            $v = Validator::make($request->all(), [
                'product_id' =>  'nullable|exists:products,id',
                'file' => 'required|mimes:jpeg,jpg,png,webp|max:5048',
            ]);

            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id','file']);
            $this->adsRepository->createPopupAds($data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }

    }

    public function show($id)
    {
        return $this->sendResponse(['ad' => $this->adsRepository->getPopupAdsById($id)],'Data exited successfully');
    }

    public function edit($id)
    {
        return $this->sendResponse(['ad' => $this->adsRepository->getPopupAdsById($id)],'Data exited successfully');
    }

    public function update(Request $request,$id)
    {
        DB::beginTransaction();
        try {
            // Validator request
            $v = Validator::make($request->all(), [
                'product_id' =>  'nullable|exists:products,id',
                'file' => 'nullable'.($request->hasFile('file')?'|file|mimes:jpeg,jpg,png|max:5048':''),
            ]);
            if ($v->fails()) {
                return $this->sendError('There is an error in the data', $v->errors());
            }

            $data = $request->only(['product_id','file']);
            $data['has_file'] = $request->hasFile('file') ? 1 : 0;
            $this->adsRepository->updatePopupAds($id,$data);

            DB::commit();
            return $this->sendResponse([],'Data exited successfully');
        }catch (\Exception $e){

            DB::rollBack();
            return $this->sendError('An error occurred in the system');
        }
    }

    public function destroy($id)
    {
        $this->adsRepository->deletePopupAds($id);
        return $this->sendResponse([],'Data exited successfully');
    }
}
