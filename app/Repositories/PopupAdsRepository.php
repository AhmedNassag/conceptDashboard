<?php

namespace App\Repositories;

use App\Interfaces\PopupAdsInterface;
use App\Models\PopupAds;
use Illuminate\Support\Facades\File;

class PopupAdsRepository implements PopupAdsInterface
{
    public function getPopupAds($request)
    {
        return PopupAds::with(['media','product:id,name'])->when($request->search,function ($q) use($request){
            return $q->whereRelation('product','name','like','%'.$request->search.'%');
        })->latest()->paginate(10);
    }

    public function getPopupAdsById($id)
    {
        return PopupAds::with(['media','product:id,name'])->findOrFail($id);
    }

    public function deletePopupAds($id)
    {
        $ads = PopupAds::findOrFail($id);
        if (File::exists('upload/popupAds/' . $ads->media->file_name)) {
            unlink('upload/popupAds/' . $ads->media->file_name);
        }
        $ads->media->delete();
        $ads->delete();
    }

    public function createPopupAds(array $request_data)
    {
        $ads = PopupAds::create($request_data);

        $file_size = $request_data['file']->getSize();
        $file_type = $request_data['file']->getMimeType();
        $image = time() . '.' . $request_data['file']->getClientOriginalName();

        // picture move
        $request_data['file']->storeAs('popupAds', $image, 'general');

        $ads->media()->create([
            'file_name' => $image,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'file_sort' => 1
        ]);
    }

    public function updatePopupAds($id, array $newDetails)
    {

        $ads = PopupAds::findOrFail($id);
        $ads->update($newDetails);

        if ($newDetails['has_file']) {
            if (File::exists('upload/popupAds/' . $ads->media->file_name)) {
                unlink('upload/popupAds/' . $ads->media->file_name);
            }
            $ads->media->delete();
            $file_size = $newDetails['file']->getSize();
            $file_type = $newDetails['file']->getMimeType();
            $image = time() . '.' . $newDetails['file']->getClientOriginalName();

            // picture move
            $newDetails['file']->storeAs('popupAds', $image, 'general');

            $ads->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 1
            ]);
        }
    }
}
