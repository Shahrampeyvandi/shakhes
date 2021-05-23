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
        // if (request()->header('Authorization')) {
            
        //     $payload = JWTAuth::parseToken(request()->header('Authorization'))->getPayload();
        //     $mobile = $payload->get('mobile');
        //     $member = Member::where('phone', $mobile)->first();
        // } else {
        //     $member = null;
        // }
        

        if (Cache::has($this->id)) {
            $c = Cache::get($this->id);
            return  [
                'id' => $this->id,
                'symbol' => $c['symbol'],
                'name' => $c['name'],
                'last_price' => number_format($c['pc']),
                'final_price_value' => $c['final_price_value'],
                'final_price_percent' => $c['final_price_percent'],
                'final_price_change' => $c['last_price_change'],
                'final_price_status' => $c['last_price_status'] ? '+' : '-',
                'namad_status' => $c['namad_status'],
                'notifications_count' => 0,
                'py' => $c['py'],
                'codal' => $this->hasCodal()
            ];
            
        } else {
            return [];
        }
    }
}
