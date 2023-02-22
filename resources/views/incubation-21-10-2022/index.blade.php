@include('frontend.includes.header')
<main>
    <div class="loginWrap">
        <div class="loginLeft">
            <img src="{{ asset('public/front_end/images/logo.png') }}" class="img-fluid" />
        </div>
       {{-- <div class="signupRight">
            <h1>Signup</h1>
            <p>Please fill the details below and create an account.</p>
            <div class="signupForm">
                <form id="signupForm" name="signupFrm" action="{{ route('front.user.signup') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="stepform" id="signup_step1">
                        <div class="form-group">
                            <label>Startup Name:</label>
                            <input type="text" class="form-control" name="member_company" required="required" />
                        </div>

                        <div class="form-group">
                                <label>Code:</label>
                                <input type="text" class="form-control" name="code" id="code" required="required" />
                            </div>
                       
                        <div class="form-group">
                            <label>Industry Verticals:</label>
                            <div>
                                <select class="form-control indusCatIds" name="industry_category_id[]" id="indusCatIds" multiple="multiple" required="required" style="width: 100%;">
                                    <option value="">Select Industry Verticals</option>
                                    @if(isset($industry_category) && count($industry_category))
                                        @foreach($industry_category as $v)
                                            <option value="{{ $v->id }}">{{ $v->industry_category }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button type="button" class="nextBtn">Next</button>
                        </div>
                    </div>

                    <div class="stepform" id="signup_step2">
                        <div class="form-group">
                            <label>Startup Stage:</label>
                            <select class="form-control" name="stage_id" required="required">
                                <option value="">Select Startup Stage</option>
                                @if(isset($stage) && count($stage))
                                    @foreach($stage as $v)
                                        <option value="{{ $v->id }}">{{ $v->stage_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="row">
                        <div class="form-group col-md-6">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email_id" required="required" />
                            </div>
                            <div class="form-group col-md-6">
                            <label>Contact Name:</label>
                            <input type="text" class="form-control" name="contact_name" required="required" />
                        </div>
                        

                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Password:</label>
                                <input type="password" class="form-control" name="password" id="password" required="required" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Confirm Password:</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" required="required" />
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button type="button" class="prevBtn">Previous</button>
                            <button type="button" class="nextBtn">Next</button>
                        </div>
                    </div>

                    <div class="stepform" id="signup_step3">
                        <div class="form-group">
                            <label>Mobile:</label>
                            <input type="text" class="form-control onlyNumber" name="contact_no" required="required" />
                        </div>
                        <div class="form-group">
                            <label>Country:</label>
                            <input type="text" class="form-control" name="country" required="required" />
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12">
                            <label>City:</label>
                            <input type="text" class="form-control" name="city" required="required" />
                            </div>
                           
                        </div>

                        <div class="btn-wrap">
                            <button type="button" class="prevBtn">Previous</button>
                            <button type="button" class="nextBtn">Next</button>
                        </div>
                    </div>

                    <div class="stepform" id="signup_step4">
                        <div class="form-group">
                            <label>Website:</label>
                            <input type="text" class="form-control" name="website" />
                        </div>
                        <div class="form-group">
                            <label>Legal Status:</label>
                            <select class="form-control" name="legal_status">
                                <option>Status 1</option>
                                <option>Status 2</option>
                                <option>Status 3</option>
                                <option>Status 3</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Image:</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                        </div>
                        <div class="btn-wrap">
                            <button type="button" class="prevBtn">Previous</button>
                            <button type="submit" class="finishBtn">Finish</button>
                        </div>
                    </div>
                    <div class="step-count">
                        <span class="step active" id="signup_step1_indicator"></span>
                        <span class="step" id="signup_step2_indicator"></span>
                        <span class="step" id="signup_step3_indicator"></span>
                        <span class="step" id="signup_step4_indicator"></span>
                    </div>
                    <div class="form-group">
                        <p>Already have an account? <a href="#" class="loginLink">Login</a></p>
                    </div>
                </form>
            </div>
        </div>--}}

        <div class="loginRight">
            @if(Session::has('msg'))
                <div class="alert alert-danger">{{ Session::get('msg') }}</div>
            @endif
            <h1>Incubatee Login</h1>
            <p>Welcome back, please login to your account.</p>
            <div class="loginForm">
                <form id="loginForm" name="loginFrm" action="{{ route('incubate.user.login') }}" method="POST">
                {{ csrf_field() }}
                    <div class="form-group">
                        <label>Email-id:</label>
                        <input type="email" name="email_id" class="form-control" required="required" value="@if(isset($_COOKIE) && !empty($_COOKIE['iimcip_email'])){{ $_COOKIE['iimcip_email'] }}@endif" />
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <input type="password" name="password" class="form-control" required="required" value="@if(isset($_COOKIE) && !empty($_COOKIE['iimcip_password'])){{ $_COOKIE['iimcip_password'] }}@endif" />
                    </div>
                    <div class="loginbtm">
                        <div class="remember">
                            <label class="checkboxCont">
                                <input type="checkbox" name="rm_me" class="form-control" value="1"
                                @if(isset($_COOKIE) && !empty($_COOKIE['iimcip_email']) && !empty($_COOKIE['iimcip_password'])) checked="checked" @endif> Remember Password
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <!-- <div class="forget">
                            <a href="#">Forget Password?</a>
                        </div> -->
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-default" value="Login" />
                    </div>
                    <div class="form-group">
                        <p>Don't have an account? <a href="{{route('email.signinup')}}" >Sign Up</a></p>
                        <p> <a href="{{route('front.user.password')}}" >Forgot Password?</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@push('page_js')
<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/additional-methods.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('.signupLink').focus(function() {
        $('.signupRight').css({
            'display': 'block'
        })
        $('.loginRight').css({
            'display': 'none'
        })
    });
    $('.loginLink').focus(function() {
        $('.loginRight').css({
            'display': 'block'
        })
        $('.signupRight').css({
            'display': 'none'
        })
    });
});

$('#loginForm').validate({
    errorElement: 'span',
    errorClass : 'roy-vali-error',
    rules: {
        email_id: {
            required: true,
            email: true
        },
        password: {
            required: true
        }
    },
    messages: {
        email_id: {
            required: 'Please enter registered email-id',
            email: 'Please enter valid email-id'
        },
        password: {
            required: 'Please enter password',
        }
    }
});


$(document).ready(function(){

    $(".nextBtn").click(function() {
        var form = $("#signupForm");
        form.validate({
            errorElement: 'span',
            errorClass: 'roy-vali-error',
            rules: {
                member_company: {
                    required: true
                },
                contact_name: {
                    required: true
                },
                "industry_category_id[]": {
                    required: true
                },
                stage_id: {
                    required: true
                },
                password : {
                    required: true,
                },
                confirm_password : {
                    required: true,
                    equalTo: '#password'
                },
                email_id:{
                    required: true,
                    email: true
                },
                contact_no:{
                    required: true
                },
                country:{
                    required: true
                },
                city:{
                    required: true
                }
            },
            messages: {
                member_company: {
                    required: 'Please enter your startup name'
                },
                contact_name: {
                    required: 'Please enter contact name'
                },
                "industry_category_id[]": {
                    required: 'Please select industry verticals'
                },
                stage_id: {
                    required: 'Please select startup stage'
                },
                password : {
                    required: 'Please enter password',
                },
                confirm_password : {
                    required: 'Please enter confirm password',
                    equalTo: 'Confirm password not match'
                },
                email_id:{
                    required: 'Please enter email-id',
                    email: 'Please enter valid email-id'
                },
                contact_no:{
                    required: 'Please enter contact number'
                },
                country:{
                    required: 'Please enter country'
                },
                city:{
                    required: 'Please enter city'
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('indusCatIds')) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element); 
                }
            }
        });
        if (form.valid() === true) {
            if ($('#signup_step1').is(":visible")) {
                current_fs = $('#signup_step1');
                next_fs = $('#signup_step2');
                indicator = "signup_step2";
            } else if ($('#signup_step2').is(":visible")) {
                current_fs = $('#signup_step2');
                next_fs = $('#signup_step3');
                indicator = "signup_step3";
            } else if ($('#signup_step3').is(":visible")) {
                current_fs = $('#signup_step3');
                next_fs = $('#signup_step4');
                indicator = "signup_step4";
            }
            next_fs.show();
            current_fs.hide();
            $('#' + indicator + '_indicator').addClass('active').siblings().removeClass('active');
        }
    });

    $('.prevBtn').click(function() {
        if ($('#signup_step4').is(":visible")) {
            current_fs = $('#signup_step4');
            next_fs = $('#signup_step3');
            indicator = "signup_step3";
        } else if ($('#signup_step3').is(":visible")) {
            current_fs = $('#signup_step3');
            next_fs = $('#signup_step2');
            indicator = "signup_step2";
        } else if ($('#signup_step2').is(":visible")) {
            current_fs = $('#signup_step2');
            next_fs = $('#signup_step1');
            indicator = "signup_step1";
        } 
        next_fs.show();
        current_fs.hide();
        $('#' + indicator + '_indicator').addClass('active').siblings().removeClass('active');
    });

});
</script>
@endpush
@include('frontend.includes.footer')


    