@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    All Mentor Diagonostics
  </h1>

</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}
  </div>
  @endif


  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <div class="box-tools pull-right">

      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable display nowrap" style="width: 100%;">
        <thead>
          <tr>
            <th>SL</th>
            <th>Diagnostic Name</th>
            <th>Mentor Name</th>
            <th>Incubatee Name</th>

            <th>Action</th>
          </tr>
        </thead>
        <tbody>

          @php $sl = 1; @endphp
          @foreach($mentorList as $list)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              {{ $list->getDiagnostic['title']!=''? $list->getDiagnostic['title']:'NA' }}
            </td>
            <td>
              {{$list->getMentorName->first_name}}
              {{$list->getMentorName->last_name}}
            </td>
            <td>
              {{$list->getIncubatee['contact_name']!=''?$list->getIncubatee['contact_name']:'NA'}}
            </td>

            <td><a
                href="{{ route('mentordiagonosticDetails') }}?mentor_id={{ $list->mentor_id }}&incubatee_id={{ $list->incubatee_id }}&diagnostic_id={{ $list->diagnostic_id }}">View
                Parameter Details</a></td>

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