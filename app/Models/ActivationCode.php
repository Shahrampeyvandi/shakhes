<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ActivationCode extends Model
{
    protected $fillable = ['mobile','v_code','expire'];

    
    

    public static function createCode($mobile)
    {
     
        $code = static::code();
        if (static::where('mobile',$mobile)->count()) {
            $beforecode=static::where('mobile',$mobile)->first();
            if($beforecode->expire > Carbon::now()){
                return false;
            }
            $beforecode->Delete();
        }
        return static::create([
            'mobile' => $mobile,
            'v_code' => $code,
            'expire' => Carbon::now()->addMinutes(15)
        ]);
    }

    public static function code($length = 6)
    {
        do {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $check_code = static::where('v_code',$randomString)->get();
        } while( ! $check_code->isEmpty() );

        return $randomString;
    }
}
