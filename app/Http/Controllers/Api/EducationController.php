<?php

namespace App\Http\Controllers\Api;

use App\Education;
use App\EducationCat;
use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    function list() {
        $all = [];
        $categories = EducationCat::latest()->get();
        if (count($categories) == 0) {
            $error = 'هیچ دسته بندی برای آموزش وجود ندارد';
           return $this->JsonResponse($all,$error,200);
           
        }

        foreach ($categories as $key => $cat) {
            $array['category_id'] = $cat->id;
            $array['category'] = $cat->name;
            $items = [];
            $educations = Education::where('category_id', $cat->id)->get();
            if (count($educations) == 0) {
                continue;
            }

            foreach ($educations as $key => $education) {
                $item['id'] = $education->id;
                $item['title'] = $education->title;
                $item['link'] = route('BaseUrl') . '/education/'.$education->id.'';
                $item['section'] = $education->section;
                $item['image'] = asset($education->image);
                $item['views'] = $education->views;
                $items['items'][] = $item;
            }
            $all[] = array_merge($array, $items);

        }
        $error = null;

         return $this->JsonResponse($all,$error,200);
       
    }

    public function addViewCount($id)
    {
        $education = Education::find($id);
        $education->increment('views');
        return \response()->json(['views' => $education->fresh()->views], 200);
    }
    public function view($id)
    {
        $education = Education::find($id);
        $array['category'] = $education->category->name;
        $array['title'] = $education->title;
        $array['description'] = $education->description;
        $array['section'] = $education->section;
        $array['image'] = $education->image;
        $array['views'] = $education->views;

        return \response()->json( $array, 200);
    }

}
