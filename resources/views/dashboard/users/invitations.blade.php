@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        Recent Invitations
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
  
      <a href="{{ route('create_invitations') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Invitations</a>
  
    </div>
    <div class="col-md-6">
    </div>
  </div>

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
            <th style="width: 170px;">Action</th>
            <th>Invitation ID</th>
            <th>Startup Name</th>
            <th>Startup Email-id</th>
            <th>Code</th>
            <th>Status</th>
            <th>Date</th>  
          </tr>
        </thead>
        <tbody>
        @if(isset($userList))
          @php $sl = 1; @endphp
          @forelse($userList as $user)
            @if(Auth::user()->user_type == 1)
              <tr>
                <td> 
                    <a href="{{ route('edit_invitation', array('utid' => $user->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Edit Invitations"><i class="fa fa-2x fa-pencil-square-o base-green"></i></a>
                  
                    <a href="{{ route('del_invitation', array('utid' => $user->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delete Invitations" onclick="return confirm('Are You Sure You Want To Delete This Invitations ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
   @if($user->status!=1)
                    <a href="{{ route('resend_invitation', array('utid' => $user->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Resend Invitations"><i class="fa fa-2x  fa-envelope base-red"></i></a>
                    
                    @endif
   
                </td>
                <td>INV0{{ ucfirst($user->id) }}</td>
                <td>{{ ucfirst($user->startup_name) }}</td>
                <td>{{ $user->startup_email }}</td>
                <td>{{ $user->code }} </td>
                <td><?php echo $user->status==1?'<p style="color:green;">Joined</p>':'<p style="color:red;">Invitation Sent</p>' ;?> </td>
                <td>{{ date('d-m-Y',strtotime($user->created_at)) }} </td> 
              </tr>
              @php $sl++; @endphp
               
            @endif
          @empty
          @endforelse
        @endif
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