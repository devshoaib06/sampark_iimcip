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
        <h3> Yearly Target</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SI#</th>
                    <th>Financial Year</th>
                    <th>Revenue(Rs.)</th>
                    <th>Volume</th>
                    <th id="{{$userType == 6 ? 'unselectableTh' : ''}}">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($targets as $key => $list)
                    <tr>

                        <td>{{ $key + 1 }}</td>
                        <td>{{ $list->getFinancialYear['display_year'] }}</td>
                        <td>{{ number_format($list->revenue) ?? '0' }}</td>
                        <td>{{ number_format($list->volume) ?? '0' }}</td>
                        @if($userType == 4)
                        <td>
                            <a class="btn btn-primary" href="{{ route('startup.targets.edit', $list->id) }}"><i
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
                    <h3>Add targets (Yearly)</h3>
                    <form name="frm_pfupd" id="frm_pfupd" action="{{ route('startup.addyearlytargetact', [$startUpId]) }}"
                        method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">

                                    <select name="financial_year" required class="form-control">
                                        @if (isset($finYear) && count($finYear) > 0)
                                            @foreach ($finYear as $finY)
                                                <option value="{{old( 'financial_year', $finY->id) }}">{{ $finY->display_year }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="revenue" placeholder="Revenue(Rs.)" value="{{old( 'revenue')}}"/>
                                </div>
                            </div>



                            <div class="col-md-4">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="volume" placeholder="Volume" value="{{old( 'volume')}}" />
                                </div>
                            </div>


                        </div>

                </div>
            </div>

        </div>
        <div class="mt-3">

            <input type="submit" class="btn btn-primary" value="Add Yearly Target">
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
        </script>
        <script type="text/javascript">
            function readURL(input) {
                let img_file = document.getElementById('inputGroupFile01').files[0];
                if (img_file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#img_thumb').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".img_inp").change(function() {
                readURL(this);
            });

            $(document).ready(function() {
                $('.img_inp').change(function(e) {
                    var img_file_name = e.target.files[0].name;
                    $('.custom-file-label').text(img_file_name)
                });
            });

            $(document).ready(function() {
                $("#unselectableTh").hide()
            });

            function goDelete(id) {

                let isConfirm = confirm('Do you want to delete?');
                if (isConfirm) {
                    window.location.href = "{{ url('yearlytargets/destroy') }}/" + id;
                }
            }
        </script>
    @endpush
@endsection
