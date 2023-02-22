@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Mentor Diagonostics <br>
    <small>Question And Answer List</small>
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
      <h3>Parameter Info</h3>
      <table class="table table-bordered table-hover table-striped display nowrap" style="width: 100%;">
        <thead>
          <tr>
            <th>Parameter Name</th>
            <th>Score</th>
            <th>Comments</th>
          </tr>
        </thead>
        <tbody>

          <tr>
            <td>
              {{ $parameter->parameter_name }}
            </td>
            <td>
              {{ isset($responseBrief)?$responseBrief->parameter_score:'' }}
            </td>


            <td>
              {{ isset($responseBrief)?$responseBrief->comment:'' }}
            </td>
          </tr>


        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <h3>Question And Answer List</h3>
      <table class="table table-bordered table-hover table-striped ar-datatable display nowrap" style="width: 100%;">
        <thead>
          <tr>
            <th>SL</th>
            <th>Question</th>
            <th>Answer</th>

          </tr>
        </thead>
        <tbody>

          @php $sl = 1; @endphp
          @foreach($questions as $key=>$value)
          <tr>
            <td>{{ $sl }}</td>

            <td>
              {{ $value->question_text }}
            </td>

            <td>
              <a class="btn @if (isset($quest[$value->id]))
                @if ($quest[$value->id]=='1')
                btn-success
                @elseif ($quest[$value->id]=='2')
                btn-warning
                @elseif ($quest[$value->id]=='3')
                btn-danger
                @endif
                @endif">
                @if (isset($quest[$value->id]))
                @if ($quest[$value->id]=='1')
                Yes
                @elseif ($quest[$value->id]=='2')
                May Be
                @elseif ($quest[$value->id]=='3')
                Partial
                @endif
                @endif
              </a>

            </td>

          </tr>
          @php $sl++; @endphp
          @endforeach


        </tbody>
      </table>
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