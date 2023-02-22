<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Sponsors;
use App\Models\ProgrammeSponsor;
use Carbon\Carbon;


class ProgrammeController extends Controller
{
   
    public function all_programmeType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'programmeType';
        $DataBag['childMenu'] = 'allprogrammeType';
        $DataBag['allFCats'] = Programme::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.programme.all_programme_type', $DataBag);
    }

    public function create_programmeType() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'programmeType';
        $DataBag['childMenu'] = 'addProgrammeTypeCategory';
        $DataBag['allFCats'] = Programme::where('status', '!=', '3')->get();
        $DataBag['allActiveSponsor'] = Sponsors::where('status','1')->get();

        return view('dashboard.programme.create_programme_type', $DataBag);
    }

    public function save_programmeType(Request $request) {
        $CompanyType = new Programme;
        $CompanyType->name = trim( ucfirst($request->input('sponsor_name')) );
        $CompanyType->programme_max_startup = trim( ucfirst($request->input('programme_max_startup')) );
        $CompanyType->brief = trim( ucfirst($request->input('brief')) );
        $CompanyType->programme_application_start_date = trim( ucfirst($request->input('application_start_date')) );
        $CompanyType->programme_application_end_date = trim( ucfirst($request->input('application_end_date')) );
        $CompanyType->programme_start_date = trim( ucfirst($request->input('programme_start_date')) );
        //$CompanyType->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $CompanyType->status = trim($request->input('status'));
        //$CompanyType->created_by = Auth::user()->id;
        if( $CompanyType->save() ) {
            $file_category_id = $CompanyType->id;
            if(isset($request->sponsor_id) && $request->input('sponsor_id') != ''){

                $programsponsor =  new ProgrammeSponsor;
                $programsponsor->programme_id=$CompanyType->id;
                $programsponsor->sponsor_id=$request->input('sponsor_id');
                $programsponsor->save();
            }
            return back()->with('msg', 'Programme Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_programmeType($id) {
        $CompanyType = Programme::findOrFail($id);
        $CompanyType->deleted_at =  Carbon::now();
        if( $CompanyType->save() ) {
            
            //CompanyTypeMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Programme Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_programmeType($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'programmeType';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Programme::with('programmsponsor')->findOrFail($file_category_id);
        $DataBag['allFCats'] = Programme::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        $DataBag['allActiveSponsor'] = Sponsors::where('status','1')->get();
        return view('dashboard.programme.create_programme_type', $DataBag);
    }

    public function update_programmeType(Request $request, $file_category_id) {
        $CompanyType = Programme::find($file_category_id);
        $CompanyType->name = trim( ucfirst($request->input('sponsor_name')) );
        $CompanyType->programme_max_startup = trim( ucfirst($request->input('programme_max_startup')) );
        $CompanyType->brief = trim( ucfirst($request->input('brief')) );
        $CompanyType->programme_application_start_date = trim( ucfirst($request->input('application_start_date')) );
        $CompanyType->programme_application_end_date = trim( ucfirst($request->input('application_end_date')) );
        $CompanyType->programme_start_date = trim( ucfirst($request->input('programme_start_date')) );
        $CompanyType->status = trim( ucfirst($request->input('status')) );
        
        
        //$CompanyType->updated_by = Auth::user()->id;
        if( $CompanyType->save() ) {

            if(isset($request->sponsor_id) && $request->input('sponsor_id') != ''){
                ProgrammeSponsor::updateOrCreate(
                    ['programme_id' => $CompanyType->id],
                    ['sponsor_id' => $request->input('sponsor_id')]
                );
         
            }
            return back()->with('msg', 'Programme name Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }


}
