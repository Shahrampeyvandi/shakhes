<?php

namespace App\Models\Namad;
use App\Http\Traits\FullTextSearch;
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
use App\Models\Member\Member;
use App\Models\VolumeTrade;
use App\User;

class Namad extends Model
{
    use FullTextSearch;
    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'symbol',
        'name'
    ];


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
    public function volume_trades()
    {
        return $this->hasMany(VolumeTrade::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'members_namads','namad_id','member_id')->withPivot(['amount', 'profit_loss_percent', 'price']);
    }


    public static function GetAllNotifications($user)
    {
        $capital_increases = 0;
        $clarifications = 0;
        $disclosures = 0;
        $volume_trades_report = 0;
        $date = Carbon::today();

        foreach (static::all() as $key => $namad) {
            $capital_increases += $namad->capital_increases()->whereDate('updated_at', $date)
                ->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                    $q->where(['member_id' => $user->id, 'namad_id' => $namad->id]);
                })
                ->count();
            $clarifications += $namad->clarifications()->whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['member_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
            $disclosures += $namad->disclosures()->whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['member_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
            $volume_trades_report += $namad->volume_trades()->whereDate('updated_at', $date)->whereDoesntHave('readed_notifications', function ($q) use ($user, $namad) {
                $q->where(['member_id' => $user->id, 'namad_id' => $namad->id]);
            })->count();
        }

        $array['capital_increases'] = $capital_increases;
        $array['clarifications'] = $clarifications;
        $array['disclosures'] = $disclosures;
        $array['volume_trades'] = $volume_trades_report;

        return $array;
    }

    public function getUserNamadNotifications($user)
    {
        $count = 0;
        $array['namad'] = $this->symbol;

        $array['capital_increases'] = $this->capital_increases()->whereDate('updated_at', Carbon::today())
            ->whereDoesntHave('readed_notifications', function ($q) use ($user) {
                $q->where(['member_id' => $user->id, 'namad_id' => $this->id]);
            })
            ->count();

        $array['volume_trades'] = $this->volume_trades()->whereDate('updated_at', Carbon::today())->whereDoesntHave('readed_notifications', function ($q) use ($user) {
            $q->where(['member_id' => $user->id, 'namad_id' => $this->id]);
        })->count();
        $array['codal_reports'] = $this->capital_increases()->whereDate('publish_date',Carbon::today())->count() + $this->clarifications()->whereDate('publish_date',Carbon::today())->count();

        $count += $array['capital_increases'] + $array['codal_reports'] + $array['volume_trades'] ;
        $array['count'] = $count;
        return $array;
    }

    public function hasCodal()
    {

        // $member = Member::find($memberId);
        // if($member && $member->namads->contains($this->id)) {

                if($this->capital_increases()->whereDate('publish_date',Carbon::today())->count()
                || $this->clarifications()->whereDate('publish_date',Carbon::today())->count())
                {
                    return true;
                }

        // }

        return false;
    }

}
