<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Parameter;
//use App\Models\ProgrammeSponsor;
use Carbon\Carbon;


class QuestionsController  extends Controller
{

    public function all_questions()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'questions';
        $DataBag['childMenu'] = 'allquestions';
        $DataBag['allFCats'] = Question::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.questions.all_questions', $DataBag);
    }

    public function create_questions()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'questions';
        $DataBag['childMenu'] = 'addquestions';
        $DataBag['allFCats'] = Question::where('status', '!=', '3')->get();
        $DataBag['allActiveParameters'] = Parameter::where('status', '1')->get();

        return view('dashboard.questions.create_questions', $DataBag);
    }

    public function save_questions(Request $request)
    {
        $CompanyType = new Question;
        $CompanyType->parameter_id = trim(ucfirst($request->input('parameter_id')));
        $CompanyType->question_text = trim(ucfirst($request->input('question_text')));

        $CompanyType->status = trim($request->input('status'));
        //$CompanyType->created_by = Auth::user()->id;
        if ($CompanyType->save()) {
            $file_category_id = $CompanyType->id;
            /* if(isset($request->parameter_id) && $request->input('parameter_id') != ''){

                $parameter =  new Parameter;
           
                $parameter->parameter_name=$request->input('parameter_name');
                $parameter->save();
            } */
            return back()->with('msg', 'Questions Created Successfully.')
                ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
            ->with('msg_class', 'alert alert-danger');
    }

    public function del_questions($id)
    {
        $CompanyType = Question::findOrFail($id);
        // $CompanyType->deleted_at =  Carbon::now();
        $CompanyType->status = '3';
        if ($CompanyType->save()) {

            //CompanyTypeMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Questions Deleted Successfully.')
                ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_questions($file_category_id)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'programmeType';
        $DataBag['childMenu'] = 'flCats';

        $DataBag['fileCat'] = Question::findOrFail($file_category_id);

        $DataBag['allActiveParameters'] = Parameter::where('status', '1')->get();

        return view('dashboard.questions.create_questions', $DataBag);
    }

    public function update_questions(Request $request, $file_category_id)
    {
        $CompanyType = Question::findOrFail($file_category_id);
        $CompanyType->parameter_id = trim(ucfirst($request->input('parameter_id')));
        $CompanyType->question_text = trim(ucfirst($request->input('question_text')));

        $CompanyType->status = trim($request->input('status'));

        //$CompanyType->updated_by = Auth::user()->id;
        if ($CompanyType->save()) {
            return back()->with('msg', 'Questions Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
            ->with('msg_class', 'alert alert-danger');
    }
}
