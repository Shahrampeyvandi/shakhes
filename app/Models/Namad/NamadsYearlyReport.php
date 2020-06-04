<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;

class NamadsYearlyReport extends Model
{
    protected $table = 'namads_yearly_reports';
    
    public function namad()
    {
        return $this->belongTo(Namad::class);
    }
}
