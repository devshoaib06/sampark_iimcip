<div class="row">
    <div class="col-lg-12">
        <ul class="nav justify-end post-nav">
            @php
                $url_1 = ['feeds'];
                $currentUrl_1 = request()->route()->uri;
            @endphp
            @if (in_array($currentUrl_1, $url_1))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('front.user.myfavposts') }}" title="My Favourites">
                        <i class="fas fa-heart"></i>
                        <span class="d-none d-sm-block">My Favourites</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('front.user.notification') }}">
                        <i class="fas fa-bell"></i>
                        <span class="d-none d-sm-block">Notification</span>
                    </a>
                </li>
            @endif
            @php
                $url_2 = ['feeds', 'company', 'startup'];
                $currentUrl_2 = request()->route()->uri;
            @endphp
            @if (in_array($currentUrl_2, $url_2))
                <li class="nav-item">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownFilter"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Filter">
                            <i class="fas fa-filter"></i>
                            <span class="d-none d-sm-block">Filter</span>
                        </a>
                        <form action="{{ route('front.user.allcheckpost') }}" method="GET">
                            <div class="dropdown-menu checkbox-menu allow-focus">
                                <div class="drop-box">
                                    @if (in_array($currentUrl_1, $url_1))
                                        <div class="relevant-post px-3">
                                            <h5>Relevant Post</h5>
                                            <div class="twin-box">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkbox1"
                                                        name="all_post_filter[]">
                                                    <label class="custom-control-label" for="checkbox1">All
                                                        Posts</label>
                                                </div>
                                                @if (!empty($category))
                                                    @foreach ($category as $c)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox"
                                                                class="custom-control-input filterCheckbox"
                                                                id="checkbox{{ $c->id }}" name="post_filter[]"
                                                                value="{{ $c->id }}">
                                                            <label class="custom-control-label"
                                                                for="checkbox{{ $c->id }}">{{ $c->name }}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                {{-- 
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox3">
                                 <label class="custom-control-label" for="checkbox3">Achievements</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox4">
                                 <label class="custom-control-label" for="checkbox4">Opportunities</label>
                              </div>
                              --}}
                                            </div>
                                        </div>
                                        <div class="dropdown-divider"></div>
                                    @endif
                                    {{-- 
                        <div class="relevant-post px-3">
                           <div class="twin-box">
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox5">
                                 <label class="custom-control-label" for="checkbox5">IIMCIP Posts</label>
                              </div>
                           </div>
                           <div class="dropdown-divider"></div>
                        </div>
                        --}}
                                    <div class="relevant-post px-3">
                                        <h5>Industry Verticals</h5>
                                        <div class="twin-box">
                                            @if (!empty($industry_category))
                                                @foreach ($industry_category as $ic)
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" name="industry_filter[]"
                                                            class="custom-control-input"
                                                            id="checkbox{{ $ic->industry_category }}"
                                                            value="{{ $ic->id }}">
                                                        <label class="custom-control-label"
                                                            for="checkbox{{ $ic->industry_category }}">{{ $ic->industry_category }}</label>
                                                    </div>
                                                @endforeach
                                            @endif
                                            {{-- 
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox7">
                                 <label class="custom-control-label" for="checkbox7">Announcements</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox8">
                                 <label class="custom-control-label" for="checkbox8">Achievements</label>
                              </div>
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox" class="custom-control-input" id="checkbox9">
                                 <label class="custom-control-label" for="checkbox9">Opportunities</label>
                              </div>
                              --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <div class="px-3 text-center">
                                    <button type="submit" class="btn btn-primary btn-block">Apply
                                        Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
            @endif
            {{-- @php
         //$url_3 = ['incubation/startup/view/{startUpId}'];

         $url_3 = ['startup.view',''];
         
         $currentUrl_3 = Route::currentRouteName();

         //echo  $currentUrl_3;
         
         @endphp --}}

            @if (Session::has('startUpId'))
                {{-- @foreach ($mentorList as $key => $list) --}}
                <li class="nav-item {{ request()->routeIs('startup.view') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('startup.view', ['startUpId' => Session::get('startUpId')]) }}"
                        title="View">
                        <span class="d-none d-sm-block">View</span>
                    </a>
                </li>

                {{-- <li class="nav-item {{ request()->routeIs('startup.mngprof') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.mngprof', ['startUpId' => Session::get('startUpId')]) }}"
                        title="General">
                        <span class="d-none d-sm-block">General</span>
                    </a>
                </li> --}}


                <li class="nav-item {{ request()->routeIs('startup.pvtprof') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.pvtprof', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Investments">
                        <span class="d-none d-sm-block">Investments</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('startup.addcust') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addcust', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Customers">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Customers</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('startup.addfin') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addfin', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Financials(Yearly)">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Financials(Yearly)</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('startup.addfinmon') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addfinmon', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Financials(Monthly) ">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Financials(Monthly) </span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addfinexp') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addfinexp', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Expenses">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Expenses </span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addorderpipe') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addorderpipe', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Order Pipeline">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Order Pipeline </span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addyearlytarget') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addyearlytarget', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Yearly Target">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Yearly Target</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addimpact') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addimpact', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Impact">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Impact</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addfunding') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addfunding', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Funding Needs">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Funding Needs</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.addcompl') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.addcompl', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Compliances">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Compliances</span>
                    </a>
                </li>
                <li class="nav-item {{ request()->routeIs('startup.chart') ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('startup.chart', ['startUpId' => Session::get('startUpId')]) }}"
                        title="Reports">
                        {{-- <i class="fas fa-user-plus"></i> --}}
                        <span class="d-none d-sm-block">Reports</span>
                    </a>
                </li>
                {{-- @endforeach --}}
            @endif
        </ul>
    </div>
</div>
