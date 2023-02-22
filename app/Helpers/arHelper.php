<?php

use Illuminate\Support\Str;

function getGeneralSettings() {
	$arr = DB::table('general_settings')->where('id', '=', '1')->first();
	return $arr;
}

function sizeFilter( $bytes ) {
	if( $bytes != '' && $bytes != null ) {
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
	} else {
		return "0";
	}
}

function fileInfo( $fileId ) {
	$dataArr = array();
	if( $fileId != null && $fileId != '' ) {
		$dataArr = DB::table('files_master')->where('id', '=', $fileId)->first();
	}
	return $dataArr;
}

function imageInfo( $imgId ) {
	$dataArr = array();
	if( $imgId != null && $imgId != '' ) {
		$dataArr = DB::table('image')->where('id', '=', $imgId)->first();
	}
	return $dataArr;
}

function getImageById($imgid) {
	$data = array();
	if($imgid != '' && $imgid != '0') {
		$data = DB::table('image')->where('id', '=', $imgid)->first();
	}

	return $data;
}

function checkRegistrationPaymentStatus($userID) {
	$rtn = 'In-Progress';
	if($userID != '') {
		$ck = DB::table('users')->where('id', $userID)->first();
		if(!empty($ck)) {
			if($ck->payment_type == 1) {
				$data = DB::table('user_payment_info')->where('user_id', $userID)->first();
				if(!empty($data)) {
					if ($data->payment_status == 0) {
						$rtn = 'In-Progress';
					} elseif ($data->payment_status == 1) {
						$rtn = 'Completed';
					} elseif ($data->payment_status == 2) {
						$rtn = 'Pending';
					} elseif ($data->payment_status == 3) {
						$rtn = 'Failed';
					} else {
						$rtn = 'In-Progress';
					}
				}
			}
			if($ck->payment_type == 2) {
				$data = DB::table('user_payment_info')->where('useby_user_id', $userID)->first();
				if(!empty($data)) {
					$rtn = 'Voucher Applied <br/>('. $data->voucher_code .')';
				}
			}	
		}
	}
	return $rtn;
}

function getContentImage($content_id) {
	$rtnData = array();
	if($content_id != '' && $content_id != 0) {
		$imageMap = DB::table('content_images_map')->where('content_id', $content_id)->get();
		if(!empty($imageMap)) {
			foreach($imageMap as $img) {
				$image = DB::table('image')->where('id', $img->image_id)->first();
				if(!empty($image)) {
					$arr = array();
					$arr['image_name'] = $image->image;
					$arr['title'] = $img->title;
					$arr['alt_tag'] = $img->alt_tag;
					array_push($rtnData, $arr);
				}
			}
		}
	}
	return $rtnData;
}

function addOnManagement() {
	$rtnData = array();
	$data = DB::table('content_type')->where('status', '!=', '3')->where('is_management', 1)
	->orderBy('id', 'asc')->get();
	if(!empty($data)) {
		foreach($data as $v) {
			$arr = array();
			$arr['route_text'] = str_limit($v->name, '16', '..'); 
			$arr['route'] = route('mngLists', array('type' => str_slug($v->name), 'type_id' =>$v->id)) . '?isManagement=true';
			array_push($rtnData, $arr);
		}
	}
	return $rtnData;
}

function getYoutubeEmbedUrl($url){


    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))(\w+)/i';

    

    if (preg_match($longUrlRegex, $url, $matches)) {
    	
    	
        $youtube_id = $matches[count($matches) - 1];
        //echo $youtube_id;die;
        return 'https://www.youtube.com/embed/' . $youtube_id ;
    }
	else if (preg_match($shortUrlRegex, $url, $matches)) {

    	
        $youtube_id = $matches[count($matches) - 1];
        //echo $youtube_id;die;
        return 'https://www.youtube.com/embed/' . $youtube_id ;
    }
    else
    {
    	$regs = array();
    
        $id = '';
        //echo $youtube_id;die;
    
        if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs)) {
            $youtube_id = $regs[3];
        }
        else
        {
        	return $url;
        }
    
        

    	return 'http://player.vimeo.com/video/' . $youtube_id ;
    }


   
}

function extractVideoID($url){
    $pattern = 
        '%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
        ;
    $result = preg_match($pattern, $url, $matches);
    if ($result) {
        return $matches[1];
    }
    return false;
}
	 
	function getYouTubeThumbnailImage($video_id) {
    return "http://i3.ytimg.com/vi/$video_id/hqdefault.jpg"; //pass 0,1,2,3 for different sizes like 0.jpg, 1.jpg
	}

	function videoType($url) {
	    if (strpos($url, 'youtube') > 0) {
	        return 'youtube';
	    } elseif (strpos($url, 'vimeo') > 0) {
	        return 'vimeo';
	    } else {
	        return 'unknown';
	    }
	}

	function getVimeoId($url)
	{
	    if (preg_match('#(?:https?://)?(?:www.)?(?:player.)?vimeo.com/(?:[a-z]*/)*([0-9]{6,11})[?]?.*#', $url, $m)) {
	        return $m[1];
	    }
	    return false;
	}
	 
	function getVimeoThumb($id)
	{
	    $arr_vimeo = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
	    return $arr_vimeo[0]['thumbnail_small']; // returns small thumbnail
	    // return $arr_vimeo[0]['thumbnail_medium']; // returns medium thumbnail
	    // return $arr_vimeo[0]['thumbnail_large']; // returns large thumbnail
	}


	function company_id($user_id)
	{
		$arr = DB::table('users')->where('id', '=', $user_id)->first();

		if(!empty($arr->founder_id))
		{
			$company_id =$arr->founder_id;
		}
		else
		{
			$company_id =$user_id;
		}
	    return $company_id;
	}

	function myUrlEncode($string) {
    	$slug = Str::slug($string, '-');
    	return $slug;
	}
	
	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 80){
		$imgsize = getimagesize($source_file);
		$width = $imgsize[0];
		$height = $imgsize[1];
		$mime = $imgsize['mime'];

		switch($mime){
			case 'image/gif':
				$image_create = "imagecreatefromgif";
				$image = "imagegif";
				break;

			case 'image/png':
				$image_create = "imagecreatefrompng";
				$image = "imagepng";
				$quality = 7;
				break;

			case 'image/jpeg':
				$image_create = "imagecreatefromjpeg";
				$image = "imagejpeg";
				$quality = 80;
				break;

			default:
				return false;
				break;
		}

		$dst_img = imagecreatetruecolor($max_width, $max_height);
		$src_img = $image_create($source_file);

		$width_new = $height * $max_width / $max_height;
		$height_new = $width * $max_height / $max_width;
		//if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
		if($width_new > $width){
			//cut point by height
			$h_point = (($height - $height_new) / 2);
			//copy image
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
		}else{
			//cut point by width
			$w_point = (($width - $width_new) / 2);
			imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
		}

		$image($dst_img, $dst_dir, $quality);

		if($dst_img)imagedestroy($dst_img);
		if($src_img)imagedestroy($src_img);
		
		//resize_crop_image(100, 100, "test.jpg", "test.jpg");
	}

	function startup_id($user_id)
	{
		//$arr = DB::table('users')->where('id', '=', $user_id)->first();
		$arr = DB::table('users')->where('id', '=', $user_id)->whereNull('user_type','=','2')->get();

		if(empty($arr->founder_id))
		{
			//$startup_id =$arr->founder_id;
			$startup_id =$user_id;
		}
		
	    return $startup_id;
	}

	

?>