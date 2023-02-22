<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyType;

use File;
use Storage;
use Image;
use Auth;
use DB;

class CompanyTypeController extends Controller
{

    public function all_companyType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'companyType';
        $DataBag['childMenu'] = 'allCompanyType';
        $DataBag['allFCats'] = CompanyType::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.file.all_company_type', $DataBag);
    }

    public function create_companyType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'companyType';
        $DataBag['childMenu'] = 'addCompanyTypeCategory';
        $DataBag['allFCats'] = CompanyType::where('status', '!=', '3')->get();
        return view('dashboard.file.create_company_type', $DataBag);
    }

    public function save_companyType(Request $request) {
        $CompanyType = new CompanyType;
        $CompanyType->company_type = trim( ucfirst($request->input('company_type')) );
        //$CompanyType->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $CompanyType->status = trim($request->input('status'));
        //$CompanyType->created_by = Auth::user()->id;
        if( $CompanyType->save() ) {
            $file_category_id = $CompanyType->id;

            return back()->with('msg', 'Company Type Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_companyType($id) {
        $CompanyType = CompanyType::findOrFail($id);
        $CompanyType->status = '3';
        if( $CompanyType->save() ) {
            
            //CompanyTypeMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Company Type Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_companyType($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'companyType';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = CompanyType::findOrFail($file_category_id);
        $DataBag['allFCats'] = CompanyType::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_company_type', $DataBag);
    }

    public function update_companyType(Request $request, $file_category_id) {
        $CompanyType = CompanyType::find($file_category_id);
        $CompanyType->company_type = trim( ucfirst($request->input('company_type')) );
        
        
        //$CompanyType->updated_by = Auth::user()->id;
        if( $CompanyType->save() ) {

            return back()->with('msg', 'Company Type Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
