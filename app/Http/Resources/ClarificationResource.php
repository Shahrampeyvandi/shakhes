<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'namad' => $this->namad->name,
            'subject' => $this->subject,
            'publish_date' => $this->publish_date,
            'link_to_codal' => $this->link_to_codal,
            'new' => $this->new,
        ];
    }
}
