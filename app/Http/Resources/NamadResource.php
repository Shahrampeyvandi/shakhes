<?php

namespace App\Http\Resources;

use App\Models\Member\Member;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Resources\Json\JsonResource;

class NamadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (request()->header('Authorization')) {
            $payload = JWTAuth::parseToken(request()->header('Authorization'))->getPayload();
            $mobile = $payload->get('mobile');
            $member = Member::where('phone', $mobile)->first();
        } else {
            $member = null;
        }


        if (Cache::has($this->id)) {

            return  [
                'id' => $this->id,
                'symbol' => Cache::get($this->id)['symbol'],
                'name' => Cache::get($this->id)['name'],
                'final_price_value' => Cache::get($this->id)['final_price_value'],
                'final_price_percent' => Cache::get($this->id)['final_price_percent'],
                'final_price_change' => Cache::get($this->id)['last_price_change'],
                'final_price_status' => Cache::get($this->id)['last_price_status'] ? '+' : '-',
                'namad_status' => Cache::get($this->id)['namad_status'],
                'notifications_count' => $member ?  $this->getUserNamadNotifications($member)['count'] : 0
            ];
        } else {
            return [];
        }
    }
}
