@extends('incubation.layouts.app')
@section('accountRightMenu')
    @include('incubation.includes.account_right_menu')
@endsection
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
    @php $userType = Auth()->user()->user_type; @endphp
    {{-- Listing --}}

    <div class="col-lg-12 fin-report">
        <h3>Order Pipeline</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SI#</th>
                    <th>Month</th>
                    <th>Financial Year</th>
                    <th>Product</th>
                    <th>Volume</th>
                    <th>Amount(Rs.)</th>
                    <th id="{{$userType == 6 ? 'unselectableTh' : ''}}">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productList as $key => $list)
                    <tr>

                        <td>{{ $key + 1 }}</td>
                        <td>{{ $list->getFinancialMonth['display_month'] }}</td>
                        <td>{{ $list->getFinancialYear['display_year'] }}</td>
                        <td>{{ $list->product_id }}</td>
                        <td>{{ $list->volume }}</td>
                        <td>{{ number_format($list->amount) }}</td>
                        @if($userType == 4)
                        <td>


                            <a class="btn btn-primary" href="{{ route('startup.orderpipe.edit', $list->id) }}"><i
                                    class="fa fa-edit"></i></a>

                            <a class="btn btn-danger" href="javascript:void(0);" onclick="goDelete({{ $list->id }})"
                                title="Delete"> <i class="fa fa-trash"></i></a>
                        </td>
                        @endif

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    {{-- Submition --}}
    @if($userType == 4)
    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Add Orders</h3>
                    <form name="frm_pfupd" id="frm_pfupd" action="{{ route('startup.addorderpipeact', [$startUpId]) }}"
                        method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">

                                    <select name="month" required class="form-control">
                                        @if (isset($finMonth) && count($finMonth) > 0)
                                            @foreach ($finMonth as $finM)
                                                <option value="{{ $finM->id }}">{{ $finM->display_month }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <select name="financial_year" required class="form-control">
                                        @if (isset($finYear) && count($finYear) > 0)
                                            @foreach ($finYear as $finY)
                                                <option value="{{ $finY->id }}">{{ $finY->display_year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="product_id" id="product_id">
                                    {{-- <select name="product_id[]" class="form-control indusCatIds" multiple="multiple">
                                        <option value="" disabled>Select Product</option>
                                        @if (isset($allProducts) && count($allProducts))
                                            @foreach ($allProducts as $c)
                                                <option value="{{ $c->id }}">{{ $c->caption }}</option>
                                            @endforeach
                                        @endif
                                    </select> --}}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="volume" placeholder="Volume" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="amount" placeholder="Amount(Rs.)" />
                                </div>
                            </div>


                        </div>

                </div>
            </div>

        </div>
        <div class="mt-3">

            <input type="submit" class="btn btn-primary" value="Add Order Pipeline">
        </div>
    </div>
    @endif
    <div class="row">

    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
    <!-- Modal -->
    <!-- Modal HTML -->
    <div id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <!-- <h4 class="modal-title">Video</h4> -->
                </div>
                <div class="modal-body">
                    <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4"
                        frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    </div>


    <!--Question modal-->
    @include('frontend.includes.add_question_modal')

    @push('page_js')
        <script>
            $('#frm_pfupd').validate({
                errorElement: 'span',
                errorClass: 'roy-vali-error',
                rules: {

                    contact_name: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    email_id: {
                        required: true,
                        email: true
                    },
                    password_confirm: {

                        equalTo: "#password"
                    }

                },
                messages: {

                    contact_name: {
                        required: 'Please enter  name'
                    },
                    email_id: {
                        required: 'Please enter email-id',
                        email: 'Please enter valid email-id'
                    },
                    password: {
                        required: 'Please enter password'
                    },

                },
                errorPlacement: function(error, element) {
                    if (element.hasClass('indusCatIds')) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            $(document).ready(function() {
                $("#unselectableTh").hide()
            });


            function goDelete(id) {
                let isConfirm = confirm('Do you want to delete?');
                if (isConfirm) {
                    window.location.href = "{{ url('orderpipeline/destroy') }}/" + id;
                }
            }
        </script>
    @endpush
@endsection
