<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selected extends Model
{
    protected $table = 'selected';
    protected $fillable = ['member_id','model_id','type'];

}
