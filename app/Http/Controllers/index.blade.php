@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
       All Portfolio Manager
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
            <th style="width: 200px;">Action</th>
            <th>Name</th>
            <th>Email-id</th>
            <th>Phone</th>
            <th>Location</th>
            <th>Designation</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($userList))
          @php $sl = 1; @endphp
          @forelse($userList as $user)
            @if(Auth::user()->user_type == 1)
              <tr>

                                <td>


                    <a href="{{ route('member_pm', array('uid' => $user->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Startup List"><i class="fa fa-tag fa-2x"></i></a>

                  <a onclick="javascript:startupAssign(<?php echo $user->id ;?>);" href="javascript:void(0);" data-toggle="tooltip" title="Assign Startup"><i class="fa fa-2x fa fa-plus"></i></a>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <input type="hidden" name="assin_pm_id" id="assin_pm_id">
      <div class="modal-body">
          
       </div>
      <div class="modal-footer">
        <button type="button" onclick="javascript:assignStartup();"  class="btn btn-primary">Assign Startup</button>
      </div>
    </div>
  </div>
</div>
    </div>
  </div>
</div>
<!-- Modal -->
                  
                    <a href="{{ route('edit_pm', array('uid' => $user->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Edit PM"><i class="fa fa-2x fa-pencil-square-o base-green"></i></a>


                  
                    <a href="{{ route('del_usr', array('utid' => $user->timestamp_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delete User" onclick="return confirm('Are You Sure You Want To Delete This User ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

                    @if($user->id != 1 && Auth::user()->id == $user->id)
                    <a href="{{ route('file_all_imgs') }}" data-toggle="tooltip" data-placement="bottom" title="Media files"><i class="fa fa-picture-o"></i></a>
                    @endif

                    @if($user->user_type == '1'){{$user->user_type}}
                    <a href="{{ route('file_all_imgs') }}" data-toggle="tooltip" data-placement="bottom" title="Media files"><i class="fa fa-picture-o"></i></a>
                    @endif
                  
                  @can('admin-password-reset')
                    <a href="{{ route('rst_pwd', array('utid' => $user->timestamp_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Reset Password"><i class="fa fa-2x fa-key"></i></a>
                  @endcan

                  @if($user->status == '1')
                    <a href="{{ route('acInac') }}?id={{ $user->id }}&val=2&tab=users"> 
                      <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                    </a>
                  
                  @else
                    <a href="{{ route('acInac') }}?id={{ $user->id }}&val=1&tab=users"> 
                      <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                    </a> 
                  @endif


                   


                  
                </td>   
               
                <td>{{ ucfirst($user->contact_name) }}</td>
                <td>{{ $user->email_id }}</td>
                <td>{{ $user->contact_no }}</td>
                <td>{{ $user->address }}</td>
                <td>{{ $user->designation }}</td>  
              </tr>
              @php $sl++; @endphp
            @else
              <tr>
                <td>
                  <!--{{ $sl }}-->
                  @if($user->id != 1 && Auth::user()->id != $user->id)
                  <input type="checkbox" name="user_ids[]" class="ckbs" value="{{ $user->id }}">
                  @endif
                </td>
                <td>{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</td>
                <td>{{ $user->email_id }}</td>
                <td>{{ date('m-d-Y', strtotime($user->created_at)) }}</td>
                <td>{{ date('m-d-Y', strtotime($user->updated_at)) }}</td>
                <td>
                @if($user->id != 1 && Auth::user()->id != $user->id)
                  @can('admin-active-inactive')
                    @if($user->status == '1')
                      <a href="{{ route('acInac') }}?id={{ $user->id }}&val=2&tab=users"> 
                        <i class="fa fa-unlock-alt base-green fa-2x" aria-hidden="true"></i> 
                      </a>
                    @endif
                    @if($user->status == '2')
                      <a href="{{ route('acInac') }}?id={{ $user->id }}&val=1&tab=users"> 
                        <i class="fa fa-lock base-red fa-2x" aria-hidden="true"></i>
                      </a> 
                    @endif
                  @endcan
                @endif
                </td>
                <td>
                @if($user->id != 1 && Auth::user()->id != $user->id)
                  @can('admin-edit')
                    <a href="{{ route('edit_user', array('utid' => $user->timestamp_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Edit User"><i class="fa fa-2x fa-pencil-square-o base-green"></i></a>
                  @endcan
                  @can('admin-delete')
                    <a href="{{ route('del_usr', array('utid' => $user->timestamp_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delete User" onclick="return confirm('Are You Sure You Want To Delete This User ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
                  @endcan
                  @can('admin-password-reset')
                    <a href="{{ route('rst_pwd', array('utid' => $user->timestamp_id)) }}" data-toggle="tooltip" data-placement="bottom" title="Reset Password"><i class="fa fa-2x fa-key"></i></a>
                  @endcan
                  


                @endif

                
                </td>
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
  $(document).ready( function () {
        var table = $('#example').DataTable();
} );

function startupAssign(pmID)
  {
    //alert(pmID);
    $.ajax({
             type : "POST",
             url : "{{ route('startup_assign_list') }}",
             data : {
               "pmID" : pmID,
               "_token" : "{{ csrf_token() }}"
             },
             success: function(scatJson) {
              console.log(scatJson);

              $('.modal-body').html(scatJson);
              var table = $('#example').DataTable();
              $('#assin_pm_id').val(pmID);
              $('#myModal').modal('show'); 
              
             }
      });
  }

  function assignStartup()
  {
    var pmID = $('#assin_pm_id').val();
    //alert(pmID);
    var startupIDs = new Array();
    $('input[name="startup_ids"]:checked').each(function() {
        startupIDs.push(this.value);
    });
    //alert(startupIDs);
    var startupIDs = startupIDs.toString();
     $.ajax({
            type : "POST",
             url : "{{ route('startup_assign_pm') }}",
              data : {
                "pmID" : pmID,
                "startupIDs" : startupIDs,
                "_token" : "{{ csrf_token() }}"
              },
              success: function(scatJson) {
                //alert("1");
               console.log(scatJson);
               alert("Starup Linked");
               window.location.href=window.location.href;
            }
       });
  }

$(function() {
  $('.ar-datatable').DataTable({
    "aaSorting": [],
    "columnDefs": [ {
      "targets": [ -1,0,5 ],
      "orderable": false
    } ]
  });
});

// $(document).ready( function () {
//     $('.ar-datatable').DataTable();
// } );
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