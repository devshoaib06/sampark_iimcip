@extends('incubation.layouts.app')
@section('content')


<div class="listing-block">
    <div class="listing-block-heading">



        <h3>Diagnostics List</h3>
        {{-- <h4>Over {{$incubatees->get_mentor_name_count}} members</h4> --}}
    </div>
    <div class="listing-block-member">
        <a href="{{ route('addDiagnostic',['startUpId'=>$startUpId]) }}"><i class="fa fa-plus" aria-hidden="true"></i>
            New Diagnostic</a>
        <a href="{{ route('incubatee.view') }}">
            Back Incubatee List</a>
    </div>
    <div class="clearfix"></div>
    <div class="listing-block-from">
        <div class="left-block diago-block1">SL</div>
        <div class="left-block diago-block2">Title</div>
        <div class="left-block diago-block3">Mentor Name </div>
        <div class="left-block diago-block4">Startup Name</div>
        <div class="left-block diago-block5">Status</div>
        <div class="left-block diago-block6">Action</div>
        <div class="clearfix"></div>
    </div>


    @forelse ($diagnosticList as $key=>$list )
    {{-- {{ dd($list) }} --}}
    <div class="listing-block-inner-from">
        <div class="left-block diago-inner-block1">{{$key+1}}</div>

        <div class="left-block diago-inner-block2">
            {{$list->title}}
        </div>


        <div class="left-block diago-inner-block3">
            {{$list->getMentor->first_name}} {{$list->getMentor->last_name}}

        </div>
        <div class="left-block diago-inner-block4">
            {{$list->getIncubatee->contact_name}}
            {{-- {{$list->getIncubatee->first_name}} {{$list->getIncubatee->last_name}} --}}

        </div>
        <div class="left-block diago-inner-block5">

            @if ($list->status==1)
            Pending
            @elseif ($list->status==2)
            In Progress
            @elseif ($list->status==3)
            Done
            @elseif ($list->status==4)
            Complete
            @endif

        </div>

        <div class="left-block diago-inner-block6">

            <a href="{{ route('viewParameterList') }}?mentor_id={{ $list->mentor_id }}&incubatee_id={{ $list->incubatee_id }}&diagnostic_id={{ $list->id }}"
                data-toggle="tooltip" data-placement="bottom" data-original-title="View Parameter Details">

                <img src="{{ asset('public/images/diagnostics.png') }}" width="25" alt="" srcset="">

            </a>




            @if (Auth()->user()->user_type!=6)
            <a href="{{ route('editDiagnostic',['startUpId'=>$startUpId,'diagnosticId'=>$list->id]) }}"
                data-toggle="tooltip" data-placement="bottom" data-original-title="Edit Diagnostic">
                <i class="fa fa-2x fa fa-pencil"></i>
            </a>
            @endif

        </div>
        <div class="clearfix"></div>
    </div>
    @empty

    @endforelse


</div>

@endsection