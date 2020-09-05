<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
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
            'symbol' => $this['symbol'],
            'symbol' => $this['symbol'],
            'symbol' => $this['symbol'],
            'symbol' => $this['symbol'],
            'symbol' => $this['symbol'],
            

        ];
    }
}
