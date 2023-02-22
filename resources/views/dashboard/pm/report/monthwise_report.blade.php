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
	  
		
		
		
	   
		

      <div class="box-tools pull-right">
      
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
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
                            <?php if($mr->is_draft==0){ ?>
                            <?php /*?><a class="btn btn-small btn-info" href="{{ URL::to('admin/edit_report/'.$financialInformationDetails->id) }}">Edit</a><?php */ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/dashboard/download_report/'.$mr->id) }}">Download</a>
                            <?php }else{ ?>
                            <a class="btn btn-small btn-info" href="{{ URL::to('admin/dashboard/edit_report/'.$mr->id) }}">Edit</a>
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