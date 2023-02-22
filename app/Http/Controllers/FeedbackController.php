<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\Parameter;
use App\Models\Question;
use App\Models\ResponseBrief;
use App\Models\ResponseDetail;
use File;
use Storage;
use Image;
use Auth;
use DB;

class FeedbackController extends Controller
{

    public function getParameterList(Request $request)
    {
        $DataBag = array();

        $DataBag['allParameters'] = Parameter::with(['getResponseBrief' => function ($q) use ($request) {
            $q->where('response_briefs.mentor_id', $request->input('mentor_id'));
            $q->where('response_briefs.incubatee_id', $request->input('incubatee_id'));
            $q->where('response_briefs.diagnostic_id', $request->input('diagnostic_id'));
        }])->where('status', '=', '1')->get();

        $request->session()->put('mentor_id', $request->input('mentor_id'));
        $request->session()->put('incubatee_id', $request->input('incubatee_id'));
        $request->session()->put('diagnostic_id', $request->input('diagnostic_id'));

        return view('frontend.feedback.parameter_list', $DataBag);
    }
    public function mentorFeedback(Request $request, $parameterId)
    {


        $DataBag = array();

        $DataBag['parameter'] = Parameter::findOrFail($parameterId);
        $DataBag['previous_record'] = Parameter::where('id', '<', $parameterId)->orderBy('id', 'desc')->first();
        $DataBag['next_record'] = Parameter::where('id', '>', $parameterId)->orderBy('id')->first();

        $mentor_id = $request->session()->get('mentor_id');
        $incubatee_id = $request->session()->get('incubatee_id');
        $diagnostic_id = $request->session()->get('diagnostic_id');

        if ($request->isMethod('post')) {
            $parameter_id = $request->input('parameter_id');
            $score = $request->input('score');
            $comment = $request->input('comment');
            $ans = $request->input('ans');


            $resId = ResponseBrief::updateOrCreate(
                [
                    'diagnostic_id' => $diagnostic_id,
                    'mentor_id' => $mentor_id,
                    'incubatee_id' => $incubatee_id,
                    'parameter_id' => $parameter_id
                ],
                [
                    'diagnostic_id' => $diagnostic_id,
                    'mentor_id' => $mentor_id,
                    'incubatee_id' => $incubatee_id,
                    'parameter_id' => $parameter_id,
                    'parameter_score' =>  $score,
                    'comment' => $comment
                ]
            );

            if (count($ans) > 0) {
                foreach ($ans as $key => $value) {
                    ResponseDetail::updateOrCreate(
                        [
                            'response_id' => $resId->id,
                            'question_id' => $key
                        ],
                        [
                            'response_id' => $resId->id,
                            'question_id' => $key,
                            'question_input' =>  $value,
                        ]
                    );
                }
            }

            if ($DataBag['next_record']) {

                return redirect()->route('mentorFeedback', ['id' => $DataBag['next_record']]);
            } else {
                return redirect()->route('thankYou');
            }
        }


        $DataBag['questions'] = Question::where('parameter_id', $parameterId)->where('status', '1')->get();
        $DataBag['responseBrief'] = ResponseBrief::with(['getResponseDetails'])
            ->where('diagnostic_id', $diagnostic_id)
            ->where('parameter_id', $parameterId)
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
        return view('frontend.feedback.feedback', $DataBag);
    }

    public function thankYou()
    {
        return view('frontend.feedback.thank_you');
        # code...
    }
}
