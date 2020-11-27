<?php

namespace App\Http\Resources;

use App\Models\Namad\Namad;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\JsonResource;

class HoldingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $h = Namad::find($this->namad_id);
        return [
            'namad' => new NamadResource($h),
            'itemId' => $this->id,
            'realPortfoy' => (int)$this->getMarketValue(),
            'formatedPortfoy' => $this->format($this->getMarketValue()),
            'percentChangePorftoy' => $this->change_percent(),
            'Status' =>  $this->change_percent() > 0 ? '+' : '-',
            'countNamad' => count($this->namads),
            'namads' => NamadResource::collection($this->namads()->orderBy('symbol')->get())
        ];
    }
}
