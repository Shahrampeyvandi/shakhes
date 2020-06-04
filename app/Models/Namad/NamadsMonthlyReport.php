<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class NamadsMonthlyReport extends Model
{
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
}
