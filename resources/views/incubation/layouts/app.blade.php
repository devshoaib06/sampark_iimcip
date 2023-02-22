@include('incubation.includes.account_header')
@include('incubation.includes.account_topbar')
<main>
    <div class="bodyCont">
        <div class="container">
		 {{-- @include('incubation.includes.account_right_menu')  --}}
         @yield('accountRightMenu')
            <div class="row">
                <div class="col-sm-12 col-md-4 col-lg-3 d-none">
                @include('incubation.includes.account_sidebar')
                </div>
                <!--<div class="col-sm-12 col-md-8 col-lg-9 ">-->
				<div class="col-lg-12">
                @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
@include('incubation.includes.account_footer')