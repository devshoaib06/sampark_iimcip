@include('frontend.includes.header')
<main>
    <div class="loginWrap">
        <div class="loginLeft">
            <img src="{{ asset('public/front_end/images/logo.png') }}" class="img-fluid" />
        </div>
         
        <div class="loginRight">
            @if(Session::has('msg'))
                <div class="alert alert-danger">{{ Session::get('msg') }}</div>
            @endif
            <h1>Forgot Password</h1>
            <p>Please Enter your Email to Reset Password.</p>
            
            <div class="loginForm">
            <form id="signupForm" name="signupFrm" action="{{ route('front.reser.password') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                    
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" class="form-control" name="email_id" id="email_id" required="required"  value="@if(isset($email_id)){{$email_id}}@endif"/>
                        </div> 
 
                        <div class="btn-wrap">
                             
                            <button type="submit" class="finishBtn">Submit</button>
                        </div>
                    <div class="form-group">
                        <p> <a href="{{route('signinup')}}" class="loginLink">Back To Login</a></p>
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

// function checkCode(){
//     email_id
//     code
// }

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
                    email: true,
                    
                },
                
                contact_email:{
                    required: true,
                    email: true,
                    notEqualTo:'#email_id' 
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
                    required: 'Please enter your startup name',
                },
                code: {
                    required: 'Please enter code',
                },
                contact_name: {
                    required: 'Please enter contact name',
                },
                "industry_category_id[]": {
                    required: 'Please select industry verticals',
                },
                stage_id: {
                    required: 'Please select startup stage',
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
                contact_email:{
                    required: 'Please enter Contact email-id',
                    email: 'Please enter valid email-id',
                    notEqualTo:'should be different from company email!!',
                    
                },
                contact_no:{
                    required: 'Please enter company contact number'
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


    