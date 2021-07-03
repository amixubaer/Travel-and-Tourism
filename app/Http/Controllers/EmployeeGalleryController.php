<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmpGalleryRequest;
use App\Gallery;
class EmployeeGalleryController extends Controller
{
    public function index(){
        return view('employee.galleryAdd');
    }

    public function upload(EmpGalleryRequest $req){
        if ($req->hasFile('image')) {
            $file = $req->file('image');
            if($file->move('upload', 'employeeGallery'.$req->place.'.'.$file->getClientOriginalExtension())){
                echo "success";
            }else{
                echo "error";
            }
        }
        $img='employeeGallery'.$req->place.'.'.$file->getClientOriginalExtension();

        $gallery = new Gallery;
        $gallery -> place = $req->place;
        $gallery -> image = $img;
        $gallery->save();
        return redirect()->route('employeeGallery.index');
    }

    public function manage(){
        $gallery = Gallery::all();
        return view('employee.GalleryManage')->with('galleries', $gallery);
    }
    
    
    public function pictureDelete($id){
        $gallery = Gallery::find($id);
        $gallery ->delete();
        return redirect()->route('employeeGallery.manage');
    }

}
