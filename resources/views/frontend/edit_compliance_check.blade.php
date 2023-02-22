@extends('frontend.layouts.app')
@section('content')
<div class="row">
    @if(Session::has('msg') && Session::has('msg_class'))
    <div class="col-sm-12">
        <div class="postCard">
            <div class="{{ Session::get('msg_class') }}" style="margin-bottom: 0;">
                {{ Session::get('msg') }}
            </div>
        </div>
    </div>
    @endif
</div>

<div>
        <div class="float-start">
            <h4 class="pb-3">Edit Compliance Checks <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('front.user.addcompl')}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Compliance Checks
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

    {{-- <div class="card card-body bg-light p-4"> --}}
        <div class="col-sm-12">
      <div class="postCard manage-wrap">
         <div class="postWrap">
            <div class="pwdbox">
        {{-- <form action="{{ route('incubatee.task.update', $task->id) }}" method="POST"> --}}
             <form action="{{route('compliancechecks.update', $compliance->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-6">
                  <div class="form-group">
                                <label> Audited Financials : </label>
                                        <select name="audited_financials" required class="form-control">
                                        <option value="Yes" {{$compliance->audited_financials== 'Yes' ? 'selected':''}}>Yes</option>
                                        <option value="No" {{$compliance->audited_financials== 'No' ? 'selected':''}}>No</option>
                                       
                                        </select>
                        </div>
            </div>

             <div class="col-md-6">
                        <div class="form-group">
                                <label> Gst Compliance : </label>
                                        <select name="gst_compliance" required class="form-control">
                                        <option value="Yes" {{$compliance->gst_compliance == 'Yes' ? 'selected':''}}>Yes</option>

                                        <option value="No"{{ $compliance->gst_compliance== 'No' ? 'selected':''}}>No</option>
                                       
                                        </select>
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('front.user.addcompl')}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection