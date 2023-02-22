<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use Carbon\Carbon;


class LocationController extends Controller
{
   
    public function all_locationType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'locationType';
        $DataBag['childMenu'] = 'alllocationType';
        $DataBag['allFCats'] = Location::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.location.all_location_type', $DataBag);
    }

    public function create_locationType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'locationType';
        $DataBag['childMenu'] = 'addLocationTypeCategory';
        $DataBag['allFCats'] = Location::where('status', '!=', '3')->get();
        return view('dashboard.location.create_location_type', $DataBag);
    }

    public function save_locationType(Request $request) {
        $CompanyType = new Location;
        $CompanyType->name = trim( ucfirst($request->input('sponsor_name')) );
 
        //$CompanyType->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $CompanyType->status = trim($request->input('status'));
        //$CompanyType->created_by = Auth::user()->id;
        if( $CompanyType->save() ) {
            $file_category_id = $CompanyType->id;

            return back()->with('msg', 'location Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_locationType($id) {
        $CompanyType = Location::findOrFail($id);
        $CompanyType->deleted_at =  Carbon::now();
        if( $CompanyType->save() ) {
            
            //CompanyTypeMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Location Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_locationType($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'locationType';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Location::findOrFail($file_category_id);
        $DataBag['allFCats'] = Location::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.location.create_location_type', $DataBag);
    }

    public function update_locationType(Request $request, $file_category_id) {
        $CompanyType = Location::find($file_category_id);
        $CompanyType->name = trim( ucfirst($request->input('sponsor_name')) );
  
        $CompanyType->status = trim( ucfirst($request->input('status')) );
        
        
        //$CompanyType->updated_by = Auth::user()->id;
        if( $CompanyType->save() ) {

            return back()->with('msg', 'Location  Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }


}
