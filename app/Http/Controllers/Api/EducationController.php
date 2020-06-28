<?php

namespace App\Http\Controllers\Api;

use App\Education;
use App\EducationCat;
use App\Http\Controllers\Controller;

class EducationController extends Controller
{
    function list() {
        $categories = EducationCat::latest()->get();
        if (count($categories) == 0) {
            return \response()->json(['data' => 'هیچ دسته بندی وجود ندارد'], 401);
        }

        $all = [];
        foreach ($categories as $key => $cat) {
            $array['category_id'] = $cat->id;
            $array['category'] = $cat->name;
            $items = [];
            $educations = Education::where('category_id', $cat->id)->get();
            if (count($educations) == 0) {
                return \response()->json(['data' => 'هیچ آموزشی پیدا نشد'], 401);
            }

            foreach ($educations as $key => $education) {
                $item['title'] = $education->title;
                $item['description'] = $education->description;
                $item['section'] = $education->section;
                $item['image'] = $education->image;
                $item['views'] = $education->views;
                $items['items'][] = $item;
            }
            $all[] = array_merge($array, $items);

        }

        return \response()->json($all, 200);

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

        return \response()->json(['data' => $array], 200);
    }

}
