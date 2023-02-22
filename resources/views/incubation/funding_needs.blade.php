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

    <div class="col-lg-12 fin-report">
        <h3>Funding Needs</h3>
        <table class="table table-bordered ">
            <thead>

                <tr>
                    <th>SI#</th>
                    <th>Funding Requirement(Rs.)</th>
                    <th id="{{$userType == 6 ? 'unselectableTh' : ''}}">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($funds as $key => $list)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $list->funding_requirement }}</td>
                        @if($userType == 4)
                        <td>
                            <a class="btn btn-primary" href="{{ route('startup.needs.edit', $list->id) }}"><i
                                    class="fa fa-edit"></i></a>

                            <a class="btn btn-danger" href="javascript:void(0);" onclick="goDelete({{ $list->id }})"
                                title="Delete"> <i class="fa fa-trash"></i></a>
                        </td>
                        @endif
                @endforeach
                </tr>
            </tbody>
        </table>
    </div>

    </div>
    </div>

    @if($userType == 4)

    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Add Funding requirements</h3>
                    <form name="frm_pfupd" id="frm_pfupd" action="{{ route('startup.addfundingact', [$startUpId]) }}"
                        method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="funding_requirement"
                                        placeholder="Fund Requirement(Rs.)" />
                                </div>
                            </div>


                        </div>

                </div>
            </div>

        </div>
        <div class="mt-3">
            {{-- <a href="{{ route('front.user.mngprof') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a> --}}
            <input type="submit" class="btn btn-primary" value="Add Funding Requirement">
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
                    window.location.href = "{{ url('fundingneeds/destroy') }}/" + id;
                }
            }
        </script>
    @endpush
@endsection
