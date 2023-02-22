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
            <h4 class="pb-3">Edit Funding Needs <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('front.user.addfunding')}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Funding Needs
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
             <form action="{{route('funding.needs.update', $funds->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-12">
                     <div class="form-group">
                <label for="funding_requirement" class="form-label">Indirect Employees</label>
                <input type="text" class="form-control" id="funding_requirement" name="funding_requirement" value="{{ $funds->funding_requirement }}">
            </div>
            </div>
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('front.user.addfunding')}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection