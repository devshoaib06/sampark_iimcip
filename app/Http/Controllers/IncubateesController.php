<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incubatee;

use File;
use Storage;
use Image;
use Auth;
use DB;

class IncubateesController extends Controller
{

    public function all_incubatees() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'incubatees';
        $DataBag['childMenu'] = 'allincubatees';
        $DataBag['allFCats'] = Incubatee::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.incubatee.all_incubatees', $DataBag);
    }

    public function create_incubatees() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'incubatees';
        $DataBag['childMenu'] = 'addincubatees';
        $DataBag['allFCats'] = Incubatee::where('status', '!=', '3')->get();
        return view('dashboard.incubatee.create_incubatees', $DataBag);
    }

    public function save_incubatees(Request $request) {
        $Incubatees = new Incubatee;
        $Incubatees->incubatee_name = trim( ucfirst($request->input('incubatee_name')) );
        //$LegalStatus->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $Incubatees->status = trim($request->input('status'));
        //$LegalStatus->created_by = Auth::user()->id;
        if( $Incubatees->save() ) {
            $file_category_id = $Incubatees->id;

            return back()->with('msg', 'Incubatees Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_incubatees($id) {
        $Incubatees = Incubatee::findOrFail($id);
        $Incubatees->status = '3';
        if( $Incubatees->save() ) {
            
            //LegalStatusMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Incubatees Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_incubatees($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'incubatees';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Incubatee::findOrFail($file_category_id);
        $DataBag['allFCats'] = Incubatee::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.incubatee.create_incubatees', $DataBag);
    }

    public function update_incubatees(Request $request, $file_category_id) {
        $Incubatees = Incubatees::find($file_category_id);
        $Incubatees->incubatee_name = trim( ucfirst($request->input('incubatee_name')) );
        
        
        //$LegalStatus->updated_by = Auth::user()->id;
        if( $Incubatees->save() ) {

            return back()->with('msg', 'Incubatees Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
