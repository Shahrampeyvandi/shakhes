<?php

namespace App\Http\Controllers;

use App\Education;
use App\EducationCat;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function Add()
    {

       
        return view('Education.add')->with('categories', EducationCat::latest()->get());
    }

    public function Save(Request $request)
    {

        if ($request->hasFile('image')) {
            $destinationPath = "pictures/educations/";
            $picextension = $request->file('image')->getClientOriginalExtension();
            $fileName = date("Y-m-d") . '_' . time() . '.' . $picextension;
            $request->file('image')->move($destinationPath, $fileName);
            $picPath = "$destinationPath/$fileName";
        } else {
            $picPath = '';
        }
        if ($cat = EducationCat::whereName($request->category)->first()) {
            Education::create([
                'category_id' => $cat->id,
                'title'    => $request->title,
                'description' => $request->desc,
                'section' => $request->section,
                'image' => $picPath
            ]);
        } else {
           $cat = EducationCat::create(['name'=>$request->category]);
             Education::create([
                'category_id' => $cat->id,
                'title'    => $request->title,
                'description' => $request->desc,
                'section' => $request->section,
                'image' => $picPath
            ]);
        }

        return back();
    }
}
