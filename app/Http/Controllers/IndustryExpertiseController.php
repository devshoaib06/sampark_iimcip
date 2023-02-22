<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndustryExpertise;

use File;
use Storage;
use Image;
use Auth;
use DB;

class IndustryExpertiseController extends Controller
{

    public function all_industryExpertise() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryExpertise';
        $DataBag['childMenu'] = 'allIndustryExpertise';
        $DataBag['allFCats'] = IndustryExpertise::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.file.all_industry_expertise', $DataBag);
    }

    public function create_industryExpertise() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryExpertise';
        $DataBag['childMenu'] = 'addIndustryExpertiseCategory';
        $DataBag['allFCats'] = IndustryExpertise::where('status', '!=', '3')->get();
        return view('dashboard.file.create_industry_expertise', $DataBag);
    }

    public function save_industryExpertise(Request $request) {
        $IndustryExpertise = new IndustryExpertise;
        $IndustryExpertise->industry_expertise = trim( ucfirst($request->input('industry_expertise')) );
        //$IndustryExpertise->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $IndustryExpertise->status = trim($request->input('status'));
        //$IndustryExpertise->created_by = Auth::user()->id;
        if( $IndustryExpertise->save() ) {
            $file_category_id = $IndustryExpertise->id;

            return back()->with('msg', 'Industry Expertise Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_industryExpertise($id) {
        $IndustryExpertise = IndustryExpertise::findOrFail($id);
        $IndustryExpertise->status = '3';
        if( $IndustryExpertise->save() ) {
            
            //IndustryExpertiseMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Industry Expertise Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_industryExpertise($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'industryExpertise';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = IndustryExpertise::findOrFail($file_category_id);
        $DataBag['allFCats'] = IndustryExpertise::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_industry_expertise', $DataBag);
    }

    public function update_industryExpertise(Request $request, $file_category_id) {
        $IndustryExpertise = IndustryExpertise::find($file_category_id);
        $IndustryExpertise->industry_expertise = trim( ucfirst($request->input('industry_expertise')) );
        
        
        //$IndustryExpertise->updated_by = Auth::user()->id;
        if( $IndustryExpertise->save() ) {

            return back()->with('msg', 'Industry Expertise Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
