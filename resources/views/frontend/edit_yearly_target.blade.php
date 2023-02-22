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
            <h4 class="pb-3">Edit Yearly Targets <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('front.user.addyearlytarget')}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All  Yearly Targets
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
             <form action="{{route('yearly.targets.update', $targets->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-4">
                     <div class="form-group">
                <label for="financial_year" class="form-label">Financial Year</label>
                <input type="text" class="form-control" id="financial_year" name="financial_year" value="{{ $targets->financial_year }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="revenue" class="form-label">Revenue</label>
                <input type="text" class="form-control" id="revenue" name="revenue" value="{{ $targets->revenue }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="volume" class="form-label">Volume</label>
                <input type="text" class="form-control" id="volume" name="volume" value="{{ $targets->volume }}">
            </div>
            </div>
          
          
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('front.user.addyearlytarget')}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection