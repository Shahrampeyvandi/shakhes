<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Namad\Namad;
use Illuminate\Support\Facades\Cache;

class ClarificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $namad = Namad::where('id', $this->id)->first();
        return [
            'namad' =>Cache::get($this->namad->id),
            'id'=>$this->id,
            'subject' => $this->subject,
            'publish_date' => $this->publish_date,
            'link_to_codal' => $this->link_to_codal,
            'new' => $this->new,
        ];
    }
}
