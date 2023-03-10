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
            <h4 class="pb-3">Edit Order Pipeline <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{route('startup.addorderpipe',[$orderpipe->startup_id])}}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Order Pipeline
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
             <form action="{{route('orderpipe.update', $orderpipe->id)}}" method="POST">
            {{ csrf_field() }}
            <div class="row">

                <div class="col-md-4">
                     <div class="form-group">
                <label for="month" class="form-label">Month</label>
                <select name="month" required class="form-control">
                    @if (isset($finMonth) && count($finMonth) > 0)
                        @foreach ($finMonth as $finM)
                            <option value="{{ old( 'month', $finM->id) }}" {{old( 'month', $finM->id) == $orderpipe->month ? 'selected': '' }}>{{ $finM->display_month }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" id="month" name="month" value="{{ $orderpipe->month }}"> --}}
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="financial_year" class="form-label">Financial Year</label>
                <select name="financial_year" required class="form-control">
                    @if (isset($finYear) && count($finYear) > 0)
                        @foreach ($finYear as $finY)
                            <option value="{{ old( 'financial_year', $finY->id) }}" {{old( 'financial_year', $finY->id) == $orderpipe->financial_year ? 'selected': '' }}>{{ $finY->display_year }}</option>
                        @endforeach
                    @endif
                </select>
                {{-- <input type="text" class="form-control" id="financial_year" name="financial_year" value="{{ $orderpipe->financial_year }}"> --}}
            </div>
            </div>

            <div class="col-md-4">
                     <div class="form-group">
                <label for="revenue" class="form-label">Product</label>
                <input type="text" name="product_id" class="form-control" value="{{ old( 'product_id', $orderpipe->product_id) }}">
                {{-- <select name="product_id[]" class="form-control indusCatIds" multiple= "multiple">
                    <option value="">Select Product</option>
                    @if(isset($allProducts) && count($allProducts))
                    @foreach($allProducts as $c)
                    <option value="{{  old( 'product_id', $c->id) }}" {{  old( 'product_id', $c->id) == $orderpipe->product_id?'selected':''}}>{{ $c->caption }}</option>
                    @endforeach
                    @endif
                </select> --}}
            </div>
            </div>
          
            <div class="col-md-4">
                     <div class="form-group">
                <label for="volume" class="form-label">volume</label>
                <input type="text" class="form-control" id="volume" name="volume" value="{{ old( 'volume', $orderpipe->volume) }}">
            </div>
            </div>

             <div class="col-md-4">
                     <div class="form-group">
                <label for="amount" class="form-label">Amount</label>
                <input type="text" class="form-control" id="amount" name="amount" value="{{ old( 'amount', $orderpipe->amount)}}">
            </div>
            </div>
          
          
          
            </div>
        </div>
        </div>
    </div>


        <div class="mt-3">
            <a href="{{route('startup.addorderpipe',[$orderpipe->startup_id])}}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
             </div>
        </form>
   
    </div>

    @endsection