<?php

namespace App\Interfaces;

interface AdsInterface
{
    public function getAds($request);
    public function getAdsById($id);
    public function deleteAds($id);
    public function createAds(array $request_data);
    public function updateAds($id, array $newDetails);
}
