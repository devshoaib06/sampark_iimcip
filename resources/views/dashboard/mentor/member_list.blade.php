@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Startups
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
      <a href="{{ route('crte_user') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create Startups</a>
    @endcan
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
            <th style="width: 80px;">Action</th>
            <th>Startup Name</th>
            <th>Primary Contact</th>
            <th>Email-id</th>
            <th>Phone</th>
            <th>Service Location</th> 
          </tr>
        </thead>
        <tbody>
        @if(isset($userList))
          @php $sl = 1; @endphp
          @forelse($userList as $user)
            @if(Auth::user()->user_type == 1)
              <tr>
                <td>
                  
                <?php $mentor_id = Request::segment(5) ;?>
                      <a href="{{ route('del_mentor', array('uid' => $user->id,'mentor_id' => $mentor_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delink Startup " onclick="return confirm('Are You Sure You Want To Delink This Startup ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

                  
                </td>
                <td>{{ ucfirst($user->member_company) }}</td>
                <td>{{ ucfirst($user->contact_name) }}</td>
                <td>{{ $user->email_id }}</td>
                <td>{{ $user->contact_no }}, {{ $user->mobile }}</td>
                <td>{{ $user->city }}, {{ $user->country }}</td>
              </tr>
              @php $sl++; @endphp
            @else
             
                 <tr>
                <td>
                  
                <?php $mentor_id = Request::segment(5) ;?>
                      <a href="{{ route('del_mentor', array('uid' => $user->id,'mentor_id' => $mentor_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delink Startup " onclick="return confirm('Are You Sure You Want To Delink This Startup ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

                  
                </td>
                <td>{{ ucfirst($user->member_company) }}</td>
                <td>{{ ucfirst($user->contact_name) }}</td>
                <td>{{ $user->email_id }}</td>
                <td>{{ $user->contact_no }}, {{ $user->mobile }}</td>
                <td>{{ $user->city }}, {{ $user->country }}</td>
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
      "targets": [ 3,4 ],
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