@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        My All Startups
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


  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('usrTKact') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      

      <div class="box-tools pull-right">
      @can('admin-active-inactive')
        <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <!--<button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete_user">Delete Users</button>-->
      @endcan
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
               <th>Startup Name</th>
            <th>Contact Name</th>
            <th>Email-id</th>
            <th>Phone</th>
            <th>Location</th>
            
          </tr>
        </thead>
        <tbody>
      @php $sl = 1; @endphp
		 @foreach($dataBag as $data)
		 
		<tr>
		
               
                <td>{{ $data->member_company }}</td>
				<td>{{ $data->contact_name }}</td>
				<td>{{ $data->email_id }}</td>
				<td>{{ $data->contact_no }}</td>
				<td>{{ $data->address }}</td>
               
	
		</tr>
		
		@endforeach
		@php $sl++; @endphp
	
	
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
<script type="text/javascript">
$(function() {
  $('.ar-datatable').DataTable({
    "columnDefs": [ {
      "targets": [ 5,6 ],
      "orderable": false
    } ]
  });
});
$( function() {
  $("#ckAll").on('click',function(){
    var isCK = $(this).is(':checked');
    if(isCK == true){
      $('.ckbs').prop('checked', true);
      $('#delAll').removeAttr('disabled');
    }
    if(isCK == false){
      $('.ckbs').prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    colMark();
    $('#delAll').val('Delete Selected');
  });
  $(".ckbs").on('click', function(){
    var c = 0;
    $(".ckbs").each(function(){
      colMark();
      if($(this).is(':checked')){
        c++;
      }
    });
    if(c == 0){
      $("#ckAll").prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    if(c > 0){
      $("#ckAll").prop('checked',true);
      $('#delAll').removeAttr('disabled');
    }
    $('#delAll').val('Delete Selected ('+c+')');
  });
} );
function colMark() {
  $( '.ckbs' ).each(function() {
    if($(this).is(':checked')) {
      $(this).parents('tr').css('background-color', '#ffe6e6');
    } else {
      $(this).parents('tr').removeAttr('style');
    }
  });
}
</script>
@endpush