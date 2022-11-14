<?php

namespace App\Interfaces;

interface SettingInterface
{
    public function getSetting();
    public function getSettingById($id);
    public function updateSetting($id, array $newDetails);
}
