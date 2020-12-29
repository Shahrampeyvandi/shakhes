<?php

namespace App;

use App\Http\Traits\CommonRelations;
use App\Models\Member\Member;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use CommonRelations;
    public function member()
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function get_status()
    {
        $s = $this->status;
        if($s == 'unread') return ['message'=>'خوانده نشده','alert'=>'danger'];
        if($s == 'readed') return ['message'=>'پاسخ داده شده','alert'=>'success'];
        if($s == 'suspended') return ['message'=>'معلق','alert'=>'info'];
        if($s == 'expired') return ['message'=>'منقضی','alert'=>'secondary'];
    }
}
