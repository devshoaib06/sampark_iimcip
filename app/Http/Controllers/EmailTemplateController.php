<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;

use File;
use Storage;
use Image;
use Auth;
use DB;

class EmailTemplateController extends Controller
{

    public function all_emailTemplate() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'emailTemplate';
        $DataBag['childMenu'] = 'allEmailTemplate';
        $DataBag['allFCats'] = EmailTemplate::where('status', '!=', '3')->orderBy('id', 'asc')->get();
        return view('dashboard.file.all_email_template', $DataBag);
    }

    public function create_emailTemplate() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'emailTemplate';
        $DataBag['childMenu'] = 'addEmailTemplateCategory';
        $DataBag['allFCats'] = EmailTemplate::where('status', '!=', '3')->get();
        return view('dashboard.file.create_email_template', $DataBag);
    }

    public function save_emailTemplate(Request $request) {
        $EmailTemplate = new EmailTemplate;
        $EmailTemplate->name = trim( ucfirst($request->input('name')) );
        $EmailTemplate->subject = trim( ucfirst($request->input('subject')) );
        $EmailTemplate->description = trim( $request->input('description') );
        //$EmailTemplate->description = trim( htmlentities( $request->input('description'), ENT_QUOTES) );

        $display_order = 0;

        
        $EmailTemplate->status = trim($request->input('status'));
        //$EmailTemplate->created_by = Auth::user()->id;
        if( $EmailTemplate->save() ) {
            $file_category_id = $EmailTemplate->id;

            return back()->with('msg', 'Email Template Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function del_emailTemplate($id) {
        $EmailTemplate = EmailTemplate::findOrFail($id);
        $EmailTemplate->status = '3';
        if( $EmailTemplate->save() ) {
            
            //EmailTemplateMap::where('file_category_id', '=', $id)->orWhere('file_subcategory_id', '=', $id)->delete();

            return back()->with('msg', 'Email Template Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        }

        return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function edit_emailTemplate($file_category_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'emailTemplate';
        $DataBag['childMenu'] = 'flCats';
        $DataBag['fileCat'] = EmailTemplate::findOrFail($file_category_id);
        $DataBag['allFCats'] = EmailTemplate::where('status', '!=', '3')
        ->where('id', '!=', $file_category_id)->get();
        return view('dashboard.file.create_email_template', $DataBag);
    }

    public function update_emailTemplate(Request $request, $file_category_id) {
        $EmailTemplate = EmailTemplate::find($file_category_id);
        $EmailTemplate->name = trim( ucfirst($request->input('name')) );
        $EmailTemplate->subject = trim( ucfirst($request->input('subject')) );
        $EmailTemplate->description = trim( $request->input('description') );
        
        
        //$EmailTemplate->updated_by = Auth::user()->id;
        if( $EmailTemplate->save() ) {

            return back()->with('msg', 'Email Template Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

}
