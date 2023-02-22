<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResponseBrief;
use App\Models\ResponseDetail;
use App\Models\Users;
use App\Models\Incubatee;
use App\Models\Parameter;
use App\Models\Question;
use File;
use Storage;
use Image;
use Auth;
use DB;

class MentordiagonosticsController extends Controller
{

    public function all_mentordiagonostics()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mentordiagonostics';
        $DataBag['childMenu'] = 'allmentordiagonostics';
        $DataBag['mentorList'] = ResponseBrief::with(['getMentorName', 'getIncubatee', 'getDiagnostic'])->select([DB::raw('DISTINCT(mentor_id), incubatee_id,diagnostic_id')])->get();
        // dd($DataBag);
        return view('dashboard.mentordiagonostic.all_mentordiagonostics', $DataBag);
    }


    public function mentordiagonosticDetails(Request $request)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mentordiagonostics';
        $DataBag['childMenu'] = 'allmentordiagonostics';
        $DataBag['mentorList'] = ResponseBrief::with(['getParameter'])->select([DB::raw('DISTINCT(mentor_id), incubatee_id,parameter_id,id,parameter_score,comment')])
            ->where('mentor_id', $request->input('mentor_id'))
            ->where('incubatee_id', $request->input('incubatee_id'))
            ->where('diagnostic_id', $request->input('diagnostic_id'))
            ->get();

        return view('dashboard.mentordiagonostic.mentordiagonostic_detail', $DataBag);
    }

    public function questionAnswer(Request $request)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'mentordiagonostics';
        $DataBag['childMenu'] = 'allmentordiagonostics';


        // $DataBag['mentorList'] = ResponseBrief::with(['getParameter'])->select([DB::raw('DISTINCT(mentor_id), incubatee_id,parameter_id,id,parameter_score,comment')])
        //     ->where('mentor_id', $request->input('mentor_id'))
        //     ->where('incubatee_id', $request->input('incubatee_id'))
        //     ->get();
        // dd($DataBag['mentorList']);

        $mentor_id = $request->input('mentor_id');
        $incubatee_id = $request->input('incubatee_id');
        $parameter_id = $request->input('parameter_id');
        $DataBag['parameter'] = Parameter::findOrFail($parameter_id);
        $DataBag['questions'] = Question::where('parameter_id', $parameter_id)->where('status', '1')->get();
        $DataBag['responseBrief'] = ResponseBrief::with(['getResponseDetails'])
            ->where('parameter_id', $parameter_id)
            ->where('mentor_id', $mentor_id)
            ->where('incubatee_id', $incubatee_id)
            ->first();
        $DataBag['quest'] = [];


        if ($DataBag['responseBrief']) {
            if (count($DataBag['responseBrief']->getResponseDetails)) {
                // dd($DataBag['responseBrief']->getResponseDetails);
                $DataBag['quest'] = $DataBag['responseBrief']->getResponseDetails->pluck('question_input', 'question_id');
            }
        }





        return view('dashboard.mentordiagonostic.questAns', $DataBag);
    }

    /*  public function create_parameters() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'parameters';
        $DataBag['childMenu'] = 'addparameters';
        $DataBag['allFCats'] = Parameter::where('status', '!=', '3')->get();
        return view('dashboard.parameter.create_parameters', $DataBag);
    }

    public function save_parameters(Request $request) {
        $Parameters = new Parameter;
        $Parameters->parameter_name = trim( ucfirst($request->input('parameter_name')) );
        //$LegalStatus->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $Parameters->status = trim($request->input('status'));
        //$LegalStatus->created_by = Auth::user()->id;
        if( $Parameters->save() ) {
            $file_category_id = $Parameters->id;

            return back()->with('msg', 'Parameters Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_parameters($id) {
        $LegalStatus = Parameter::findOrFail($id);
        $LegalStatus->status = '3';
        if( $LegalStatus->save() ) {
            
            //LegalStatusMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Parameters Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_parameters($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'legalStatus';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Parameter::findOrFail($file_category_id);
        $DataBag['allFCats'] = Parameter::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.parameter.create_parameters', $DataBag);
    }

    public function update_parameters(Request $request, $file_category_id) {
        $Parameters = Parameter::find($file_category_id);
        $Parameters->parameter_name = trim( ucfirst($request->input('parameter_name')) );
        
        
        //$LegalStatus->updated_by = Auth::user()->id;
        if( $Parameters->save() ) {

            return back()->with('msg', 'Parameters Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    } */
}
