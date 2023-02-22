<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LegalStatus;

use File;
use Storage;
use Image;
use Auth;
use DB;

class LegalStatusController extends Controller
{

    public function all_legalStatus() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'legalStatus';
        $DataBag['childMenu'] = 'allLegalStatus';
        $DataBag['allFCats'] = LegalStatus::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.file.all_legal_status', $DataBag);
    }

    public function create_legalStatus() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'legalStatus';
        $DataBag['childMenu'] = 'addLegalStatusCategory';
        $DataBag['allFCats'] = LegalStatus::where('status', '!=', '3')->get();
        return view('dashboard.file.create_legal_status', $DataBag);
    }

    public function save_legalStatus(Request $request) {
        $LegalStatus = new LegalStatus;
        $LegalStatus->legal_status = trim( ucfirst($request->input('legal_status')) );
        //$LegalStatus->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $LegalStatus->status = trim($request->input('status'));
        //$LegalStatus->created_by = Auth::user()->id;
        if( $LegalStatus->save() ) {
            $file_category_id = $LegalStatus->id;

            return back()->with('msg', 'Legal Status Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_legalStatus($id) {
        $LegalStatus = LegalStatus::findOrFail($id);
        $LegalStatus->status = '3';
        if( $LegalStatus->save() ) {
            
            //LegalStatusMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Legal Status Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_legalStatus($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'legalStatus';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = LegalStatus::findOrFail($file_category_id);
        $DataBag['allFCats'] = LegalStatus::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_legal_status', $DataBag);
    }

    public function update_legalStatus(Request $request, $file_category_id) {
        $LegalStatus = LegalStatus::find($file_category_id);
        $LegalStatus->legal_status = trim( ucfirst($request->input('legal_status')) );
        
        
        //$LegalStatus->updated_by = Auth::user()->id;
        if( $LegalStatus->save() ) {

            return back()->with('msg', 'Legal Status Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
