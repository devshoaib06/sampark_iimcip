@extends('incubation.layouts.app')
@section('content')
    <div class="container">
        @if (Auth::user()->user_type == '4' || Auth::user()->user_type == '6')
            <div class="dashboard-block">
                <div class="dashboard-titel"><a href="{{ route('incubatee.view') }}">
                        {{ Auth::user()->user_type == 6 ? 'My Mentees' : 'Incubates' }} </a></div>
                <div class="dashboard-icon"><img src="{{ asset('public/incubation/images/home-icon1.png') }}"></div>
                <div class="clearfix"></div>
                <div class="dashboard-count"><a href="{{ route('incubatee.view') }}">{{ $incubatees }}</a></div>
                <hr />
                <div class="dashboard-details"><a href="{{ route('incubatee.view') }}">View Details </a></div>
            </div>
        @endif



        <div class="dashboard-block">
            <div class="dashboard-titel">
                <a href="{{ route('incubatee.user.todo') }}">
                    To Do
                </a>
            </div>
            <div class="dashboard-icon"><img src="{{ asset('public/incubation/images/home-icon2.png') }}"></div>
            <div class="clearfix"></div>
            <div class="dashboard-count"><a href="{{ route('incubatee.user.todo') }}">{{ $todos }}</a></div>
            <hr />
            <div class="dashboard-details"><a href="{{ route('incubatee.user.todo') }}"> View Details </a></div>
            {{-- <div class="terget">TARGET</div> 
       <div  class="percentage">49%</div> 
       <div class="clearfix"></div>
        <div class="progress">
            <div class="bar1" style="width:49%">
                
            </div>
        </div> --}}
        </div>

        {{-- <div class="dashboard-block">
        <div class="dashboard-titel"><a href="#">Reportees </a></div>
        <div class="dashboard-icon"><img src="{{ asset('public/incubation/images/home-icon3.png')}}"></div>
        <div class="clearfix"></div>
        <div class="dashboard-count">8947</div>
      
    </div>  --}}
        {{-- <div class="dashboard-block" style="margin-right: 0;">
        <div class="dashboard-titel"><a href="#">Collaboration </a></div>
        <div class="dashboard-icon"><img src="{{ asset('public/incubation/images/home-icon4.png')}}"></div>
        <div class="clearfix"></div>
        <div class="dashboard-count">178</div>
      
    </div> --}}

        <div class="clearfix"></div>
    </div>

    <!--Question modal-->
    @include('frontend.includes.add_question_modal')
@endsection

@push('page_js')
    <script></script>
@endpush
