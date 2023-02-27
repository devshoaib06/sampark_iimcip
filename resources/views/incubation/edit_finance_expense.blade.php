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
            <h4 class="pb-3">Edit Financial Expense <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('startup.addfinexp',[$finexpense->startup_id])}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Financial Expenses
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
             <form action="{{route('startup.finexp.update', $finexpense->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

            <div class="col-md-4">
                     <div class="form-group">
                <label for="month" class="form-label">Month</label>
                <select name="month" required class="form-control">
                    @if (isset($finMonth) && count($finMonth) > 0)
                        @foreach ($finMonth as $finM)
                            <option value="{{ $finM->id }}" {{$finM->id == $finexpense->month ? 'selected': '' }}>{{ $finM->display_month }}</option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" name="startup_id" value="{{ $finexpense->startup_id }}">

                {{-- <input type="text" class="form-control" id="month" name="month" value="{{ $finexpense->month }}"> --}}
            </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                <label for="financial_year" class="form-label">Financial Year</label>
                <select name="financial_year" required class="form-control">
                    @if (isset($finYear) && count($finYear) > 0)
                        @foreach ($finYear as $finY)
                            <option value="{{ $finY->id }}" {{$finY->id == $finexpense->financial_year ? 'selected': '' }}>{{ $finY->display_year }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" id="financial_year" name="financial_year" value="{{ $finexpense->financial_year }}"> --}}
            </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                <label for="raw_material" class="form-label">Raw Material</label>
                <input type="text" class="form-control" id="raw_material" name="raw_material" value="{{ $finexpense->raw_material }}">
            </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                <label for="salary_wages" class="form-label">Salary Wages</label>
                <input type="text" class="form-control" id="salary_wages" name="salary_wages" value="{{ $finexpense->salary_wages }}">
            </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                <label for="other_expenses" class="form-label">Other Expenses</label>
                <input type="text" class="form-control" id="other_expenses" name="other_expenses" value="{{ $finexpense->other_expenses }}">
            </div>
            </div>
            <div class="col-md-4">
                     <div class="form-group">
                <label for="capex" class="form-label">capex</label>
                <input type="text" class="form-control" id="capex" name="capex" value="{{ $finexpense->capex }}">
            </div>
            </div>
          
          
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('startup.addfinexp',[$finexpense->startup_id])}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection