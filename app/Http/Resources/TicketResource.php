<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
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
            'subject' => $this->subject,
            'message' => $this->content,
            'answered' => $this->status == 'readed' ? true : false,
            'answer' => $this->answer,
            'date' => $this->get_current_date_shamsi($this->created_at),

        ];
    }
}
