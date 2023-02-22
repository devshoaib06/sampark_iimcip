@include('frontend.includes.account_header')
@include('frontend.includes.account_topbar')
<main>
    <div class="bodyCont">
        <div class="container">
		@include('frontend.includes.account_right_menu')
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3 d-none">
                @include('frontend.includes.account_sidebar')
                </div>
                <!--<div class="col-sm-12 col-md-8 col-lg-9 ">-->
				<div class="col-lg-12">
                @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
@include('frontend.includes.account_footer')