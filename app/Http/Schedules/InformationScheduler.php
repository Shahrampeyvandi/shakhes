<?php

namespace App\Http\Schedules;

use Illuminate\Http\Request;
use App\Models\Namad\Namad;
use App\Models\Namad\NamadsDailyReport;
use Exception;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Goutte;

class InformationScheduler
{


    public function __invoke()
    {

    }

}