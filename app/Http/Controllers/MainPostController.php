<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryCategories;
use App\Models\PostIndustry;
use App\Models\PostReply;
use App\Models\Categories;
use App\Models\Users;
use App\Models\Posts;

use App\Models\PostCategory;
use Image;
use Auth;
use DB;

class MainPostController extends Controller
{
    
    public function index()
    {
       
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mainPost';
    	$DataBag['parentMenu'] = '';
    	$DataBag['childMenu'] = 'allmainPost';
        
        $DataBag['alldata'] = $alldata = DB::table('post_master as pm')->select('pm.*','ur.contact_name as to_contact_name','urf.contact_name')
                ->Leftjoin('users as ur','pm.private_member_id','=','ur.id')
                ->Leftjoin('users as urf','pm.member_id','=','urf.id')
          ->where([
              ['pm.status','!=','3']
          ])
          ->orderBy('pm.id','desc')->get()->toArray();

          if(!empty($alldata)){
            $i=0; $memberBusiness= array();
            
            foreach($alldata as $user){

            $replyCnt=0;    
            $DataBag['alldata'][$i]->replyCnt=0; 
            $DataBag['alldata'][$i]->comments=array();   
            $replyCnt =  PostReply::where('post_id', '=', $user->id)->where('replied_on', '=', 0)->pluck('id')->count();
            $DataBag['alldata'][$i]->replyCnt= $replyCnt;

            $post_industry= DB::table('post_industry')
            ->select('industry_category')
             ->join('industry_category', 'post_industry.industry_category_id', '=', 'industry_category.id')
             ->where("industry_category.status", "=", 1)
             ->where("post_id", "=", $user->id)->get();

             $DataBag['alldata'][$i]->postIndustry= ""; 


             $str = '';
             if(!empty($post_industry)){
                $k=0;
                foreach($post_industry as $mb){
                    if($k==0){
                        $str .= $mb->industry_category;
                    }else{
                        $str .= ', '.$mb->industry_category; 
                    }
                   $k++;
                }   
             }
             $DataBag['alldata'][$i]->postIndustry=$str;

             $post_category= DB::table('post_categories')
            ->select('categories.name')
             ->join('categories', 'post_categories.category_id', '=', 'categories.id')
             ->where("categories.status", "=", 1)
             ->where("post_id", "=", $user->id)->get();

             $DataBag['alldata'][$i]->postCategory= ""; 

             
             $str1 = '';
             if(!empty($post_category) && count($post_category)>0){
                
                $j=0;
                foreach($post_category as $mbc){
                    if($j==0){
                        $str1 .= $mbc->name;
                    }else{
                        $str1 .= ', '.$mbc->name; 
                    }
                   $j++;
                }   
             }
             $DataBag['alldata'][$i]->postCategory=$str1;
             $i++; 
           }
         }
        
       
       # dd($DataBag['alldata']);
        
    	return view('dashboard.main_post.index', $DataBag);
    }


