<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryCategories;

use File;
use Storage;
use Image;
use Auth;
use DB;

class IndustryController extends Controller
{

    public function all_industryCats() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryCategory';
        $DataBag['childMenu'] = 'allIndustryCategory';
        $DataBag['allFCats'] = IndustryCategories::where('status', '!=', '3')->orderBy('industry_category', 'asc')->get();
        return view('dashboard.file.all_categories', $DataBag);
    }

    public function create_industryCats() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryCategory';
        $DataBag['childMenu'] = 'addIndustryCategory';
        $DataBag['allFCats'] = IndustryCategories::where('status', '!=', '3')->get();
        return view('dashboard.file.create_category', $DataBag);
    }

    public function save_industryCats(Request $request) {
        $IndustryCategories = new IndustryCategories;
        $IndustryCategories->industry_category = trim( ucfirst($request->input('industry_category')) );
        //$IndustryCategories->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $IndustryCategories->status = trim($request->input('status'));
        //$IndustryCategories->created_by = Auth::user()->id;
        if( $IndustryCategories->save() ) {
            $file_category_id = $IndustryCategories->id;

            return back()->with('msg', 'Startup Business Verticals Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_industryCats($id) {
        $IndustryCategories = IndustryCategories::findOrFail($id);
        $IndustryCategories->status = '3';
        if( $IndustryCategories->save() ) {
            
            //IndustryCategoriesMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Startup Business Verticals Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_industryCats($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryCategory';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = IndustryCategories::findOrFail($file_category_id);
        $DataBag['allFCats'] = IndustryCategories::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_category', $DataBag);
    }

    public function update_industryCats(Request $request, $file_category_id) {
        $IndustryCategories = IndustryCategories::find($file_category_id);
        $IndustryCategories->industry_category = trim( ucfirst($request->input('industry_category')) );
        
        
        //$IndustryCategories->updated_by = Auth::user()->id;
        if( $IndustryCategories->save() ) {

            return back()->with('msg', 'Startup Business Verticals Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
