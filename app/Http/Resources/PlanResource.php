<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name .' '. number_format($this->price,0,'.',',') . ' تومان',
            'price' => $this->price,
            'has_discount' => $this->discount ? true : false,
            'discount' => $this->discount ? 'تخفیف '. $this->discount .' %' : '0',
            'days_number' => $this->days, 
            'description' => $this->description
        ];
    }
}
