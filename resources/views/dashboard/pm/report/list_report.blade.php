@extends('layouts.admindashboard')
@section('page_heading','Incubate Report List')

@section('section')
<div class="col-sm-12">
<?php //echo $status;die(); ?>
    <div class="row">
        @if(Session::has('flash_message'))
        <div class="alert alert-success" style="text-align:center;">
            {{ Session::get('flash_message') }}
        </div>
        @endif

        <div class="col-sm-12">
            @section ('cotable_panel_title','Startups')
            @section ('cotable_panel_body')
            
            
            <?php //echo '<pre>'; print_r($lastFinancialInformation['month_year']);
            //if(date('Y-m',strtotime($lastFinancialInformation['month_year'])) >= date('Y-m')){
                //
            //}else{ ?>
                <?php /* ?><span class="rightcsv"><a  href="{{ URL::to('admin/add_report/'.$start_up_id) }}">Add Incubation Report</a></span><?php */ ?>
            <?php //} ?>
            
            
            <div>
                   {{ Form::model(null, array('URL' => URL::to('admin/list_report/'.$start_up_id),'method'=>'post')) }} 
                  
                    
                   
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
                        <button type="submit" class="btn btn-default" id="submitButton" style="margin-top: 24px;">Searchsgsgg</button>
                    </div>
                  {{ Form::close() }} 
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
                         <td> {{ $mr->name}}</td>
                        <td> <?php echo (isset($mr->individual_name) && !empty($mr->individual_name)) ? $mr->individual_name : '' ?></td>
                        <td> {{ date('M y',strtotime($mr->submit_date))}}</td>
                        
                        <td>
                            <?php if($mr->is_draft==0){ ?>
                            <?php /*?><a class="btn btn-small btn-info" href="{{ URL::to('admin/edit_report/'.$financialInformationDetails->id) }}">Edit</a><?php */ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/download_report/'.$mr->id) }}">Download</a>
                            <?php }else{ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/edit_report/'.$mr->id) }}">Edit</a>
                            <?php } ?>

                        </td>
                    </tr>
                   <?php 
                    }
                      }else{ ?>
                    <tr> 
                        <td colspan="4" class="warning"> No Records</td>
                    </tr>
                    <?php } ?>

                </tbody>
            </table>

           <div class="text-center">
                <ul class="pagination">

                    {{ $myReports->appends(Input::except('page'))->links(); }}

                </ul>
            </div> 		
            @endsection
            @include('widgets.panel', array('header'=>true, 'as'=>'cotable'))

            

        </div>
    </div>
</div>
@stop