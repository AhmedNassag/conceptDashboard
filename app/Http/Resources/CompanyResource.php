<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'address' => $this->address,
            'phone_second' => $this->phone_second,
            'facebook' => $this->facebook,
            'linkedin' => $this->linkedin,
            'website' => $this->website,
            'whatsapp' => $this->whatsapp
        ];
    }
}
