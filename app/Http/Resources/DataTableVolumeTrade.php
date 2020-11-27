<?php

namespace App\Http\Resources;

use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\JsonResource;

class DataTableVolumeTrade extends JsonResource
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
            'symbol' => $this->namad->symbol,
            'pl' => Cache::get($this->namad->id)['pl'],
            'final_price_percent' => Cache::get($this->namad->id)['final_price_percent'],
            'status' => Cache::get($this->namad->id)['status'],
            'trade_vol' => $this->format($this->trade_vol),
            'month_avg' => $this->format($this->month_avg),
            'ratio' => $this->volume_ratio,
            'date' => Jalalian::forge($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
