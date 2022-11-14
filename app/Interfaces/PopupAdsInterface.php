<?php

namespace App\Interfaces;

interface PopupAdsInterface
{
    public function getPopupAds($request);
    public function getPopupAdsById($id);
    public function deletePopupAds($id);
    public function createPopupAds(array $request_data);
    public function updatePopupAds($id, array $newDetails);
}
