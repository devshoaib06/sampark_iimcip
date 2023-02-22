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
            <h4 class="pb-3">Edit Impacts <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('startup.addimpact',[$impact->startup_id])}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Impacts
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
             <form action="{{route('startup.impacts.update', $impact->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-4">
                     <div class="form-group">
                <label for="indirect_employees" class="form-label">Indirect Employees</label>
                <input type="text" class="form-control" id="indirect_employees" name="indirect_employees" value="{{ $impact->indirect_employees }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="employee_count" class="form-label">Employee Count</label>
                <input type="text" class="form-control" id="employee_count" name="employee_count" value="{{ $impact->employee_count }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="women_employee_count" class="form-label">Women Employee Count</label>
                <input type="text" class="form-control" id="women_employee_count" name="women_employee_count" value="{{ $impact->women_employee_count }}">
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="total_beneficiaries" class="form-label">Total Beneficiaries</label>
                <input type="text" class="form-control" id="total_beneficiaries" name="total_beneficiaries" value="{{ $impact->total_beneficiaries }}">
            </div>
            </div>
          
          
          
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('startup.addimpact',[$impact->startup_id])}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection