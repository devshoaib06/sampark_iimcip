<?php

class TaskCategoriesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id=null)
	{
		if (isset($_GET['parent']))
		{
			$parent=base64_decode($_GET['parent']);
		}
		else {
			$parent=0;
		}
	  //print_r($_GET); exit;
	  $sessionid = Session::get('userid');
          $status=!empty($_REQUEST['status']) ? $_REQUEST['status']:'Active'; 
          if($status=='Active'){
              $status=1;
          }else{
              $status=0;
          }
	 if (!isset($sessionid) && $sessionid=='')
	  {
		return Redirect::route('admin')->with('message','Please Login');
	  }
          $all_categories= TaskCategory::with('parent')->where('parent_id',$parent)->where('is_active',$status)->orderBy('name', 'asc')->get();
          
          $data=[
              'all_categories'=>$all_categories,
              'status'=>$status
          ];
          return View::make('admin.task_categories.index')->with($data);
	
	 // return View::make('admin.task_categories.index')->with('all_categories', $all_categories);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$sessionid = Session::get('userid');
	 if (!isset($sessionid) && $sessionid=='')
	  {
		return Redirect::route('admin')->with('message','Please Login');
	  }

		$categoryList=TaskCategory::with('children')->where('parent_id',0)->get();
		
		return View::make('admin.task_categories.create',array('categoryList' => $categoryList));
	}

	public function getCategories($cat_id=null) {
	  $getCategory=TaskCategory::where('parent_id',$cat_id)->get();
	  return $getCategory;
	}

	public function getUserCategory($user_id=null) {
		$categories=CategoryUser::with('category')->where('user_id',$user_id)->get();
		return $categories;
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		 if (isset($_POST['_token']) && !empty($_POST['_token'])) {
			$category = new TaskCategory;
			
			//$category->parent_id = $_POST['parent_id'];
			
			$category->parent_id="0";
			
			$category->name = $_POST['name'];
			if($category->save()) {
			Session::flash('flash_message', 'Category Added Successfully');
			return Redirect::route('admin.list_task_categories');
		    }
		    else {
		     Session::flash('flash_message', 'Sorry!! Category Cannot be added.');
		    return Redirect::route('admin.list_task_categories');
		   
		    }
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{  
		$sessionid = Session::get('userid');
		 if (!isset($sessionid) && $sessionid=='')
		  {
			return Redirect::route('admin')->with('message','Please Login');
		  }

		$id=base64_decode($id);
		$category = TaskCategory::find($id);
		$categoryList=TaskCategory::with('children')->where('parent_id',0)->get();
		return View::make('admin.task_categories.edit',compact('category','categoryList'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$sessionid = Session::get('userid');
	 if (!isset($sessionid) && $sessionid=='')
	  {
		return Redirect::route('admin')->with('message','Please Login');
	  }
		$category = TaskCategory::find($id);

		if (isset($_POST['_token']) && !empty($_POST['_token'])) {
			
			//$category->parent_id = $_POST['parent_id'];
			$category->parent_id = "0";
			
			$category->name = $_POST['name'];
			if($category->save()) {
				Session::flash('flash_message', 'Task Category Updated Successfully');
			return Redirect::route('admin.list_task_categories')->with('message','The task category  saved');
			
		    }
		    else {
		    Session::flash('flash_message', 'Sorry! Task Category Cannot be Added');
		    return Redirect::route('admin.list_task_categories');
		    
		    }
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function delete($id)
	{
		$sessionid = Session::get('userid');
		 if (!isset($sessionid) && $sessionid=='')
		  {
			return Redirect::route('admin')->with('message','Please Login');
		  }
		  $category=TaskCategory::find($id);
		  $category->delete();	
		  Session::flash('flash_message', 'Task Category Deleted Successfully');
		  return Redirect::back();
		
	}
	
	public function task_category_status_change()
	{
	
		if (isset($_POST['_token']) && !empty($_POST['_token'])) {
		
		$id=$_POST['id'];
		$category=TaskCategory::where("id",$id);
		
		if($category->count() > 0)
		{
			$category_array=$category->get();
			
			$status=$category_array[0]['is_active'];
			
			if($status=="0")
			{
				$new_status="1";
				
				$val="Active";
			}
			else
			{
				$new_status="0";
				
				$val="Inactive";
			}
			
			$category=TaskCategory::find($id);
			$category->is_active=$new_status;
			
			$category->save();
			
		}
		else
		{
			$val="error";
		}
		
		
		
			
			}
		else
		{
			$val="error";
		}
		
		echo $val;
		exit;
	
	}
	
	
	


}
