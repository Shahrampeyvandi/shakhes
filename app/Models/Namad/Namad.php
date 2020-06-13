<?php

namespace App\Models\Namad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\NamadsDailyReport;
use App\Models\Namad\NamadsMonthlyReport;
use App\Models\Namad\NamadsYearlyReport;
use App\Models\Namad\NamadsSeasonalReport;
use App\Models\Holding\Holding;
use App\Models\Accounting\Subscribe;
use App\Models\Accounting\Transaction;
use App\Models\CapitalIncrease\CapitalIncrease;
use App\Models\clarification;

class Namad extends Model
{
    public function dailyReports()
    {
        return $this->hasMany(NamadsDailyReport::class);
    }

    public function monthlyReports()
    {
        return $this->hasMany(NamadsMonthlyReport::class);
    }
    
    public function yearlyReports()
    {
        return $this->hasMany(NamadsYearlyReport::class)->orderBy('year','ASC');
    }

    public function seasonalReports()
    {
        return $this->hasMany(NamadsSeasonalReport::class)->orderBy('id','ASC');
    }

    public function holdings()
    {
        return $this->belongsToMany(Holding::class)->withPivot(['amount_percent','amount_value','change']);
    }

    public function capital_increases()
    {
        return $this->hasMany(CapitalIncrease::class);
    }
    public function clarifications()
    {
        return $this->hasMany(clarification::class);
    }

}

