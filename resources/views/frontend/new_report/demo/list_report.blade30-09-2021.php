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
                    
                            <div class="postWrap mt-3 search-alpha">
                            <strong>Incubate Report: </strong><br>  <span class="rightcsv" style="float:right">
							<?php //echo '<pre>'; print_r($lastFinancialInformation['month_year']);
							//echo $current_month_info->progress_key_activities;
							//dd($current_month_info);
							if(count($current_month_info) >0){
							//if(count($current_month_info) >0){	
							//
							}else{ ?>
                                <a style="margin-bottom: 4px;" class="btn btn-primary" href="{{ URL::to('incubate_report_add') }}">Add Incubate Report</a></span>  
							<?php } ?>
                        </div>
                    
                </div>
				
				
				
				<div>
                   
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
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-default" id="submitButton">Search</button>
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

<!--Question modal-->
@include('frontend.includes.add_question_modal')

@endsection



@push('page_js')
<script>
$(document).ready(function() {    
 } );
</script>
@endpush

    