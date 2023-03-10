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
            <a href="{{route('startup.addyearlytarget',[$targets->startup_id])}}" class="btn btn-info">
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
            <div class="row">
                    <div class="col-md-12">
                        @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            @foreach ($errors->all() as $message)
                            <div> {{ $message }} </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
        {{-- <form action="{{ route('incubatee.task.update', $task->id) }}" method="POST"> --}}
             <form action="{{route('startup.targets.update', $targets->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-4">
                     <div class="form-group">
                <label for="financial_year" class="form-label">Financial Year</label>
                <select name="financial_year" required class="form-control">
                    @if (isset($finYear) && count($finYear) > 0)
                        @foreach ($finYear as $finY)
                            <option value="{{ old( 'financial_year', $finY->id) }}" {{old( 'financial_year', $finY->id)  == $targets->financial_year ? 'selected': '' }}>{{ $finY->display_year }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" id="financial_year" name="financial_year" value="{{ old( 'financial_year',$targets->financial_year) }}"> --}}
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="revenue" class="form-label">Revenue</label>
                <input type="text" class="form-control" id="revenue" name="revenue" value="{{ old( 'revenue', $targets->revenue) }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="volume" class="form-label">Volume</label>
                <input type="text" class="form-control" id="volume" name="volume" value="{{ old( 'volume', $targets->volume) }}">
            </div>
            </div>
          
          
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('startup.addyearlytarget',[$targets->startup_id])}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection