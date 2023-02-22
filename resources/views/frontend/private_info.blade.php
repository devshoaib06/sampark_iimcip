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
   @php
   $company_id =company_id(Auth::user()->id);
   $user =DB::table('users')->where('id', '=', $company_id)->first();
   //dd($user);
   @endphp
   <div class="col-sm-12">
      <div class="postCard manage-wrap">
         <div class="postWrap">
            <div class="pwdbox">
               <h3>Investment Details</h3>
               <form name="frm_pfupd" id="frm_pfupd" action="{{ route('front.user.updprof') }}" method="post" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <div class="row">
                     {{--Souvik change--}}
                     <div class="col-md-12">
                        <div id="invest_sec" >
                           @if (isset($company_investments) && count($company_investments)>0)
                           @php
                           $c=count($company_investments);
                           $i=1;
                           @endphp
                           <div class="founder-header">
                              <label>Investment Info </label>
                           </div>
                           @foreach ($company_investments as $vv)   
                           <div id="invest_sec_{{$i}}">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="inv-info-box">
                                       <div class="holder">
                                          {{-- 
                                          <div class="field-box">
                                             <label>Source :</label>
                                             <input type="text" name="company_source[]" class="form-control" id="company_source_{{$i}}" placeholder="Source" value="@if(!empty($vv->source)){{$vv->source}}@endif">
                                          </div>
                                          --}}
                                          <div class="field-box">
                                             <label> Source : </label>
                                             <select name="company_source[]" required class="form-control" id="company_source_{{$i}}" >
                                             <option value="1" {{old('company_source',$vv->source??'')==1? 'selected':''}} >IIMCIP</option>
                                             <option value="2" {{old('company_source',$vv->source??'')==2 ? 'selected':''}}>VC</option>
                                             <option value="3" {{old('company_source',$vv->source??'')==3? 'selected':''}} >Angel</option>
                                             <option value="4" {{old('company_source',$vv->source??'')==4 ? 'selected':''}}>Govt & Others</option>
                                             </select>
                                          </div>
                                          <div class="field-box">
                                             <label>Instrument :</label>
                                             <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_{{$i}}" placeholder="Instrument" value="@if(!empty($vv->instrument)){{$vv->instrument}}@endif">
                                          </div>
                                          <div class="field-box">
                                             <label>Value :</label>
                                             <input type="text" name="company_value[]" class="form-control" id="company_value_{{$i}}" placeholder="Value" value="@if(!empty($vv->value)){{$vv->value}}@endif">
                                          </div>
                                          <div class="field-box">
                                             <label>Funders Name :</label>
                                             <input type="text" name="funders_name[]" class="form-control" id="funders_name_{{$i}}" placeholder="Funders Name" value="@if(!empty($vv->funders_name)){{$vv->funders_name}}@endif">
                                          </div>
                                          <div class="field-box">
                                             <label>Year :</label>
                                             <input type="text" name="year[]" class="form-control" id="year_{{$i}}" placeholder="Year" value="@if(!empty($vv->year)){{$vv->year}}@endif">
                                          </div>
                                          <div class="field-box">
                                             <label>Other Details :</label>
                                             <input type="text" name="other_details[]" class="form-control" id="other_details_{{$i}}" placeholder="Other Details" value="@if(!empty($vv->other_details)){{$vv->other_details}}@endif">
                                          </div>
                                       </div>
                                       @if ($i != 1) 
                                       <a  id="rm-answer-{{$i}}" class="btn btn-danger btn-remove" onClick="remove_invest({{$i}})">Remove</a>
                                       @else
                                       <a id="ad-contact_person-{{$i}}" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                                       <input type="hidden" name="answer_row_count" id="answer_row_count" value="{{$c}}" />  
                                       @endif
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @php
                           ++$i;
                           @endphp
                           @endforeach
                           @else 
                           <div id="invest_sec_1">
                              <div class="row">
                                 <div class="col-lg-12">
                                    <label for="is_raised_invest">Raised Investments:</label>
                                    <input type="radio" name="is_raised_invest" onclick="change_hidden_answer_type_value(1)" id="is_raised_invest" value="1" 
                                    @if(isset($user->is_raised_invest))
                                    @if($user->is_raised_invest == 1) 
                                    checked
                                    @endif                      
                                    @endif>Yes
                                    <input type="radio" name="is_raised_invest" id="is_raised_invest" onclick="change_hidden_answer_type_value(0)"  value="0" 
                                    @if(isset($user->is_raised_invest))
                                    @if($user->is_raised_invest == 0) 
                                    checked
                                    @endif                      
                                    @endif>No
                                    <input type="hidden" name="raise_check" id="raise_check" value="{{$user->is_raised_invest}}">
                                    <div class="form-group" id="show_raise" style="display: none">

                                        <div class="inv-info-box">
                                            <div class="holder">
                                                <div class="field-box">
                                                    <label> Source : </label>
                                                    <select name="company_source[]" required class="form-control"
                                                        id="company_source_1">
                                                        <option value="1">IIMCIP</option>
                                                        <option value="2"> VC</option>
                                                        <option value="3">Angel</option>
                                                        <option value="4">Govt & Others</option>
                                                    </select>
                                                </div>

                                            <div class="field-box">
                                                <label> Instrument : </label>
                                                <input type="text" name="company_instrument[]"
                                                    class="form-control" id="company_instrument_1"
                                                    placeholder="Instrument">
                                            </div>

                                            <div class="field-box">
                                                <label> Value : </label>
                                                <input type="text" name="company_value[]"
                                                    class="form-control" id="company_value_1"
                                                    placeholder="Value">
                                            </div>

                                            <div class="field-box">
                                                <label>Funders Name :</label>
                                                <input type="text" name="funders_name[]"
                                                    class="form-control" id="funders_name_1"
                                                    placeholder="Funders Name">
                                            </div>

                                            <div class="field-box">
                                                <label>Year :</label>
                                                <input type="text" name="year[]" class="form-control"
                                                    id="year_1" placeholder="Year">
                                            </div>
                                            <div class="field-box">
                                                <label>Other Details :</label>
                                                <input type="text" name="other_details[]"
                                                    class="form-control" id="other_details_1"
                                                    placeholder="Other Details">
                                            </div>
                                            </div>
                                        </div>

                                       {{--<textarea class="form-control" name="invest_name" placeholder="Investment Info..">{{ $user->invest_name }}</textarea>--}}
                                       {{-- <label> Source : </label>
                                       <input type="text" name="company_source[]" class="form-control" id="company_source_1" placeholder="Source">
                                       <label> Instrument : </label>
                                       <input type="text" name="company_instrument[]" class="form-control" id="company_instrument_1" placeholder="Instrument">
                                       <label> Value : </label>
                                       <input type="text" name="company_value[]" class="form-control" id="company_value_1" placeholder="Value">
                                       <a id="ad-contact_person-3" class="btn btn-success btn-add" onClick="clone_invest()">Add More</a>
                                       <input type="hidden" name="answer_row_count" id="answer_row_count" value="1" />         --}}
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif 
                        </div>
                     </div>
                  </div>
                  <div class="mt-3">
                     {{-- <a href="{{ route('front.user.mngprof') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a> --}}
                     <input type="submit" class="btn btn-primary" value="Save Changes">
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
   var editorPostInfo = CKEDITOR.replace( 'aboutMe', {
     customConfig: "{{ asset('public/assets/ckeditor/mini_config.js') }}",
   } );
   $('#frm_pfupd').validate({
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
               required: 'Please enter your company name'
           },
           contact_name: {
               required: 'Please enter contact name'
           },
           "industry_category_id[]": {
               required: 'Please select industies'
           },
           stage_id: {
               required: 'Please select company stage'
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
</script>
<script type="text/javascript">
   function readURL(input) {
       let img_file = document.getElementById('inputGroupFile01').files[0];
       if (img_file) {
           var reader = new FileReader();
           reader.onload = function(e) {
               $('.browse-img').attr('src', e.target.result);
           }
           
           reader.readAsDataURL(input.files[0]); 
           }
       }
   
       $("#inputGroupFile01").change(function() {
           readURL(this);
       });
   
   
       $(document).ready(function(){
           $('.file-pitch').change(function(e){
               var pitch_file_name = e.target.files[0].name;
               $('.file_pitch_label').text(pitch_file_name)
           });
           $('#inputGroupFile01').change(function(e){
               var img_file_name = e.target.files[0].name;
               $('.com_logo').text(img_file_name)
           });
       });
       
</script>
<script type="text/javascript">
   $(document).ready(function() {
       $(".fancybox").fancybox();
       $i=1;
       $('.add-founder').click(function(){
           $('.founder').clone().appendTo('.founder-list').removeAttr('class','founder').addClass('founders'+$i).append('<div class="form-group"><a class="btn remove-founder btn-danger btn-remove" onclick="removeItem('+$i+')">Remove</a></div>');
   
           $i++;
       });
   
       //alert($("#is_raised_invest").val());
   
       if($("#raise_check").val()==1)
       {
           $("#show_raise").show();
       }
       else
       {
           $("#show_raise").hide();
       }
   });
   
   function removeItem($j){
          //alert($j);
          $(".founders"+$j).remove();
       }
       function clone_answer() {  
   
             var id = $("#answer_row_count").val();
             var new_id = parseInt(id) + 1;
             $("#answer_row_count").val(new_id);
             var table_row = "";
   
             table_row += '<div id="answer_sec_' + new_id + '">';
             table_row += '<div class="row">';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Name : </label>';
             table_row += '<input type="text" name="founder_name[]" id="founder_name_' + new_id + '" class="form-control" value="" /> ';                                 
             table_row += '</div>';
             table_row += '</div>';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Brief Profile :</label>';
             table_row += '<textarea class="form-control" name="founder_profile[]" id="founder_profile_' + new_id + '"></textarea>';
   
             table_row += '</div>';
             table_row += '</div>';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>LinkedIn profile :</label>';
             table_row += '<input type="text" name="founder_linc_profile[]" class="form-control" id="founder_linc_profile_' + new_id + '"  >';
   
             table_row += '</div>';
             table_row += '</div>';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Image :</label>';
             table_row += ' <div class="custom-file"><input type="file" name="founder_img[]" class="custom-file-input" id="founder_img' + new_id + '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';
   
   
   
             table_row += '</div>';
             table_row += '</div>';
             table_row += '<div class="col-md-12">';             
             table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_answer(\'' + new_id + '\')">';
             table_row += 'Remove';      
             table_row += '</div>';
             table_row += '</div>';
             table_row += '</div>';    
   
   
             $("#answer_sec").append(table_row);
   
   }
   function remove_answer(id) {
   
     
       $("#answer_sec_" + id).remove();
     
   
   }
   function clone_service() {  
   
             var id = $("#answer_row_count").val();
             var new_id = parseInt(id) + 1;
             $("#answer_row_count").val(new_id);
             var table_row = "";
   
             table_row += '<div id="service_sec_' + new_id + '">';
             table_row += '<div class="row">';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Image :</label>';
             table_row += ' <div class="custom-file"><input type="file" name="buisness_img[]" class="custom-file-input" id="buisness_img_' + new_id + '" aria-describedby="inputGroupFileAddon02"><label class="custom-file-label" for="inputGroupFile02">Choose file</label></div>';
   
   
   
             table_row += '</div>';
             table_row += '</div>';
             table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Caption : </label>';
             table_row += '<input type="text" name="buisness_caption[]" id="buisness_caption_' + new_id + '" class="form-control" value="" /> ';                                 
             table_row += '</div>';
             table_row += '</div>';
              table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Video : </label>';
             table_row += '<input type="text" name="buisness_video[]" id="buisness_video_' + new_id + '" class="form-control"  placeholder="Enter YouTube or Vimeo Video Link"/> ';                                 
             table_row += '</div>';
             table_row += '</div>';
             /*table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Website :</label>';
             table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';
   
   
   
             table_row += '</div>';
             table_row += '</div>';*/
             
             table_row += '<div class="col-md-12">';             
             table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_service(\'' + new_id + '\')">';
             table_row += 'Remove';      
             table_row += '</div>';
             table_row += '</div>';
             table_row += '</div>';    
   
   
             $("#service_sec").append(table_row);
   
   }
   
   function remove_service(id) {
   
     
       $("#service_sec_" + id).remove();
     
   
   }
   
   function clone_video() {  
   
       
   
             var id = $("#answer_row_count").val();
             var new_id = parseInt(id) + 1;
             $("#answer_row_count").val(new_id);
             var table_row = "";
   
             table_row += '<div id="video_sec_' + new_id + '">';
             table_row += '<div class="row">';
              table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Link : </label>';
             table_row += '<input type="text" name="company_video[]" id="company_video_' + new_id + '" class="form-control"  placeholder="Enter YouTube or Vimeo Video Link"/> ';                                 
             table_row += '</div>';
             table_row += '</div>';
             /*table_row += '<div class="col-md-12">';
             table_row += '<div class="form-group">';
             table_row += '<label>Website :</label>';
             table_row += '<textarea class="form-control" name="buisness_website[]" id="buisness_website_' + new_id + '"></textarea>';
   
   
   
             table_row += '</div>';
             table_row += '</div>';*/
             
             table_row += '<div class="col-md-12">';             
             table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_video(\'' + new_id + '\')">';
             table_row += 'Remove';      
             table_row += '</div>';
             table_row += '</div>';
             table_row += '</div>';    
   
             $("#video_sec").append(table_row);
   
   }
   
   function remove_video(id) {
   
     
       $("#video_sec_" + id).remove();
     
   
   }
   
   function change_hidden_answer_type_value(id){
   
   if(id == 1){
       $("#show_raise").show();        
   }else{
       $("#show_raise").hide();        
   }
   
   
   
   
   
   }   
   
   function openVideoModal(video_url)
   
   {
        
       $("#cartoonVideo").attr('src', video_url);
           //alert(video_url);
       $("#myModal").modal('show');
   }
   
   
   function clone_invest() {  
   
        var id = $("#answer_row_count").val();
             var new_id = parseInt(id) + 1;
             $("#answer_row_count").val(new_id);
             var table_row = "";
   
             table_row += '<div id="invest_sec_' + new_id + '">';
             table_row += '<div class="add-more-holder">';
             
   				
             table_row += '<div class="form-group">';
             table_row += '<label>Source : </label>';
           //   table_row += '<input type="text" name="company_source[]" id="company_source_' + new_id + '" class="form-control"  placeholder="Source"/> ';    
             
           table_row += '<select name="company_source[]" required class="form-control" id="company_source_' + new_id +'" >';
               
             table_row += '<option value="1">IIMCIP</option>';
             table_row += '<option value="2">VC</option>';
             table_row += '<option value="3">Angel</option>';
             table_row += '<option value="4">Govt & Others</option>';
   
             table_row += '</select>';
             table_row += '</div>';
             
   
    
           
   
             table_row += '<div class="form-group">';
             table_row += '<label>Instrument : </label>';
             table_row += '<input type="text" name="company_instrument[]" id="company_instrument_' + new_id + '" class="form-control"  placeholder="Instrument"/> ';                                 
             table_row += '</div>';
           
   
    
    
   
             table_row += '<div class="form-group">';
             table_row += '<label>Value : </label>';
             table_row += '<input type="text" name="company_value[]" id="company_value_' + new_id + '" class="form-control"  placeholder="Value"/> ';                                 
             table_row += '</div>';
         
   
   
            
             table_row += '<div class="form-group">';
             table_row += '<label>Funders Name : </label>';
             table_row += '<input type="text" name="funders_name[]" id="funders_name_' + new_id + '" class="form-control"  placeholder="Funders Name"/> ';                                 
             table_row += '</div>';
            
   
   
           
             table_row += '<div class="form-group">';
             table_row += '<label>Year: </label>';
             table_row += '<input type="text" name="year[]" id="year_' + new_id + '" class="form-control"  placeholder="Year"/> ';                                 
             table_row += '</div>';
            
  
           
   
          
             table_row += '<div class="form-group">';
             table_row += '<label>Other Details: </label>';
             table_row += '<input type="text" name="other_details[]" id="other_details_' + new_id + '" class="form-control"  placeholder="Other Details"/> ';                                 
             table_row += '</div>';
           

             
                    
             
             table_row += '</div>';
             table_row += '<a id="rm-answer-' + new_id + '"  class="btn btn-danger btn-remove" onClick="remove_invest(\'' + new_id + '\')">';
             table_row += 'Remove';      
             table_row += '</div>';
             table_row += '</div>';
           	  
   
             $("#invest_sec").append(table_row);
   
            
   
   }
   
   function remove_invest(id) {
   
     
       $("#invest_sec_" + id).remove();
     
   
   }
</script>
@endpush
@endsection
