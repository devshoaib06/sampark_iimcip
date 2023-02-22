@extends('incubation.layouts.app')
@section('content')


<div class="listing-block">
    <div class="listing-block-heading">
        <h3>Parameter List</h3>

    </div>

    <div class="clearfix"></div>
    <div class="listing-block-from">
        <div class="left-block diago-block1">SL</div>
        <div class="left-block diago-block2">Parameter Name</div>
        <div class="left-block diago-block3">Score</div>
        <div class="left-block diago-block4">Comments</div>

        <div class="left-block diago-block6">Action</div>
        <div class="clearfix"></div>
    </div>


    @forelse ($parameterList as $key=>$list )
    {{-- {{ dd($list) }} --}}
    <div class="listing-block-inner-from">
        <div class="left-block diago-inner-block1">{{$key+1}}</div>

        <div class="left-block diago-inner-block2">
            {{$list->parameter_name}}
        </div>


        <div class="left-block diago-inner-block3">
            {{-- {{ dd($list->getResponseBriefData) }} --}}
            {{($list->getResponseBriefData)?$list->getResponseBriefData['parameter_score']:'NA'}}

        </div>
        <div class="left-block diago-inner-block4">
            {{($list->getResponseBriefData)?$list->getResponseBriefData['comment']:'NA' }}
            {{-- {{$list->getIncubatee->first_name}} {{$list->getIncubatee->last_name}} --}}

        </div>


        <div class="left-block diago-inner-block6">
            @if (Auth()->user()->user_type==6)
            <a href="{{ route('mentorDiagnostic') }}?mentor_id={{ $mentor_id }}&incubatee_id={{ $incubatee_id }}&diagnostic_id={{ $diagnostic_id }}"
                data-toggle="tooltip" data-placement="bottom" data-original-title="View Question & Answer">

                <img src="{{ asset('public/images/diagnostics.png') }}" width="25" alt="" srcset="">

            </a>
            @else
            <a href="{{ route('viewQuestAnsList') }}?mentor_id={{ $mentor_id }}&incubatee_id={{ $incubatee_id }}&parameter_id={{ $list->id }}&diagnostic_id={{ $diagnostic_id }}"
                data-toggle="tooltip" data-placement="bottom" data-original-title="View Question & Answer">

                <img src="{{ asset('public/images/diagnostics.png') }}" width="25" alt="" srcset="">

            </a>
            @endif
            {{-- <a href="" data-toggle="tooltip" data-placement="bottom" data-original-title="Edit Diagnostic">
                <i class="fa fa-2x fa fa-pencil"></i>
            </a> --}}
        </div>
        <div class="clearfix"></div>
    </div>
    @empty

    @endforelse


</div>

@endsection