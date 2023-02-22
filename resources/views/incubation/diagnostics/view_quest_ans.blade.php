@extends('incubation.layouts.app')
@section('content')


<div class="listing-block">
    {{-- <div class="listing-block-heading">
        <h3>Parameter List</h3>

    </div> --}}

    <div class="clearfix"></div>
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
            <table class="table table-bordered table-hover table-striped ar-datatable display nowrap"
                style="width: 100%;">
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



</div>

@endsection