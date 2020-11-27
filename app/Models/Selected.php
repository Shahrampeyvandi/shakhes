<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selected extends Model
{
    protected $table = 'selected';
    protected $guarded = ['id'];

    public function bookmarkable()
    {
        return $this->morphTo();
    }

}
