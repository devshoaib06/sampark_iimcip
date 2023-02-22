@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        Hi <strong>{{ Auth::user()->first_name }}</strong>, Welcome to IIMCIP Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
@endsection

@section('content')
<hr/>
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif
      
      <div class="row">
        @if( isset($industryCategories) )
        <div class="col-md-4 col-sm-6 col-xs-12">
          <a href="{{ route('allIndustryCats') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-user" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Startup Business Verticals</span>
              <span class="info-box-number" style="font-size:40px;">{{ $industryCategories }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($users) )
        <div class="col-md-4 col-sm-6 col-xs-12">
          @if(Auth::user()->user_type == 1)
          <a href="{{ route('users_list') }}">
          @endif
          @if(Auth::user()->user_type == 6)
          <a href="{{ route('member_mentor', array('uid' => Auth::user()->id)) }}">
          @endif

          <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-user" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Startups</span>
              <span class="info-box-number" style="font-size:40px;">{{ $users }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif


         @if( isset($posts) )
        <div class="col-md-4 col-sm-6 col-xs-12">
          <a href="{{ route('main_post.all') }}">
          <div class="info-box">
            <span class="info-box-icon bg-blue">
                <i class="fa fa-user" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Topics/Q&A</span>
              <span class="info-box-number" style="font-size:40px;">{{ $posts }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        
      </div>
    </section>
@endsection
