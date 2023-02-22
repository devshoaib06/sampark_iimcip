@extends('frontend.layouts.app')
@section('content')
<div class="postCard manage-wrap">
<div class="postWrap">
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
                    
                            <div class="postWrap mt-3 search-alpha1">
                            <div class="row align-items-center">
                                    <div class="col-6">
                                    <strong>Incubate Report: </strong>
                                    </div>
                                    <div class="col-6 text-right">
                                    <?php //echo '<pre>'; print_r($lastFinancialInformation['month_year']);
							//echo $current_month_info->progress_key_activities;
							//dd($current_month_info);
							if(count($current_month_info) >0){
							//if(count($current_month_info) >0){	
							//
							}else{ ?>
                                <a class="btn btn-sm btn-primary" href="{{ URL::to('incubate_report_add') }}">Add Incubate Report</a>
							<?php } ?>
                                    </div>
                                </div>
                            

							
                        </div>
                    
                </div>
				
				
				
				<div style="width:100%; padding: 30px 8px 30px 8px;">
                   
                  <form name="" action="{{ URL::to('incubate_report_list') }}" method="post">
                    {{ csrf_field() }}
                   <div class="row">
				   
                   <div class="form-group col-sm-3">
                    <label>Financial Year</label>
                   <select name="year" class="form-control">
                      <option value="">Select</option>
                      <?php for($i=2018;$i<=date('Y');$i++){
                          $first = date('Y',strtotime($i.'-01-01'));
                          ?>
                          <option <?php if(isset($searchYear) && !empty($searchYear) && ($searchYear==$first)){ echo 'selected'; } ?> value="<?php echo $first; ?>"><?php echo $first; ?></option>
                      <?php } ?>
                   </select>

                 </div>
                   		
                   
                    <div class="form-group col-sm-3">
                        <label>Month</label>
                        <select name="month" class="form-control">
                            <option value="">Select</option>
                            <option <?php if(isset($searchMonth) && !empty($searchMonth) && ($searchMonth=='last3')){ echo 'selected'; } ?> value="last3">Past 3 months</option>
                            <option <?php if(isset($searchMonth) && !empty($searchMonth) && ($searchMonth=='last6')){ echo 'selected'; } ?> value="last6">Past 6 months</option>
                            <?php for($j=1;$j<=12;$j++){ 
                              $dropDate = '2000-'.$j.'-01';
                            ?>
                            <option <?php if(isset($searchMonth) && !empty($searchMonth) && ($searchMonth==date('m',strtotime("+3 month", strtotime($dropDate))))){ echo 'selected'; } ?> value="{{ date('m',strtotime("+3 month", strtotime($dropDate)))}}" >{{ date('F',strtotime("+3 month", strtotime($dropDate)))}}</option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    
                   
                    <div class="form-group col-sm-6">
                    <label>&nbsp;</label>
                        <button type="submit" class="btn btn-default" id="submitButton" style="font-size: 15px; padding: 7px 24px; width: auto; height: auto !important;margin-top:30px;">Search</button>
                    </div>
					
					</div>
					
                  </form> 
              </div>
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				<table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="25%">Company Name</th>
                        <th width="25%">Name</th>
                         <th width="25%">Month</th>
                        <th width="25%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($myReports) && !empty($myReports) && count($myReports)>0){
                    foreach($myReports as $mr){ ?>
                    <tr>
                        <td> {{ $mr->member_company}}</td>
                        <td> <?php echo (isset($mr->contact_name) && !empty($mr->contact_name)) ? $mr->contact_name : '' ?></td>
                        <td> {{ date('M y',strtotime($mr->submit_date))}}</td>
                        
                        <td>
                            <?php if($mr->is_draft==1){ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('incubate_report_edit/'.$mr->id) }}">Edit</a>
                            <?php }if($mr->is_draft==0){ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('incubate_report_download/'.$mr->id) }}">Download</a>
                            <?php }?>

                        </td>
                    </tr>
                    <?php }
                    }else{ ?>
                    <tr> 
                        <td colspan="4" class="warning"> No Records</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            
            
       
   

     

</div>
</div>
</div>
<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')
<script>
$(document).ready(function() {    
 } );
</script>
@endpush

    