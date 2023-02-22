@include('frontend.includes.account_header')
@include('frontend.includes.account_topbar')
<main>
    <div class="bodyCont">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                @yield('content')
                </div>
            </div>
        </div>
    </div>
</main>
@include('frontend.includes.account_footer')