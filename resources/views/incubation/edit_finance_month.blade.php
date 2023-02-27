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
            <h4 class="pb-3">Edit Financial Month <span class="badge bg-info"></span></h4>
        </div>
        <div class="float-end">
            <a href="{{ route('startup.addfinmon', [$finmonth->startup_id]) }}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Financial Month Report
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
                    <form action="{{ route('startup.finmonth.update', $finmonth->id) }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <input type="hidden" name="startup_id" value="{{ $finmonth->startup_id }}">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="month" class="form-label">Month</label>
                                    {{-- <input type="text" class="form-control" id="month" name="month"
                                        value="{{ $finmonth->month }}"> --}}
                                        <select name="month" required class="form-control">
                                            @if (isset($finMonth) && count($finMonth) > 0)
                                                @foreach ($finMonth as $finM)
                                                    <option value="{{ $finM->id }}" {{$finM->id == $finmonth->month ? 'selected': '' }}>{{ $finM->display_month }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="financial_year" class="form-label">Financial Year</label>
                                    {{-- <input type="text" class="form-control" id="financial_year" name="financial_year"
                                        value="{{ $finmonth->financial_year }}"> --}}
                                        <select name="financial_year" required class="form-control">
                                            @if (isset($finYear) && count($finYear) > 0)
                                                @foreach ($finYear as $finY)
                                                    <option value="{{ $finY->id }}" {{$finY->id == $finmonth->financial_year ? 'selected': '' }}>{{ $finY->display_year }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="revenue" class="form-label">Product</label>
                                    <input type="text" class="form-control" id="product_id" name="product_id"
                                        value="{{ $finmonth->product_id }}">
                                    {{-- <select name="product_id[]" class="form-control indusCatIds" multiple="multiple">
                                        <option value="">Select Product</option>
                                        @if (isset($allProducts) && count($allProducts))
                                            @foreach ($allProducts as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ $c->id == $finmonth->product_id ? 'selected' : '' }}>
                                                    {{ $c->caption }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select> --}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="volume" class="form-label">volume</label>
                                    <input type="text" class="form-control" id="volume" name="volume"
                                        value="{{ $finmonth->volume }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="credit_sale" class="form-label">Credit Sale</label>
                                    <input type="text" class="form-control" id="credit_sale" name="credit_sale"
                                        value="{{ $finmonth->credit_sale }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="cash_sale" class="form-label">Cash Sale</label>
                                    <input type="text" class="form-control" id="cash_sale" name="cash_sale"
                                        value="{{ $finmonth->cash_sale }}">
                                </div>
                            </div>



                        </div>
                </div>
            </div>
        </div>


        <div class="mt-3">
            <a href="{{ route('startup.addfinmon', [$finmonth->startup_id]) }}" class="btn btn-secondary mr-2"><i
                    class="fa fa-arrow-left"></i> Cancel</a>

            <button type="submit" class="btn btn-success">
                <i class="fa fa-check"></i>
                Save
            </button>
        </div>
        </form>

    </div>

@endsection
