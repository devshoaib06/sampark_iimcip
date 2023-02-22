@extends('incubation.layouts.app')
@section('content')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    @if (\Session::has('success'))
        <div class="alert alert-success">
            {!! \Session::get('success') !!}
        </div>
    @endif
    <div>
        <div class="float-start">
            <h4 class="pb-3">Create Task</h4>
        </div>
        <div class="float-end" style="float: right;">
            <a href="{{ route('incubatee.user.todo') }}" class="btn btn-info">
                <i class="fa fa-arrow-left"></i> All Task
            </a>
        </div>
        <div class="clearfix"></div>
    </div>
    @php
        $selfID = Auth::user()->id;
    @endphp
    <div class="col-sm-12">
        <div class="postCard manage-wrap">
            <div class="postWrap">
                <div class="pwdbox">
                    <form action="{{ route('incubatee.task.store') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="task_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="task_title" name="task_title">
                                </div>


                                <div class="form-group">
                                    <label for="task_details" class="form-label">Description</label>
                                    <textarea type="text" class="form-control" id="task_details" name="task_details" rows="5"></textarea>
                                </div>


                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="form-group">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea type="text" class="form-control" id="notes" name="notes" rows="5"></textarea>
                                </div>
                            </div>
                            @if (Auth::user()->user_type == 2)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Assign To</label>
                                        <select name="assigned_by[]" class="form-control assignedIds" multiple="multiple">
                                            <option value="" disabled>Select Assigned Task</option>

                                            <option value="{{ $selfID }}">{{ $startupName }}(SELF)</option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="deadline" class="form-label">Deadline</label>
                                        <input type="text" class="form-control" name="deadline" id="datepicker">
                                    </div>
                                </div>
                            @elseif (Auth::user()->user_type == 4)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Assign To</label>
                                        <select name="assigned_by[]" class="form-control assignedIds" multiple="multiple">
                                            <option value="" disabled>Select Assigned Task</option>
                                            <option value="{{ $selfID }}">{{ $selfName }} (SELF)</option>
                                            @if (isset($userList))
                                                @foreach ($userList as $c)
                                                    <option value="{{ $c->id }}">
                                                        @if ($c->user_type == 6)
                                                            {{ $c->contact_name }} - <strong
                                                                class="text-danger">(Mentor)</strong>
                                                        @else
                                                            {{ $c->contact_name }} - {{ $c->member_company }}
                                                            <strong class="tex-info">(StartUp)</strong>
                                                        @endif

                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="deadline" class="form-label">Deadline</label>
                                        <input type="text" class="form-control" name="deadline" id="datepicker">
                                    </div>
                                </div>
                            @elseif (Auth::user()->user_type == 6)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Assign To</label>
                                        <select name="assigned_by[]" class="form-control assignedIds" multiple="multiple">
                                            <option value="" disabled>Select Assigned Task</option>
                                            <option value="{{ $selfID }}">{{ $selfName }} (SELF)</option>
                                            @if (isset($userList))
                                                @foreach ($userList as $c)
                                                    <option value="{{ $c->id }}">
                                                        @if ($c->user_type == 6)
                                                            {{ $c->contact_name }} - <strong
                                                                class="text-danger">(Mentor)</strong>
                                                        @else
                                                            {{ $c->contact_name }} - {{ $c->member_company }}
                                                            <strong class="tex-info">(StartUp)</strong>
                                                        @endif

                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="deadline" class="form-label">Deadline</label>
                                        <input type="text" class="form-control" name="deadline" id="datepicker">
                                    </div>
                                </div>
                                {{-- @else
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Assign To</label>
                                        <select name="assigned_by[]" class="form-control assignedIds" multiple="multiple">
                                            <option value="" disabled>Select Assigned Task</option>
                                            @if (isset($allActiveUsers))
                                                <option value="{{ $selfID }}">{{ $selfName }}(SELF)</option>
                                                @foreach ($allActiveUsers as $c)
                                                    <option value="{{ $c->id }}">{{ $c->member_company }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="deadline" class="form-label">Deadline</label>
                                        <input type="text" class="form-control" id="datepicker" name="deadline">
                                    </div>
                                </div> --}}
                            @endif
                        </div>



                        <input id="status" name="status" type="hidden" value="0">
                        {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description" class="form-label">Status</label>
                                     <select name="status" id="status" class="form-control">
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status['value'] }}">{{ $status['label'] }}</option>
                                        @endforeach
                                    </select> 
                                    

                                </div>
                            </div> --}}



                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('incubatee.user.todo') }}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left"></i>
            Cancel</a>
        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i>
            Save
        </button>
    </div>
    </form>
    </div>
    {{-- 
</div>
--}}
@endsection
@push('page_js')
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                showButtonPanel: true
            });
        });
    </script>
@endpush
