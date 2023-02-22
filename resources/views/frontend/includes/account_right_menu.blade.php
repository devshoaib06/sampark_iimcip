<div class="row">
   <div class="col-lg-12">
      <ul class="nav mt-3 justify-end post-nav">
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
               <a class="nav-link dropdown-toggle" href="#" role="button"
                  id="dropdownFilter" data-toggle="dropdown" aria-haspopup="true"
                  aria-expanded="false" title="Filter">
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
                                 <input type="checkbox" class="custom-control-input"
                                    id="checkbox1" name="all_post_filter[]">
                                 <label class="custom-control-label" for="checkbox1">All
                                 Posts</label>
                              </div>
                              @if (!empty($category))
                              @foreach ($category as $c)
                              <div class="custom-control custom-checkbox">
                                 <input type="checkbox"
                                    class="custom-control-input filterCheckbox"
                                    id="checkbox{{ $c->id }}"
                                    name="post_filter[]"
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
         @php
         $url_3 = ['manage-profile', 'public-profile', 'private-profile', 'add_customer', 'add_financials','add_financials_month', 'add_financials_expenses','add_order_pipeline','add_yearly_targets','add_impacts','add_funding_needs','add_compliance_checks'];
         $currentUrl_3 = request()->route()->uri;
         @endphp
         @if (in_array($currentUrl_3, $url_3))
         <li class="nav-item {{ (request()->routeIs('front.user.pblprof')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.pblprof') }}" title="General">
            <span class="d-none d-sm-block">General</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.pvtprof')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.pvtprof') }}"
               title="Investments">
            <span class="d-none d-sm-block">Investments</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addcust')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addcust') }}" title="Customers">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Customers</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addfin')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addfin') }}"
               title="Financials(Yearly)">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Financials(Yearly)</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addfinmon')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addfinmon') }}"
               title="Financials(Monthly) ">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Financials(Monthly) </span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addfinexp')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addfinexp') }}"
               title="Expenses">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Expenses </span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addorderpipe')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addorderpipe') }}"
               title="Order Pipeline">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Order Pipeline </span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addyearlytarget')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addyearlytarget') }}"
               title="Yearly Target">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Yearly Target</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addimpact')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addimpact') }}"
               title="Impact">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Impact</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addfunding')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addfunding') }}"
               title="Funding Needs">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Funding Needs</span>
            </a>
         </li>
         <li class="nav-item {{ (request()->routeIs('front.user.addcompl')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('front.user.addcompl') }}"
               title="Compliances">
            {{-- <i class="fas fa-user-plus"></i> --}}
            <span class="d-none d-sm-block">Compliances</span>
            </a>
         </li>
         @endif
      </ul>
   </div>
</div>
