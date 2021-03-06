<?php

namespace App\Http\Controllers;

use App\Education;
use App\EducationCat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class EducationController extends Controller
{

    function list()
    {

        $educations = Education::latest()->get();
        return view('Education.List', compact('educations'));
    }
    public function Add()
    {
        if (isset(request()->edit)) {
            $data['edu'] = Education::find(request()->edit);
        }
        $data['categories'] = EducationCat::latest()->get();

        return view('Education.add', $data);
    }

    public function Save(Request $request)
    {
        if (isset($request->edu)) {
            $education = Education::find($request->edu);
            $message = 'آموزش با موفقیت ویرایش شد';
        } else {
            $education = new Education();
            $message = 'آموزش با موفقیت ایجاد شد';
        }

        if ($request->hasFile('image')) {
            $destinationPath = "pictures/educations/$request->title";
            $picextension = $request->file('image')->getClientOriginalExtension();
            $fileName = date("Y-m-d") . '_' . time() . '.' . $picextension;
            $request->file('image')->move($destinationPath, $fileName);
            $picPath = "$destinationPath/$fileName";
            $education->image = $picPath;
        }
        if ($cat = EducationCat::whereName($request->category)->first()) {
        } else {
            $cat = EducationCat::create(['name' => $request->category]);
        }

        $education->category_id = $cat->id;
        $education->title = $request->title;
        $education->description = $request->desc;
        $education->section = $request->section;
        $education->save();

        return redirect()->route('Education.List')->with('success', $message);
    }

    public function UploadImage()
    {

        if (request()->hasFile('upload')) {

            $tmpName = $_FILES['upload']['tmp_name'];

            $size = $_FILES['upload']['size'];
            $filePath = "pictures/educations/" . uniqid();
            $filename = request()->file('upload')->getClientOriginalName();

            if (!file_exists($filePath)) {
                mkdir($filePath, 0755, true);
            }
            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $type = $_GET['type'];
            $funcNum = isset($_GET['CKEditorFuncNum']) ? $_GET['CKEditorFuncNum'] : null;

            if ($type == 'image') {
                $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png');
            } else {
                //file
                $allowedfileExtensions = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'docx');
            }

            //contrinue only if file is allowed
            if (in_array($fileExtension, $allowedfileExtensions)) {

                if (request()->file('upload')->move(public_path($filePath), $filename)) {
                    // if (move_uploaded_file($tmpName, $filePath)) {
                    $file = "$filePath/$filename";
                    // $filePath = str_replace('../', 'http://filemanager.localhost/elfinder/', $filePath);
                    $data = ['uploaded' => 1, 'fileName' => $filename, 'url' => route('BaseUrl') . '/' . $file];

                    if ($type == 'file') {

                        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$filePath','');</script>";
                    }
                } else {

                    $error = 'There has been an error, please contact support.';

                    if ($type == 'file') {
                        $message = $error;

                        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$filePath', '$message');</script>";
                    }

                    $data = array('uploaded' => 0, 'error' => array('message' => $error));
                }
            } else {

                $error = 'The file type uploaded is not allowed.';

                if ($type == 'file') {
                    $funcNum = $_GET['CKEditorFuncNum'];
                    $message = $error;

                    return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$filePath', '$message');</script>";
                }

                $data = array('uploaded' => 0, 'error' => array('message' => $error));
            }

            //return response
            return json_encode($data);
        }
    }

    public function Delete(Request $request)
    {
        $education = Education::find($request->id);
        File::delete(public_path() . '/' . $education->image);
        $education->delete();
        return back();
    }

    public function Show($id)
    {
        $education = Education::find($id);
        $education->increment('views');
        return view('Education.show', compact('education'));
    }
}
