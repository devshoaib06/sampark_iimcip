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
        {{-- Listing --}}
 
        <div class="col-lg-12 fin-report">
            <h3>Impact Report</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SI#</th>
                    <th>Month</th>
                    <th>Financial Year</th>
                    <th>Head Count (FT)</th>
                    <th>Head Count (PT)</th>
                    <th>Women employees (FT)</th>
                    <th>Women employees (PT)</th>
                        <th>Action</th>
                      
                    </tr>
                </thead>
                <tbody>
            @foreach($impacts as $key => $list) 
            <tr>
          
                <td>{{$key+1}}</td>
                <td>{{$list->getFinancialMonth['display_month']}}</td>
                <td>{{$list->getFinancialYear['display_year']}}</td>
                <td>{{ $list->head_count_ft }}</td>
                <td>{{ $list->head_count_pt }}</td>
                <td>{{ $list->women_employees_ft }}</td>
                <td>{{ $list->women_employees_pt }}</td>
                <td>
                      <a class="btn btn-primary" href="{{route('impacts.edit',$list->id)}}"><i class="fa fa-edit"></i></a>

                    <a class="btn btn-danger" href="javascript:void(0);" onclick="goDelete({{$list->id}})" title="Delete"> <i class="fa fa-trash"></i></a>
                </td>

            </tr>

            
            @endforeach
         
                </tbody>
            </table>
        </div>     

 {{-- Submition --}}
    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <h3>Add Impacts</h3>
                    <form name="frm_pfupd" id="frm_pfupd" action="{{ route('front.user.addimpactact') }}" method="post" enctype="multipart/form-data">
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

                                    <input type="text" class="form-control" name="head_count_ft"
                                        placeholder="Head Count (FT)" />
                                </div>
                            </div>


                            

                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="head_count_pt"
                                        placeholder="Head Count (PT)" />
                                </div>
                            </div>



                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="women_employees_ft"
                                        placeholder="Women employees (FT)" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" name="women_employees_pt"
                                        placeholder="Women employees (PT)" />
                                </div>
                            </div>
                            </div>
                          
                            
                            </div>
                            
                        </div>
                        </div>
                       
                    </div>
                    <div class="mt-3">
                       
                        <input type="submit" class="btn btn-primary" value="Add Impact">
                    </div>
                </div>
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
                        <iframe id="cartoonVideo" width="560" height="315" src="//www.youtube.com/embed/YE7VzlLtp-4" frameborder="0" allowfullscreen></iframe>
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
        email_id:{
            required: true,
            email: true
        },
        password_confirm : {
                   
            equalTo : "#password"
        }
        
    },
    messages: {
        
        contact_name: {
            required: 'Please enter  name'
        },
        email_id:{
            required: 'Please enter email-id',
            email: 'Please enter valid email-id'
        },
        password:{
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

    $(document).ready(function(){
        $('.img_inp').change(function(e){
            var img_file_name = e.target.files[0].name;
            $('.custom-file-label').text(img_file_name)
        });
    });

    function goDelete(id){
        let isConfirm = confirm('Do you want to delete?');
        if(isConfirm){
            window.location.href="{{url('impacts/destroy')}}/"+id;
        }
    }
            
</script>


@endpush
@endsection

