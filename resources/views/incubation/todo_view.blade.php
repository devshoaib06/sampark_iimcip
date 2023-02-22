@extends('incubation.layouts.app')
@section('content')
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}

        </div>
    @endif


    <div>
        <div class="float-start">
            <h4 class="pb-3">Task View <span class="badge bg-info">{{ $task->title }}</span></h4>
        </div>
        <div class="float-end">
            <a href="{{ route('incubatee.user.todo') }}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Task
            </a>
        </div>
        <div class="clearfix"></div>
    </div>

    {{-- <div class="card card-body bg-light p-4"> --}}
    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="task_details" class="form-label">Todo Details</label>
                            <textarea type="text" class="form-control" id="task_details" name="task_details" rows="5" disabled>{{ $task->task_details }}</textarea>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>



    </div>
@endsection
