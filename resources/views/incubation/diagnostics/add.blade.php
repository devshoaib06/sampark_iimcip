@extends('incubation.layouts.app')
@section('content')


@if (\Session::has('success'))
<div class="alert alert-success">
    {!! \Session::get('success') !!}

</div>
@endif

<div>
    <div class="float-start">
        <h4 class="pb-3">{{ isset($diagnostic)?'Update':'Create' }} Diagnostics</h4>
    </div>
    <div class="float-end">
        <a href="{{ route('diagnosticsList',['startUpId'=>$startUpId]) }}" class="btn btn-info">
            <i class="fa fa-arrow-left"></i> Diagonostics List
        </a>
    </div>
    <div class="clearfix"></div>
</div>

<div class="col-sm-12">
    <div class="postCard manage-wrap">
        <div class="postWrap">
            <div class="pwdbox">

                @if (isset($diagnostic))
                <form action="{{ url('incubation/incubatee/edit-diagnostics') }}/{{ $startUpId }}/{{ $diagnostic->id }}"
                    method="POST">

                    @else
                    <form action="{{ route('addDiagnostic',['startUpId'=>$startUpId]) }}" method="POST">
                        @endif
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="task_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="task_title" name="title"
                                        value="{{ isset($diagnostic)?$diagnostic->title:''}}">
                                </div>
                            </div>


                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label>Mentor List</label>
                                    {{-- {{ dd($diagnostic->mentor_id) }} --}}

                                    <select name="mentor_id" class="form-control assignedIds">
                                        <option value="" readonly>Select Mentor</option>
                                        @if(isset($mentorList) && count($mentorList))
                                        @foreach($mentorList as $c)

                                        <option value="{{ $c->id }}" @if (isset($diagnostic))
                                            {{ $c->id==$diagnostic->mentor_id?'selected':'' }} @endif>
                                            {{ $c->first_name }} {{ $c->last_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>

                            </div>




                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="description" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        {{-- @foreach ($statuses as $status) --}}
                                        <option value="1" @if (isset($diagnostic))
                                            {{ $diagnostic->status==1?'selected':'' }} @endif>Pending
                                        </option>
                                        @if (isset($diagnostic))
                                        <option value="2" @if (isset($diagnostic))
                                            {{ $diagnostic->status==2?'selected':'' }} @endif>In
                                            Progress</option>
                                        <option value="3" @if (isset($diagnostic))
                                            {{ $diagnostic->status==3?'selected':'' }} @endif>Done
                                        </option>
                                        <option value="4" @if (isset($diagnostic))
                                            {{ $diagnostic->status==4?'selected':'' }} @endif>Completed
                                        </option>
                                        @endif
                                        {{-- @endforeach --}}
                                    </select>
                                </div>
                            </div>




                        </div>


            </div>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('diagnosticsList',['startUpId'=>$startUpId]) }}" class="btn btn-secondary mr-2"><i
                class="fa fa-arrow-left"></i>
            Cancel</a>

        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i>
            {{ isset($diagnostic)?'Update':'Save' }}
        </button>
    </div>

    </form>
</div>

{{-- </div> --}}

@endsection



@push('page_js')


<script>

</script>


@endpush