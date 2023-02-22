<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parameter;

use File;
use Storage;
use Image;
use Auth;
use DB;

class ParametersController extends Controller
{

    public function all_parameters()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'parameters';
        $DataBag['childMenu'] = 'allparameters';
        $DataBag['allFCats'] = Parameter::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.parameter.all_parameters', $DataBag);
    }

    public function create_parameters()
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'parameters';
        $DataBag['childMenu'] = 'addparameters';
        $DataBag['allFCats'] = Parameter::where('status', '!=', '3')->get();
        return view('dashboard.parameter.create_parameters', $DataBag);
    }

    public function save_parameters(Request $request)
    {
        $Parameters = new Parameter;
        $Parameters->parameter_name = trim(ucfirst($request->input('parameter_name')));
        //$LegalStatus->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;


        $Parameters->status = trim($request->input('status'));
        //$LegalStatus->created_by = Auth::user()->id;
        if ($Parameters->save()) {
            $file_category_id = $Parameters->id;

            return back()->with('msg', 'Parameters Created Successfully.')
                ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
            ->with('msg_class', 'alert alert-danger');
    }

    public function del_parameters($id)
    {
        $LegalStatus = Parameter::findOrFail($id);
        $LegalStatus->status = '3';
        if ($LegalStatus->save()) {

            //LegalStatusMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Parameters Deleted Successfully.')
                ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_parameters($file_category_id)
    {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'legalStatus';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = Parameter::findOrFail($file_category_id);
        $DataBag['allFCats'] = Parameter::where('status', '!=', '3')
            ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.parameter.create_parameters', $DataBag);
    }

    public function update_parameters(Request $request, $file_category_id)
    {
        $Parameters = Parameter::find($file_category_id);
        $Parameters->parameter_name = trim(ucfirst($request->input('parameter_name')));
        // $Parameters->parameter_name = trim( ucfirst($request->input('parameter_name')) );
        $Parameters->status = trim($request->input('status'));

        //$LegalStatus->updated_by = Auth::user()->id;
        if ($Parameters->save()) {

            return back()->with('msg', 'Parameters Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
            ->with('msg_class', 'alert alert-danger');
    }
}
