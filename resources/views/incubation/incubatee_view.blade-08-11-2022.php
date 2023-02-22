@extends('incubation.layouts.app')
@section('content')


<div class="listing-block">
    <div class="listing-block-heading">
        <h3>INCUBATES</h3>
        {{-- <h4>Over {{$incubatees->get_mentor_name_count}} members</h4> --}}
    </div>
    <div class="listing-block-member">
        <a href=""><i class="fa fa-plus" aria-hidden="true"></i> New member</a>
    </div>
    <div class="clearfix"></div>
    <div class="listing-block-from">
        <div class="left-block block1">SI#</div>
        <div class="left-block block2">Logo</div>
        <div class="left-block block3">Organization Name</div>
        {{-- <div class="left-block block4">Organization Type</div> --}}
        <div class="left-block block5">Mentor Name</div>
        <div class="left-block block8">Service Location</div>
        <div class="left-block block6">Programme</div>
        <div class="left-block block7">Action</div>
        <div class="clearfix"></div>
    </div>
    @foreach($mentorList as $key => $list)
    {{-- {{ dd($list->getCompanyType->company_type) }} --}}
    <div class="listing-block-inner-from">
        <div class="left-block block-inner1">{{$key+1}}</div>
        <div class="left-block block-inner2">

            @if ($list->image=='')
            <img src="{{asset('public/images/dummy_logo.png')}}" width="50" align="left">
            @else
            <img src="{{ asset('public/uploads/user_images/thumb')}}/{{ $list->image  }}" width="50" align="left">
            @endif


        </div>
        <div class="left-block block-inner3">
            {{$list->member_company}}
        </div>


        {{-- <div class="left-block block-inner4">
            {{ ($list->getCompanyType['company_type']!=''?$list->getCompanyType['company_type']:'NA')}}
    </div> --}}
    <div class="left-block block-inner5">
        @forelse ($list->getMentor as $menKey=>$mentor )
        {{$mentor->first_name}} {{$mentor->last_name}} {{ ($menKey>0)?',':'' }}

        @empty
        NA
        @endforelse
    </div>
    <div class="left-block block-inner6">
        @forelse ($list->getServiceLocation as $locKey=>$loc )
        {{$loc->name}} {{($locKey>0)?',':''  }}

        @empty
        NA
        @endforelse
    </div>
    <div class="left-block block-inner8">
        @forelse ($list->getProgramme as $progKey=>$prog )
        {{$prog->name}} {{($progKey>0)?',':''  }}

        @empty
        NA
        @endforelse
    </div>
    <div class="left-block block-inner7">
        <a href="{{ route('diagnosticsList',['startUpId'=>$list->id]) }}" data-toggle="tooltip" data-placement="bottom"
            data-original-title="diagnostics ">

            <img src="{{ asset('public/images/diagnostics.png') }}" width="25" alt="" srcset="">

        </a>
        <a href="" data-toggle="tooltip" data-placement="bottom" data-original-title="View Details ">

            <img src="{{ asset('public/images/details.png') }}" width="25" alt="" srcset="">

        </a>
        <a href="" data-toggle="tooltip" data-placement="bottom" data-original-title="Reports">
            <img src="{{ asset('public/images/reports.png') }}" width="25" alt="" srcset="">
        </a>
        <a href="" data-toggle="tooltip" data-placement="bottom" data-original-title="Assign Mentor">
            <i class="fa fa-2x fa fa-plus"></i>
        </a>
    </div>
    <div class="clearfix"></div>
</div>
@endforeach


</div>

@endsection