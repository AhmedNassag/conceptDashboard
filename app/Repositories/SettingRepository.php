<?php

namespace App\Repositories;

use App\Interfaces\SettingInterface;
use App\Models\Setting;

class SettingRepository implements SettingInterface
{
    public function getSetting()
    {
        return Setting::whereId(1)->latest()->paginate(10);
    }

    public function getSettingById($id)
    {
        return Setting::findOrFail($id);
    }

    public function updateSetting($id, array $newDetails)
    {
        return Setting::whereId($id)->update($newDetails);
    }
}
