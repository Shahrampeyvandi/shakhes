<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\clarification;

class ClarificationController extends Controller
{
    public function getall()
    {
        $clarifications_array = clarification::latest()->get();
        $count = 1;
        foreach ($clarifications_array as $key => $item) {
            $array[$count]['namad'] = $item->namad->name;
            $array[$count]['subject'] = $item->subject;
            $array[$count]['publish_date'] = $item->publish_date;
            $array[$count]['link_to_codal'] = $item->link_to_codal;
            $array[$count]['new'] = $item->new;

            $count++;
        }
        return response()->json(
            $array,
            200
        );
    }
}
