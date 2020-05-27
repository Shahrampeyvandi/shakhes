<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\NamadDailyReport;
use App\Models\Namad\NamadMonthlyReport;
use App\Models\Namad\NamadYearlyReport;
use App\Models\Namad\NamadSeasonalReport;
use App\Models\Holding\Holding;
use App\Models\Accounting\Subscribe;
use App\Models\Accounting\Transaction;

class Namad extends Model
{
    public function dailyReports()
    {
        return $this->hasMany(NamadDailyReport::class);
    }

    public function monthlyReports()
    {
        return $this->hasMany(NamadMonthlyReport::class);
    }
    
    public function yearlyReports()
    {
        return $this->hasMany(NamadYearlyReport::class)->orderBy('year','ASC');
    }

    public function seasonalReports()
    {
        return $this->hasMany(NamadSeasonalReport::class)->orderBy('id','ASC');
    }

    public function holdings()
    {
        return $this->belongsToMany(Holding::class)->withPivot(['amount_percent','amount_value','change']);
    }

}

