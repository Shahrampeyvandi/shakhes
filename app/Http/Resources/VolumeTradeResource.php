<?php

namespace App\Http\Resources;

use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\JsonResource;

class VolumeTradeResource extends JsonResource
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

            'namad' => new NamadResource($this->namad),
            'newsId' => $this->id,
            'newsId' => $this->id,
            'transactionVolume' =>  $this->trade_vol,
            'averageVolumeOfMonth' => $this->month_avg,
            'increaseRatio' => number_format((float)$this->volume_ratio, 1),
            'newsDate' => Jalalian::forge($this->updated_at)->format('Y/m/d'),
            'newsTime' => Jalalian::forge($this->updated_at)->format('H:m'),
            'isBookmarked' => false,
        ];
    }
}
