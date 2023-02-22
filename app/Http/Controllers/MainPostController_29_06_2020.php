<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryCategories;
use App\Models\PostIndustry;
use App\Models\PostReply;

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
              ['pm.status','=','1']
          ])
          ->get()->toArray();


         if(!empty($alldata)){
            $i=0; $memberBusiness= array();
            
            foreach($alldata as $user){
            $replyCnt=0;    
            $DataBag['alldata'][$i]->replyCnt=0; 
            $DataBag['alldata'][$i]->comments=array();   
            $replyCnt =  PostReply::where('post_id', '=', $user->id)->where('replied_on', '=', 0)->pluck('id')->count();
            $DataBag['alldata'][$i]->replyCnt= $replyCnt;
            
            //$DataBag['alldata'][$i]->comments =  PostReply::select('id','reply_text','replied_by', 'created_at')->where('post_id', '=', $user->id)->where('replied_on', '=', 0)->get();

            $DataBag['alldata'][$i]->comments = DB::table('post_reply')
            ->select('post_reply.id','reply_text','contact_name', 'post_reply.created_at')
             ->join('users', 'post_reply.replied_by', '=', 'users.id')
             ->where("post_reply.status", "=", '1')
             ->where('replied_on', '=', 0)
             ->where("post_id", "=", $user->id)->get();


            if(!empty($DataBag['alldata'][$i]->comments)){
                $j=0;
                foreach($DataBag['alldata'][$i]->comments as $c){
                    //$DataBag['alldata'][$i]->comments[$j]->reply =  PostReply::select('reply_of_reply','replied_by', 'created_at')->where('post_id', '=', $user->id)->where('replied_on', '=', $c->id)->get();

                    $DataBag['alldata'][$i]->comments[$j]->reply =  DB::table('post_reply')
                                ->select('post_reply.id','reply_of_reply','contact_name', 'post_reply.created_at')
                                 ->join('users', 'post_reply.replied_by', '=', 'users.id')
                                 ->where("post_reply.status", "=", '1')
                                 ->where('replied_on', '=', $c->id)
                                 ->where("post_id", "=", $user->id)->get();
                    $j++;
                }   
            }

           //dd($DataBag['alldata'][$i]->comments);

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
             $i++;
            }

        } 
        
//        echo "<pre>";
//        print_r($DataBag['alldata']);
//        die();
       # dd($DataBag['alldata']);
        
    	return view('dashboard.main_post.index', $DataBag);
    }

    public function add()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mainPost';
    	$DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addmainPost';

        $DataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();
        
        $DataBag['member'] = DB::table('users')
          ->where([
              ['user_type','!=','1'],
              ['status','=','1']
          ])->get()->toArray();

        
    	return view('dashboard.main_post.add_edit', $DataBag);
    }

    public function save(Request $request)
    {
        $post_title= $request->input('post_title');
        $post_information = $request->input('post_information');
        $post_type = $request->input('post_type');
        $private_member = $request->input('private_member');
        $userId = Auth::id();
        
//        echo "-----------".$userId; die();
        
        $post = DB::table('post_master')
                        ->insert(
                                ['post_title'=>$post_title,
                                'post_info'=>$post_information,  
                                'member_id'=>$userId,
                                'post_type'=>$post_type,
                                'private_member_id'=>$private_member,
                                'created_at' => date('Y-m-d'),
                                ]);

         $id = DB::getPdo()->lastInsertId();  
                    
     $industry_ids = $request->input('industry_id');
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

    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
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
        
        $DataBag['member'] = DB::table('users')
          ->where([
              ['user_type','!=','1'],
              ['status','=','1']
          ])->get()->toArray();
        
        $DataBag['industryList'] = IndustryCategories::orderBy('id', 'desc')->get();

        $DataBag['PostIndustry'] = PostIndustry::where('post_id', '=', $id)->pluck('industry_category_id')->toArray();
        //dd($DataBag['industryList']);
    	return view('dashboard.main_post.add_edit', $DataBag);
    }

    public function update(Request $request, $id)
    {
        $post_title= $request->input('post_title');
        $post_information = $request->input('post_information');
        $post_type = $request->input('post_type');
        $private_member = $request->input('private_member');
        $userId = Auth::id();

                DB::table('post_master')
              ->where('id', $id)
              ->update([
                    'post_title'=>$post_title,
                    'post_info'=>$post_information,  
                    'post_type'=>$post_type,
                    'private_member_id'=>$private_member,
                    'updated_at' => date('Y-m-d'),
                      ]);


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
