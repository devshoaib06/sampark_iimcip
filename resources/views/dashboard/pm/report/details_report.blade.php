@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
       Incubate Report List
        <!--small>it all starts here</small-->
      </h1>
      <!-- <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('crte_user') }}">Create Startups</a></li>
        <li class="active">Startup List</li>
      </ol> -->
    </section>  
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
    @can('admin-create')
      
    @endcan
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('admin.incubate_report_list') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">      
	  
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
			<button type="submit" class="btn btn-default" id="submitButton" style="margin-top: 24px;">Search</button>
		</div>

      <div class="box-tools pull-right">
      
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            
            <th>Month-Year</th>
            <th>Action</th>

          </tr>
        </thead>
        
        
		<tbody>
                    <?php if(isset($myReports) && !empty($myReports) && count($myReports)>0){
                    foreach($myReports as $mr){ ?>
                    <tr>
                        <td> {{ date('M y',strtotime($mr->submit_date))}}</td>
                        
                        <td>						
							<!--<a class="btn btn-small btn-info" href="{ route('admin.download_incubate_report',date('m',strtotime($mr->submit_date)).'/'.date('Y',strtotime($mr->submit_date))) }}">Download</a>-->						
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/dashboard/download_incubate_report/'.date('m',strtotime($mr->submit_date)).'/'.date('Y',strtotime($mr->submit_date))) }}">Download</a>
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/dashboard/incubate_report_monthwise/'.date('m',strtotime($mr->submit_date)).'/'.date('Y',strtotime($mr->submit_date))) }}">Edit report monthwise</a>
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
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
    </form>
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')

@endpush