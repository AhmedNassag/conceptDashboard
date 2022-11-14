<?php

namespace App\Http\Resources;

use App\Models\SellingMethod;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplementResource extends JsonResource
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
            'province' => new ProvinceResource($this->province),
            'area' => new AreaResource($this->area),
            'selling_method' => new SellingMethodResource($this->sellingMethod),
            'nameCompany' => $this->nameCompany
        ];
    }
}
