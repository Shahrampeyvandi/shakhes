<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Paterns\ContinuingPatern;
use Carbon\Carbon;

class PaternsController extends Controller
{
    public function getContinuingPaterns()
    {
        $paterns = ContinuingPatern::latest()->get();
        $new = ContinuingPatern::where('created_at', '>', Carbon::now()->subDay(3))
            ->where('new', 1)->count();
        $all = [];
        foreach ($paterns as $key => $patern) {
            $array['name'] = $patern->name;
            $array['namad'] = $patern->namad->symbol;
            $array['picture'] = $patern->picture;
            $array['type'] = $patern->type == 'asc' ? 'صعودی' : 'نزولی';
            $all[] = $array;
        }


        return response()->json(
            ['data' => $all, 'new' => $new],
            200
        );
    }
}
