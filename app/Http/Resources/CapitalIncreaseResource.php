<?php

namespace App\Http\Resources;

use App\Models\Namad\Namad;
use Carbon\Carbon;
use Morilog\Jalali\Jalalian;
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


            'namad' => [
                'id' => $this->namad->id,
                'symbol' => Cache::get($this->id)['symbol'],
                'name' => Cache::get($this->id)['name'],
                'final_price_value' => Cache::get($this->id)['final_price_value'],
                'final_price_percent' => Cache::get($this->id)['final_price_percent'],
                'final_price_change' => Cache::get($this->id)['last_price_change'],
                'final_price_status' => Cache::get($this->id)['last_price_status'] ? '+' : '-',
            ],
            'newsId' => $this->id,
            'newsDate' => Jalalian::forge($this->publish_date)->format('Y/m/d'),
            'publish_at' => $this->publish_date,
            'newsLink' => $this->link_to_codal,
            'newsText' => $this->subject,
            'isBookmarked' => false,
            'seen' => Carbon::parse($this->created_at)->isToday() ? false : true
        ];
    }
}
