<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CapitalIncrease\CapitalIncrease;

class CapitalIncreaseController extends Controller
{
    public function getCapitalIncreases(Request $request)
    {
        $CapitalIncreases =  CapitalIncrease::where('namad_id', $request->sahm)->get();
        $count = count($CapitalIncreases);
        $list = ' <table class="table table-bordered  ">
      <thead>
          <tr>
             
              <th scope="col">ردیف</th>
              <th scope="col">نوع افزایش سرمایه</th>
              <th scope="col">مرحله</th>
              <th scope="col">میزان افزایش سرمایه</th>
              <th scope="col">تاریخ</th>
              <th scope="col">لینک</th>
              <th scope="col">عملیات</th>
      </thead>
      <tbody>';
        foreach ($CapitalIncreases as $key => $item) {

            $list .= '<tr>
           <td >' . ($key + 1) . '</td>';
            switch ($item->from) {
                case 'assets':
                    $name = 'تجدید ارزیابی دارایی ها';
                    break;
                case 'compound':
                    $name = 'افزایش سرمایه ترکیبی';
                    break;
                case 'stored_gain':
                    $name = 'سود انباشته';
                    break;
                case 'cash':
                    $name = 'آورده نقدی سهام داران';
                    break;
            }

            $amounts = '';
            foreach ($item->amounts as $key => $amount) {
                switch ($amount->type) {
                    case 'assets':
                        $name2 = 'تجدید ارزیابی دارایی ها';
                        break;
                    case 'compound':
                        $name2 = 'افزایش سرمایه ترکیبی';
                        break;
                    case 'stored_gain':
                        $name2 = 'سود انباشته';
                        break;
                    case 'cash':
                        $name2 = 'آورده نقدی سهام داران';
                        break;
                }
                if ($item->from == 'compound') {
                    $amounts .= '<span>' . $name2 . '</span><span class="float-left">' . $amount->percent . ' ریال</span></br>';
                } else {
                    $amounts .= '<span>' . $amount->percent . ' ریال</span></br>';
                }
            }



            $list .= '<td >' . $name . '</td>
           <td >' . $item->step . '</td>
           <td >' . $amounts . '</td>
           <td >' . $item->publish_date . '</td>
           <td ><a href="' . $item->link_to_codal . '" class="text-primary">لینک به کدال</a></td>
           <td ><a href="#" class="text-danger">حذف</a></td>';
        }
        $list .= '</tr>
                            
        </tbody>
    </table>';

        return response()->json(['list' => $list, 'count' => $count], 200);
    }
}
