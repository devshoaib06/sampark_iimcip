@include('frontend.includes.account_header')
@include('frontend.includes.account_topbar1')
<main>
    <div class="bodyCont">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3">
                @include('frontend.includes.account_sidebar_mentor')
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
@include('frontend.includes.account_footer')