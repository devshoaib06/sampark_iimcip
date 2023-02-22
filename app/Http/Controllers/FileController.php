<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Images;

use File;
use Storage;
use Image;
use Auth;
use DB;

class FileController extends Controller
{
    
    public function all_images() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'image';
    	$DataBag['subMenu'] = '';
    	$DataBag['childMenu'] = 'allImgs';
        

        $query = Images::where('status', '!=', '3');
        $query = Images::where('member_id', '=', Auth::user()->id);
        

        if( isset($_GET['status']) && $_GET['status'] != '' && $_GET['status'] != '0' && $_GET['status'] != null ) {
            $query = $query->where('status', '=', trim($_GET['status']));
        } 
       
        $allImages = $query->orderBy('id', 'desc')->paginate(25);
    	$DataBag['allImages'] = $allImages;
    	return view('dashboard.image.index', $DataBag);
    }

    public function add() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'image';
    	$DataBag['subMenu'] = '';
    	$DataBag['childMenu'] = 'addImg';
    	
       

    	return view('dashboard.image.add_edit', $DataBag);
    }

    public function upload(Request $request) {

    	if( $request->hasFile('images') ) {
                // $nameArr = $request->input('name');
                // $captionArr = $request->input('caption');
                $i=0;

                //dd($request->file('images'));
    		foreach( $request->file('images') as $img ) {
    			$Images = new Images;
	    		$real_path = $img->getRealPath();
	            $file_orgname = $img->getClientOriginalName();
	            $file_size = $img->getClientSize();
	            $file_ext = strtolower($img->getClientOriginalExtension());
	            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

	            $destinationPath = public_path('/uploads/files/media_images');
	            $thumb_path = $destinationPath."/thumb";
	            // echo $destinationPath;
             //    exit;
	            //$imgObj = Image::make($real_path);
                //$imgObj = File::make($real_path);
	        	

	        	$img->move($destinationPath, $file_newname);
	        	
                $Images->media_filepath = $file_newname;
                if($file_ext == 'mp4' ||  $file_ext == 'MOV' ||  $file_ext == 'WMV' ||  $file_ext == 'AVI'){
                    $Images->media_type = '2';
                }
                else{
                     $Images->media_type = '1';
                }
	        	$Images->extension = $file_ext;
                $Images->status = trim($request->input('status'));
	        	$Images->member_id  = Auth::user()->id;
                
                $Images->save();
                $i++;
    		}
            
            return back()->with('msg', 'Images Uploaded Successfully.')
            ->with('msg_class', 'alert alert-success');
    	}

    	return back();
    }

    public function imgDetails($image_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'image';
        $DataBag['childMenu'] = 'allImgs';
        $DataBag['imgInfo'] = $imgInfo = Images::findOrFail($image_id);
        
        return view('dashboard.image.add_edit', $DataBag);
    }

    public function imgDetailsUpdate(Request $request, $image_id) {

        //dd($request);
        $Images = Images::find($image_id);
        if( isset($Images) && !empty($Images) ) {
            

            if( $request->hasFile('image') ) {


                

                $img = $request->file('image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();

                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "media"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
    

                $img->move($destinationPath, $file_newname);

                // $Images->image = $file_newname;
                // $Images->size = $file_size;
                

                $Images->media_filepath = $file_newname;
                if($file_ext == 'mp4' ||  $file_ext == 'MOV' ||  $file_ext == 'WMV' ||  $file_ext == 'AVI'){
                    $Images->media_type = '2';
                }
                else{
                     $Images->media_type = '1';
                }
                $Images->extension = $file_ext;
                $Images->status = trim($request->input('status'));
                $Images->member_id  = Auth::user()->id;
                $Images->updated_at = date('Y-m-d H:i:s');
                $Images->save();
            }
            return back()->with('msg', 'Image Details Updated Succesfully.')->with('msg_class', 'alert alert-success');
        } 

        return back();
    }

    public function imgDelete( $image_id ) {
        $ck = Images::find($image_id);
        if( isset($ck) && !empty($ck) ) {
            $image = $ck->image;
            $res = $ck->delete();
            

            File::delete(['public/uploads/files/media_images/thumb/'. $image, 'public/uploads/files/media_images/'. $image]);
            return back()->with('msg', 'Image Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function imgMultiDelete(Request $request) {
        if( $request->has('imgIds') && !empty( $request->input('imgIds') ) ) {
            $imageFileArray = array();
            foreach( $request->input('imgIds') as $id ) {
                $ck = Images::find($id);
                $image = "public/uploads/files/media_images/".$ck->image;
                array_push( $imageFileArray, $image );
                $image_thumb = "public/uploads/files/media_images/thumb/".$ck->image;
                array_push( $imageFileArray, $image_thumb );
                $res = $ck->delete();
                
            }

            File::delete($imageFileArray);
            return back()->with('msg', 'Images Deleted Successfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }


}
