<?php

namespace App\Models\Namad;

use Carbon\Carbon;
use App\Models\clarification;
use App\Models\Holding\Holding;
use App\Models\Accounting\Subscribe;
use App\Models\Accounting\Transaction;
use App\Models\Namad\NamadsDailyReport;
use Illuminate\Database\Eloquent\Model;
use App\Models\Namad\NamadsYearlyReport;
use App\Models\Namad\NamadsMonthlyReport;
use App\Models\Namad\NamadsSeasonalReport;
use App\Models\CapitalIncrease\CapitalIncrease;

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
        return $this->hasMany(NamadsYearlyReport::class)->orderBy('year', 'ASC');
    }

    public function seasonalReports()
    {
        return $this->hasMany(NamadsSeasonalReport::class)->orderBy('id', 'ASC');
    }

    public function holdings()
    {
        return $this->belongsToMany(Holding::class)->withPivot(['amount_percent', 'amount_value', 'change']);
    }

    public function capital_increases()
    {
        return $this->hasMany(CapitalIncrease::class);
    }
    public function clarifications()
    {
        return $this->hasMany(clarification::class);
    }
    public function disclosures()
    {
        return $this->hasMany(Disclosures::class);
    }


    public static function GetAllNotifications($user)
    {
        $capital_increases = 0;
        $clarifications = 0;
        $disclosures = 0;

        foreach (static::all() as $key => $namad) {
            $capital_increases += $namad->capital_increases()->whereDate('created_at', Carbon::today())
            ->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })
            ->count();
            $clarifications += $namad->clarifications()->whereDate('created_at', Carbon::today())->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
            $disclosures += $namad->disclosures()->whereDate('created_at', Carbon::today())->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
        }

        $array['capital_increases'] = $capital_increases;
        $array['clarifications'] = $clarifications;
        $array['disclosures'] = $disclosures;

        return $array;
    }

    public function getUserNamadNotifications($user, $namad)
    {
        
        $array['capital_increases'] = $this->capital_increases()->whereDate('created_at', Carbon::today())
            ->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })
            ->count();
        $array['clarifications'] = $this->clarifications()->whereDate('created_at', Carbon::today())->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
        $array['disclosures']  = $this->disclosures()->whereDate('created_at', Carbon::today())->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['user_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
        return $array;
    }
}