    public function allComments(Request $request){
      $postId= $request->input('postId');

      // echo $postId;
      // exit;

      $DataBag = array();
      $DataBag['alldata'] = DB::table('post_reply')
            ->select('post_reply.id','reply_text','contact_name', 'post_reply.created_at','post_reply.video_url')
             ->join('users', 'post_reply.replied_by', '=', 'users.id')
             ->where("post_reply.status", "=", '1')
             ->where('replied_on', '=', 0)
             ->where("post_id", "=", $postId)->get();

//dd($DataBag['alldata']);


            if(!empty($DataBag['alldata'])){
                $j=0;
                foreach($DataBag['alldata'] as $c){
                    //$DataBag['alldata'][$i]->comments[$j]->reply =  PostReply::select('reply_of_reply','replied_by', 'created_at')->where('post_id', '=', $user->id)->where('replied_on', '=', $c->id)->get();
                  if(!empty($c->video_url))
                  {
                     $c->video_url =getYoutubeEmbedUrl($c->video_url);
                  }

                 
                    $DataBag['alldata'][$j]->reply =  DB::table('post_reply')
                                ->select('post_reply.id','reply_text','contact_name', 'post_reply.created_at')
                                 ->join('users', 'post_reply.replied_by', '=', 'users.id')
                                 ->where("post_reply.status", "=", '1')
                                 ->where('replied_on', '=', $c->id)
                                 ->where("post_id", "=", $postId)->get();

                    //dd($DataBag['alldata'][$j]->reply);

                    $DataBag['alldata'][$j]->images =  DB::table('post_reply')
                                ->select('post_medias.media_path')
                                 ->join('post_medias', 'post_medias.post_reply_id', '=', 'post_reply.id')
                                 ->where("post_medias.status", "=", '1')
                                 ->where("post_medias.media_type", "=", 'I')
                                  ->where('post_medias.post_reply_id', '=', $c->id)
                                 ->where("post_id", "=", $postId)->get();


                    $DataBag['alldata'][$j]->videos =  DB::table('post_reply')
                                ->select('post_medias.media_path')
                                 ->join('post_medias', 'post_medias.post_reply_id', '=', 'post_reply.id')
                                 ->where("post_medias.status", "=", '1')
                                 ->where("post_medias.media_type", "=", 'V')
                                  ->where('post_medias.post_reply_id', '=', $c->id)
                                 ->where("post_id", "=", $postId)->get();

                    $j++;
                }   
            }
      //exit;
             //dd($DataBag);

      return view('dashboard.main_post.comments', $DataBag)->withHeaders('X-Frame-Options', 'ALLOWALL');

    }


    public function allReplys(Request $request){
      $commentId= $request->input('commnetId');

        // echo $commentId;
        // exit;

      $DataBag = array();
      $DataBag['alldata'] = DB::table('post_reply')
            ->select('post_reply.id','reply_text','contact_name', 'post_reply.created_at')
             ->join('users', 'post_reply.replied_by', '=', 'users.id')
             ->where("post_reply.status", "=", '1')
             ->where('replied_on', '=', $commentId)->get();


            

      return view('dashboard.main_post.reply', $DataBag);

    }





    public function add()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mainPost';
    	$DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addmainPost';

        $DataBag['industryList'] = IndustryCategories::orderBy('industry_category', 'asc')->get();

        $DataBag['categoryList'] = Categories::where('status', '!=', '3')->orderBy('name', 'asc')->get();
        
