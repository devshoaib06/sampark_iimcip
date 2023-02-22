<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\IndustryCategories;
use App\Models\Users;
use App\Models\Posts;
use File;
use Storage;
use Image;
use Auth;
use DB;

class CategoryController extends Controller
{

    public function all_Cats() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'category';
        $DataBag['childMenu'] = 'allCategory';
        $DataBag['allFCats'] = Categories::where('status', '!=', '3')->orderBy('created_at', 'desc')->orderBy('name', 'asc')->get();
        return view('dashboard.file.all_real_categories', $DataBag);
    }

    public function create_cats() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'category';
        $DataBag['childMenu'] = 'addCategory';
        $DataBag['allFCats'] = Categories::where('status', '!=', '3')->get();
        return view('dashboard.file.create_category_real', $DataBag);
    }

    public function save_Cats(Request $request) {
        $IndustryCategories = new Categories;
        $IndustryCategories->name = trim( ucfirst($request->input('category')) );
        //$IndustryCategories->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $IndustryCategories->status = trim($request->input('status'));
        //$IndustryCategories->created_by = Auth::user()->id;
        if( $IndustryCategories->save() ) {
            $file_category_id = $IndustryCategories->id;

            return back()->with('msg', 'Category Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_Cats($id) {
        $IndustryCategories = Categories::findOrFail($id);
        $IndustryCategories->status = '3';
        if( $IndustryCategories->save() ) {
            
            //IndustryCategoriesMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Category Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function activeInactive() {

        
        $table =$_GET['tab'];

        $id =$_GET['id'];

        $val =$_GET['val'];


        if($table=='industry_category')
        {
            
            $IndustryCategories = IndustryCategories::findOrFail($id);
        }
        else if($table=='users')
        {
            
            $IndustryCategories = Users::findOrFail($id);
        }
        else if($table=='main_posts')
        {
            
            $IndustryCategories = Posts::findOrFail($id);
        }
        else
        {
            $IndustryCategories = Categories::findOrFail($id);
        }



       
        $IndustryCategories->status = $val;

    
        
        
        if( $IndustryCategories->save() ) {
            
            //IndustryCategoriesMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();
            
            return back()->with('msg', 'Status Changed Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }


    public function edit_Cats($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'category';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Categories::findOrFail($file_category_id);
        $DataBag['allFCats'] = Categories::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_category_real', $DataBag);
    }

    public function update_Cats(Request $request, $file_category_id) {
        $IndustryCategories = Categories::find($file_category_id);
        $IndustryCategories->name = trim( ucfirst($request->input('category')) );
        
        
        //$IndustryCategories->updated_by = Auth::user()->id;
        if( $IndustryCategories->save() ) {

            return back()->with('msg', 'Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
