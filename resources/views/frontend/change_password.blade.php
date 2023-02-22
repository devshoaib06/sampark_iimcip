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
    
    <div class="col-sm-12">
        <div class="postCard">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Change your password</h3>
                    <form name="frm_cngpwd" id="frm_cngpwd" action="{{ route('front.user.updpwd') }}" method="post">
                    {{ csrf_field() }}
                        <div class="row mt-5">
                            <div class="col-md-6 mt-3">
                                <div class="form-group">
                                    <input type="password" name="password" id="password" class="form-control" maxlength="20" placeholder="New Password" required="required"> 
                                </div>
                                <div class="form-group mt-3">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required="required"> 
                                </div>
                                <div class="form-group mt-3">
                                    <input type="submit" class="btn btn-primary" value="Change Password"> 
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>


<!--Question modal-->
@include('frontend.includes.add_question_modal')

@push('page_js')
<script>
$('#frm_cngpwd').validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    rules: {
        password : {
            required: true,
            minlength: 6
        },
        confirm_password : {
            required: true,
            equalTo: '#password'
        }
    },
    messages: {
        password : {
            required: 'Please enter password'
        },
        confirm_password : {
            required: 'Please confirm your password',
            equalTo: 'Confirm password not match'
        }
    }
});
</script>
@endpush
@endsection

    