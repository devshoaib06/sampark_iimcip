@extends('frontend.layouts.app')
@section('content')
    <div class="row">
        @if (Session::has('msg') && Session::has('msg_class'))
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
            <h4 class="pb-3">Edit Task <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{ route('startup.addfin', [$finance->startup_id]) }}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Financial Year Report
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
                    <form action="{{ route('startup.finyear.update', $finance->id) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    {{-- <label for="financial_year" class="form-label">Financial Year</label>
                        <input type="text" class="form-control" id="financial_year" name="financial_year" value="{{ $finance->financial_year }}"> --}}
                                    <label for="financial_year" class="form-label">Financial Year</label>
                                    <select name="financial_year" required class="form-control">
                                        @if (isset($finYear) && count($finYear) > 0)
                                            @foreach ($finYear as $finY)
                                                <option value="{{ $finY->id }}"
                                                    {{ $finY->id == $finance->financial_year ? 'selected' : '' }}>
                                                    {{ $finY->display_year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="revenue" class="form-label">Revenue</label>
                                    <input type="text" class="form-control" id="revenue" name="revenue"
                                        value="{{ $finance->revenue }}">
                                </div>
                            </div>

                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="gmv" class="form-label">GMV</label>
                                    <input type="text" class="form-control" id="gmv" name="gmv"
                                        value="{{ $finance->gmv }}">
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="expense" class="form-label">Expense</label>
                                    <input type="text" class="form-control" id="expense" name="expense"
                                        value="{{ $finance->expense }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer_count" class="form-label">Customer Count</label>
                                    <input type="text" class="form-control" id="customer_count" name="customer_count"
                                        value="{{ $finance->customer_count }}">
                                </div>
                            </div>



                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ebitda" class="form-label">EBITDA</label>
                                    <input type="text" class="form-control" id="ebitda" name="ebitda"
                                        value="{{ $finance->ebitda }}">
                                </div>
                            </div> --}}

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="net_profit" class="form-label">Net Profit</label>
                                    <input type="text" class="form-control" id="net_profit" name="net_profit"
                                        value="{{ $finance->net_profit }}">
                                </div>
                            </div>



                        </div>
                </div>
            </div>
        </div>


        <div class="mt-3">
            <a href="{{ route('startup.addcust', [$finance->startup_id]) }}" class="btn btn-secondary mr-2"><i
                    class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
        </div>
        </form>

    </div>

@endsection
