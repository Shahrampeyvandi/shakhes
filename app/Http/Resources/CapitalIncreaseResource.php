<?php

namespace App\Http\Resources;

use App\Models\Namad\Namad;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\JsonResource;

class CapitalIncreaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        return  [
            'namad' => $this->namad ?  Cache::get($this->namad->id) : '',
            'id' => $this->id,
            'step' => $this->step,
            "from_cash" => $this->get_percent('from_cash'),
            "from_stored_gain" => $this->get_percent('from_stored_gain'),
            "from_assets" => $this->get_percent('from_assets'),
            'publish_date' => $this->publish_date,
            'link_to_codal' => $this->link_to_codal,
            'description' => $this->description,
            'selected'=>$this->member_selected(),
            'new' => $this->new()
        ];
    }
}
