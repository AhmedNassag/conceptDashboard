<?php

namespace App\Repositories;

use App\Interfaces\AdsInterface;
use App\Models\Ad;
use Illuminate\Support\Facades\File;

class AdsRepository implements AdsInterface
{
    public function getAds($request)
    {
        return Ad::where('place',$request->place)->with(['media','product:id,name'])->when($request->search,function ($q) use($request){
            return $q->whereRelation('product','name','like','%'.$request->search.'%');
        })->latest()->paginate(10);
    }

    public function getAdsById($id)
    {
        return Ad::with(['media','product:id,name'])->findOrFail($id);
    }

    public function deleteAds($id)
    {
        $ads = Ad::findOrFail($id);
        if (File::exists('upload/ads/' . $ads->media->file_name)) {
            unlink('upload/ads/' . $ads->media->file_name);
        }
        $ads->media->delete();
        $ads->delete();
    }

    public function createAds(array $request_data)
    {
        $ads = Ad::create($request_data);

        $file_size = $request_data['file']->getSize();
        $file_type = $request_data['file']->getMimeType();
        $image = time() . '.' . $request_data['file']->getClientOriginalName();

        // picture move
        $request_data['file']->storeAs('ads', $image, 'general');

        $ads->media()->create([
            'file_name' => $image,
            'file_size' => $file_size,
            'file_type' => $file_type,
            'file_sort' => 1
        ]);
    }

    public function updateAds($id, array $newDetails)
    {

        $ads = Ad::findOrFail($id);
        $ads->update($newDetails);

        if ($newDetails['has_file']) {
            if (File::exists('upload/ads/' . $ads->media->file_name)) {
                unlink('upload/ads/' . $ads->media->file_name);
            }
            $ads->media->delete();
            $file_size = $newDetails['file']->getSize();
            $file_type = $newDetails['file']->getMimeType();
            $image = time() . '.' . $newDetails['file']->getClientOriginalName();

            // picture move
            $newDetails['file']->storeAs('ads', $image, 'general');

            $ads->media()->create([
                'file_name' => $image,
                'file_size' => $file_size,
                'file_type' => $file_type,
                'file_sort' => 1
            ]);
        }
    }
}