        $DataBag['member'] = DB::table('users')
          ->where([
              ['user_type','!=','1'],
              ['status','=','1']
          ])->get()->toArray();

        
    	return view('dashboard.main_post.add_edit', $DataBag);
    }

    public function addGuide()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'postGuide';
      $DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addmainPostGuide';

        $DataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();
        
        $DataBag['member'] = DB::table('users')
          ->where([
              ['user_type','!=','1'],
              ['status','=','1']
          ])->get()->toArray();

          $DataBag['data'] = DB::table('post_guidlines')->get()->toArray();



        
      return view('dashboard.main_post.add_edit_guide', $DataBag);
    }

    public function saveGuide(Request $request)
    {
        
        $post_information = $request->input('post_information');
        $userId = Auth::id();
        
//        echo "-----------".$userId; die();

        $count =DB::table('post_guidlines')->count();


        if($count>0)
        {
            DB::table('post_guidlines')
              ->update([
                    'post_guide'=>$post_information,
                    'updated_at' => date('Y-m-d'),
                      ]);
        }
        else
        {
           $post = DB::table('post_guidlines')
                        ->insert(
                                [
                                'post_guide'=>$post_information,  
                                'member_id'=>$userId,
                                ]);
        }
        
       


    	 return back()->with('msg', 'Guidlines saved Successfully')
        ->with('msg_class', 'alert alert-success');
    }

    public function save(Request $request)
    {
        $post_title= $request->input('post_title');

        $video_link =$request->input('video_link');
        $post_information = $request->input('post_information');
        $post_type = $request->input('post_type');
        $private_member = $request->input('private_member');

        $is_bookmarked = $request->input('is_bookmarked');

        $bookmark_end_date ='';

        $new_time = date("Y-m-d H:i:s", strtotime('+5 hours +30 minutes'));

        if($is_bookmarked !=0)
        {
            
            if(!empty($request->input('bookmark_end_date')))
            {
               $date =$request->input('bookmark_end_date');

                $res = explode("/", $date);
                $date = $res[2]."-".$res[0]."-".$res[1];

                $bookmark_end_date =  $date;
            }
        }

        

        

        $userId = Auth::id();
        
//        echo "-----------".$userId; die();


        if(!empty($bookmark_end_date))
        {
          $post =new Posts();
          $post->post_title =$post_title;
          $post->post_info =$post_information;
          $post->member_id =$userId;
          $post->post_type =$post_type;
          $post->private_member_id =$private_member;
          $post->is_bookmarked =$is_bookmarked;
          $post->video_link =$video_link;
          $post->bookmark_end_date =$bookmark_end_date;
          $post->created_at =$new_time;

         /* $post = DB::table('post_master')
                        ->insert(
                                ['post_title'=>$post_title,
                                'post_info'=>$post_information,  
                                'member_id'=>$userId,
                                'post_type'=>$post_type,
                                'private_member_id'=>$private_member,
                                'is_bookmarked'=>$is_bookmarked,
                                'video_link' =>$video_link,
                                'bookmark_end_date'=>$bookmark_end_date,
                                'created_at' =>$new_time
                                ]);*/

          
        }
        else
        {
          $post =new Posts();
          $post->post_title =$post_title;
          $post->post_info =$post_information;
          $post->member_id =$userId;
          $post->post_type =$post_type;
          $post->private_member_id =$private_member;
          $post->is_bookmarked =$is_bookmarked;
          $post->video_link =$video_link;
         // $post->bookmark_end_date =$bookmark_end_date;
          $post->created_at =$new_time;


          /*$post = DB::table('post_master')
                        ->insert(
                                ['post_title'=>$post_title,
                                'post_info'=>$post_information,  
                                'member_id'=>$userId,
                                'post_type'=>$post_type,
                                'private_member_id'=>$private_member,
                                'is_bookmarked'=>$is_bookmarked,
                                'video_link' =>$video_link,
                                'created_at' =>$new_time
                                ]);*/
        }

        $post->save();


        $update_data = array(
                        'post_read' => 0,

                    );

       Users::where('status', '=', 1)->update($update_data);
        
        

        //echo $bookmark_end_date;die;

         /*$id = DB::getPdo()->lastInsertId();  

         echo $id;die;*/
                    
     $industry_ids = $request->input('industry_id');
                if(!empty($industry_ids)){
                    PostIndustry::where('post_id', '=', $post->id)->delete();
                    foreach($industry_ids as $ii){
                        $post_id = $post->id;
                        $PostIndustry = new PostIndustry;
                        $PostIndustry->industry_category_id = $ii;
                        $PostIndustry->status = '1';
                        $PostIndustry->post_id = $post_id;
                        $PostIndustry->save();
                        }
                    }

      $category_ids = $request->input('category_id');

      //dd($category_ids);
                if(!empty($category_ids)){
                    PostCategory::where('post_id', '=', $post->id)->delete();
                    foreach($category_ids as $iii){
                        $post_id = $post->id;
                        $PostCategory = new PostCategory;
                        $PostCategory->category_id = $iii;
                        $PostCategory->status = '1';
                        $PostCategory->post_id = $post_id;
                        //dd($PostCategory);
                        $PostCategory->save();
                        }
                    }

      return back()->with('msg', 'Post Saved Successfully')
        ->with('msg_class', 'alert alert-success');
    }

    public function edit(Request $request, $id)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mainPost';
    	$DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addmainPost';
        $DataBag['data'] = DB::table('post_master')
          ->where([
              ['status','=','1'],
              ['id','=',$id]
          ])->get()->toArray();

          if(!empty($DataBag['data'][0]->bookmark_end_date))
          {
           

           $date=date('m-d-Y', strtotime($DataBag['data'][0]->bookmark_end_date));

            $date = str_replace('-', '/', $date);
            

            $DataBag['data'][0]->bookmark_end_date =  $date;
          }

          //dd($DataBag['data'][0]->bookmark_end_date);
        
        $DataBag['member'] = DB::table('users')
          ->where([
              ['user_type','!=','1'],
              ['status','=','1']
          ])->get()->toArray();
        
        $DataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();

        $DataBag['categoryList'] = Categories::where('status', '!=', '3')->orderBy('id', 'desc')->get();

        $DataBag['PostIndustry'] = PostIndustry::where('post_id', '=', $id)->pluck('industry_category_id')->toArray();

        $DataBag['PostCategory'] = PostCategory::where('post_id', '=', $id)->pluck('category_id')->toArray();

        //dd($DataBag['industryList']);
    	return view('dashboard.main_post.add_edit', $DataBag);
    }

    public function update(Request $request, $id)
    {
        $post_title= $request->input('post_title');
        $video_link =$request->input('video_link');
        $post_information = $request->input('post_information');
        $post_type = $request->input('post_type');
        $private_member = $request->input('private_member');

        $is_bookmarked = $request->input('is_bookmarked');

        $bookmark_end_date ='';


        if($is_bookmarked !=0)
        {
          if(!empty($request->input('bookmark_end_date')))
          {
           $date =$request->input('bookmark_end_date');

            $res = explode("/", $date);
            $date = $res[2]."-".$res[0]."-".$res[1];

            $bookmark_end_date =  $date;
          }

        }

        
        //echo $bookmark_end_date;die;

        $userId = Auth::id();

        if(!empty($bookmark_end_date))
        {
            DB::table('post_master')
              ->where('id', $id)
              ->update([
                    'post_title'=>$post_title,
                    'post_info'=>$post_information,  
                    'post_type'=>$post_type,
                    'private_member_id'=>$private_member,
                    'video_link' =>$video_link,
                    'is_bookmarked'=>$is_bookmarked,
                    'bookmark_end_date'=>$bookmark_end_date,
                    'updated_at' => date('Y-m-d'),
                      ]);
        }
        else
        {
            DB::table('post_master')
              ->where('id', $id)
              ->update([
                    'post_title'=>$post_title,
                    'post_info'=>$post_information,  
                    'post_type'=>$post_type,
                    'private_member_id'=>$private_member,
                    'video_link' =>$video_link,
                    'is_bookmarked'=>$is_bookmarked,
                    'updated_at' => date('Y-m-d'),
                      ]);
        }

                


              $industry_ids = $request->input('industry_id');

              //dd($industry_ids);
                if(!empty($industry_ids)){
                    PostIndustry::where('post_id', '=', $id)->delete();
                    foreach($industry_ids as $ii){
                        $post_id = $id;
                        $PostIndustry = new PostIndustry;
                        $PostIndustry->industry_category_id = $ii;
                        $PostIndustry->status = '1';
                        $PostIndustry->post_id = $post_id;
                        $PostIndustry->save();
                        }
                    }

                    $category_ids = $request->input('category_id');

              //dd($industry_ids);
                if(!empty($category_ids)){
                    PostCategory::where('post_id', '=', $id)->delete();
                    foreach($category_ids as $iii){
                        $post_id = $id;
                        $PostCategory = new PostCategory;
                        $PostCategory->category_id = $iii;
                        $PostCategory->status = '1';
                        $PostCategory->post_id = $post_id;
                        $PostCategory->save();
                        }
                    }
                
    	
    		return back()->with('msg', 'Post Updated Successfully')
    		->with('msg_class', 'alert alert-success');
    	

//    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function delete($id)
    {
        DB::table('post_master')
              ->where('id', $id)
              ->update([                  
                  'status' => 3,
                  'updated_at' => date('Y-m-d'),
                      ]);
        
        return back()->with('msg', 'Post Deleted Successfully')
    		->with('msg_class', 'alert alert-success');
    }

    /*********************** BULK ACTION ****************************/

    public function bulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $Contents = Career::find($id);
                        $Contents->status = '1';
                        $Contents->save();
                    }
                    $msg = 'Records Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Contents = Career::find($id);
                        $Contents->status = '2';
                        $Contents->save();
                    }
                    $msg = 'Records Deactivated Succesfully.';
                    break;

                case 'ordering':
                    if($request->has('page_order')) {
                        $i = 0;
                        $displayOrderArr = $request->input('page_order');
                        foreach($idsArr as $id) {
                            $Contents = Career::find($id);
                            $Contents->page_order = $displayOrderArr[$i];
                            $Contents->save();
                            $i++;
                        }
                        $msg = 'Records Ordering Succesfully.';
                    }
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Contents = Career::find($id)->delete();
                        CareerImagesMap::where('career_id', '=', $id)->delete();
                    }
                    $msg = 'Records Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    
}
