@extends('incubation.layouts.app')
@section('content')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" /> --}}

    {{-- Bootstrap CSS --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> --}}

    {{--    
        <div class="float-start">
            <h4 class="pb-3">My Tasks</h4>
        </div> --}}
    <div class="col-lg-12 todo-my-task">


        <div class="task-holder">
            <h4>My Tasks</h4>
            <ul class="nav mt-3 justify-content-end post-nav">

                <li class="nav-item">
                    <a href="{{ route('incubatee.task.create') }}" class="nav-link dropdown-toggle">
                        <i class="fa fa-plus-circle"></i> Create Task
                    </a>
                </li>

                <li class="nav-item">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownFilter"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Filter">
                            <i class="fas fa-filter"></i>
                            <span class="d-none d-sm-block">Filter</span>
                        </a>
                        <form action="{{ route('incubatee.task.status') }}" method="GET">

                            <div class="dropdown-menu checkbox-menu allow-focus">
                                <div class="drop-box">
                                    <div class="relevant-post px-3">
                                        <h5>Filter</h5>
                                        <div class="twin-box">

                                            {{-- @if (isset($status))	
                                            @foreach ($statuses as $c)
                                            
                                             <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input filterCheckbox" id="checkbox{{ $c['value'] }}" name="status" value="{{$c['value']}}" 
                                                >
                                                <label class="custom-control-label" for="checkbox{{$c['value'] }}">{{ $c['label'] }}</label>
                                            </div> 
                                            
                                            @endforeach
                                             @endif  --}}

                                            @if (isset($statuses))
                                                @foreach ($statuses as $c)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input filterCheckbox"
                                                            id="checkbox{{ $c['value'] }}" name="status"
                                                            value="{{ $c['value'] }}">
                                                        <label class="custom-control-label"
                                                            for="checkbox{{ $c['value'] }}">{{ $c['label'] }}</label>
                                                    </div>
                                                @endforeach
                                            @endif


                                        </div>
                                    </div>



                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="px-3 text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

            </ul>
        </div>
        <div class="clearfix"></div>



        <div class="table-responsive">
            <table class="table todo-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Details</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($status))
                        @foreach ($status as $key => $task)
                            <tr>

                                <th scope="row">{{ $key + 1 }}</th>
                                @if ($task->status == '1')
                                    <td><i class="fa fa-circle  text-success"></i>
                                        {{ ucfirst($task->task_title) }}
                                    </td>
                                @else
                                    <td>
                                        <i class="fa fa-circle  text-warning"></i>
                                        {{ ucfirst($task->task_title) }}
                                    </td>
                                @endif
                                <td>
                                    @if ($task->status == '1')
                                        {{ str_limit($task->task_details, $limit = 30, $end = '...') }}
                                    @else
                                        {{ str_limit($task->task_details, $limit = 30, $end = '...') }}
                                    @endif
                                    <br>
                                    <small>Last Updated - {{ $task->updated_at->diffForHumans() }} </small>
                                </td>
                                <td>
                                    @if ($task->status === '1')
                                        <span class="badge rounded-pill bg-info text-white">
                                            Completed
                                        </span>
                                    @elseif($task->status === '0')
                                        <span class="badge rounded-pill bg-success text-white">
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning text-white">
                                            In Process
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('incubatee.task.view', [$task->id]) }}" class="btn btn-warning"
                                        target="_blank">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>

                                    <a href="{{ route('incubatee.task.edit', $task->id) }}" class="btn btn-success">

                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('incubatee.task.destroy', $task->id) }}" style="display: inline"
                                        method="POST" onsubmit="return confirm('Are you sure to delete ?')">
                                        {{ csrf_field() }}
                                        {{ method_field('delete') }}

                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    @endif



                </tbody>
            </table>

        </div>
    </div>


    @if (count($tasks) === 0)
        <div class="alert alert-danger p-2">
            No Task Found. Please Create one task
            <br>
            <br>
            <a href="{{ route('incubatee.task.create') }}" class="btn btn-info">
                <i class="fa fa-plus-circle"></i> Create Task
            </a>
        </div>
    @endif



@endsection
