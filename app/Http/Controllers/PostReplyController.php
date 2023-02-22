<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Image;
use Auth;
use DB;

class PostReplyController extends Controller
{
    
    public function index()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'postReply';
    	$DataBag['parentMenu'] = '';
    	$DataBag['childMenu'] = 'allpostReply';
        
        $DataBag['alldata'] = DB::table('post_reply as pr')->select('pr.*','pm.post_title','pm.post_title','pm.post_info','ur.first_name','ur.last_name')
                ->Leftjoin('post_master as pm','pr.post_id','=','pm.id')
                ->Leftjoin('users as ur','pr.replied_by','=','ur.id')
          ->where([
              ['pr.status','=','1']
          ])->get()->toArray();
        
//        echo "<pre>";
//        print_r($DataBag['alldata']);
//        die();
        
    	return view('dashboard.post_reply.index', $DataBag);
    }

    public function reply(Request $request, $id)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mainPost';
    	$DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addpostReply';
        $DataBag['data'] = DB::table('post_master')
          ->where([
              ['status','=','1'],
              ['id','=',$id]
          ])->get()->toArray();

        
        
        
    	return view('dashboard.post_reply.reply', $DataBag);
    }

    public function save(Request $request, $id)
    {

        $reply= $request->input('reply');

        $userId = Auth::id();
        
        
        \DB::table('post_reply')
                        ->insert(
                                ['post_id'=>$id,
                                'replied_by'=>$userId,  
                                'reply_text'=>$reply,                                    
                                'replied_on'=>'0',
                                'created_at' => date('Y-m-d'),
                                ]);
        
        
      $insertedId = DB::getPdo()->lastInsertId();
    	if($insertedId) {
    		return back()->with('msg', 'Reply Created Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }
    
    public function reply_of_reply(Request $request, $id)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'postReply';
    	$DataBag['parentMenu'] = '';
        $DataBag['childMenu'] = 'addpostReply';
        $DataBag['data'] = DB::table('post_reply')
          ->where([
              ['status','=','1'],
              ['id','=',$id]
          ])->get()->toArray();

        
        
        
    	return view('dashboard.post_reply.reply_of_reply', $DataBag);
    }
    
    public function saveROR(Request $request, $id)
    {
        $reply= $request->input('reply');

        $userId = Auth::id();
        
        $DataBag['ror'] = DB::table('post_reply')->select('*')                
          ->where([
              ['id','=',$id]
          ])->get()->toArray();
        
        foreach ($DataBag['ror'] as $ror) {
            $post_id = $ror->post_id;
            $reply_text = $ror->reply_text;
        }
        
        
        \DB::table('post_reply')
                        ->insert(
                                ['post_id'=>$post_id,
                                'replied_by'=>$userId,  
                                'reply_text'=>$reply_text,   
                                'reply_of_reply'=>$reply,
                                'replied_on'=>'1',
                                'created_at' => date('Y-m-d'),
                                ]);
        
        
      $insertedId = DB::getPdo()->lastInsertId();
    	if($insertedId) {
    		return back()->with('msg', 'Reply Of Reply Created Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

//    public function edit(Request $request, $id)
//    {
//        $DataBag = array();
//        $DataBag['GparentMenu'] = 'postReply';
//    	$DataBag['parentMenu'] = '';
//        $DataBag['childMenu'] = 'addpostReply';
//        $DataBag['data'] = DB::table('post_master')
//          ->where([
//              ['status','=','1'],
//              ['id','=',$id]
//          ])->get()->toArray();
//        
//        $DataBag['member'] = DB::table('users')
//          ->where([
//              ['user_type','!=','1'],
//              ['status','=','1']
//          ])->get()->toArray();
//        
//        
//        
//    	return view('dashboard.post_reply.reply', $DataBag);
//    }

//    public function update(Request $request, $id)
//    {
//        echo "Add"; die();
//        $post_title= $request->input('post_title');
//        $post_information = $request->input('post_information');
//        $post_type = $request->input('post_type');
//        $private_member = $request->input('private_member');
//        $userId = Auth::id();
//
//                DB::table('post_master')
//              ->where('id', $id)
//              ->update([
//                    'post_title'=>$post_title,
//                    'post_info'=>$post_information,  
//                    'member_id'=>$userId,
//                    'post_type'=>$post_type,
//                    'private_member_id'=>$private_member,
//                    'updated_at' => date('Y-m-d'),
//                      ]);
//                
//    	
//    		return back()->with('msg', 'Post Updated Successfully')
//    		->with('msg_class', 'alert alert-success');
//    	
//
////    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
//    }

    public function delete($id)
    {
        DB::table('post_reply')
              ->where('id', $id)
              ->update([                  
                  'status' => 3,
                  'updated_at' => date('Y-m-d'),
                      ]);
        
        return back()->with('msg', 'Reply Deleted Successfully')
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
