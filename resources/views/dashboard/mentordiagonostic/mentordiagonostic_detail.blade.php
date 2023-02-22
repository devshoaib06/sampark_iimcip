@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Mentor Diagonostics <br>
    <small>Parameter List</small>
    <!--small>it all starts here</small-->
  </h1>
  <!-- <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('addIndustryCats') }}">Add Verticals</a></li>
      </ol> -->
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}
  </div>
  @endif

  {{--<div class="row">
    <div class="col-md-6">
      <a href="{{ route('addparameters') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add
  Parameters</a>
  </div>
  <div class="col-md-6">
  </div>
  </div>--}}

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <!--  <h3 class="box-title">All Industry Categories</h3> -->

      <div class="box-tools pull-right">

      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable display nowrap" style="width: 100%;">
        <thead>
          <tr>
            <th>SL</th>
            <th>Parameter Name</th>
            <th>Score</th>
            <th>Comments</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          @php $sl = 1; @endphp
          @foreach($mentorList as $list)
          <tr>
            <td>{{ $sl }}</td>

            <td>


              {{$list->getParameter->parameter_name}}

            </td>


            <td>
              {{$list->parameter_score}}

            </td>

            <td>{{ $list->comment }}</td>

            <td><a
                href="{{ route('questionAnswer') }}?mentor_id={{ $list->mentor_id }}&incubatee_id={{ $list->incubatee_id }}&parameter_id={{ $list->parameter_id }}">View
                Questions &
                Answer</a></td>

          </tr>
          @php $sl++; @endphp
          @endforeach


        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
@endsection

@push('page_js')
<script type="text/javascript">
  $( function () {
    $( '.ar-datatable' ).DataTable( {
      "columnDefs": [ {
        "targets": [ 0, 1, 2 ],
        "orderable": false
      } ]
    } );
  } );
</script>
@endpush