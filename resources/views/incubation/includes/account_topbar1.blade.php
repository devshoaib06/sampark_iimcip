
<header class="siteHeader">
    <div class="container">
        <div class="row align-items-center no-gutters">
            <div class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                <div class="logo">
                    <a href="{{ route('front.user.feed') }}?post=feed">
                        <img src="{{ asset('public/front_end/images/logo-1.png') }}" class="img-fluid" alt="IIM Calcutta" />
                    </a>
                </div>
            </div>
            <div class="col-1 col-sm-5 col-md-6 col-lg-7 col-xl-6">
                <div class="search search-desktop">
                 
                </div>
            </div>
            <div class="col-9 col-sm-5 col-md-4 col-lg-3 col-xl-4">
                <div class="topRight">
                    <ul>
                        <li>
                            <div class="searchMobile">
                               
                            </div>
                        </li>
                        <li>
                             @php 

                              //echo Auth::user()->fresh()->last_login;die;
                                if(empty(Auth::user()->fresh()->last_login))
                                {
                                    $postcount =0;
                                }
                                else
                                {
                                    if( Auth::user()->fresh()->post_read ==1)
                                    {
                                        $postcount =0;
                                    }
                                    else
                                    {
                                        //echo Auth::user()->fresh()->last_login;die;

                                        $postcount = DB::table('post_master')->join('users', 'users.id', '=', 'post_master.member_id')->where('post_master.created_at','>=', Auth::user()->fresh()->last_login)->where('post_master.member_id','!=',Auth::user()->fresh()->id)->count();
                                    }

                                }

                               
                                //echo $postcount;die;

                                
                            @endphp
                            <a href="{{ route('front.user.last-login_post') }}"><img src="{{ asset('public/front_end/images/notification-icon.png') }}" width="50" class="width-inheherit">@if($postcount)<span id="post_count">{{ $postcount }}</span>@endif</a></li>
                        <li class="dropdown">
                            @php 
                                $profileImage = asset('public/front_end/images/profile-pic.png');
                                if(Auth::user()->image != '' && Auth::user()->image != null) {
                                    $profileImage = asset('public/uploads/user_images/thumb/'. Auth::user()->image);
                                }
                                else
                                {
                                    $profileImage = asset('public/uploads/user_images/thumb/entreprenaur_photo/noimage.png');
                                }
                            @endphp
                            <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="profile-picture "><img src="{{ $profileImage }}" class="width-inheherit1" ></a></span>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">{{ Auth::user()->contact_name }} <span style="font-size: 14px;font-weight: normal;">{{ Auth::user()->member_company }}</span>
                                    <span></span></a>
                                <div class="dropdown-divider"></div> 
                                 

                                <a class="dropdown-item" href="{{ route('front.user.mngprof') }}"><span><i class="fas fa-user"></i></span>Update Company Profile</a>
                                <a class="dropdown-item" href="{{ route('front.user.mnguserprof') }}"><span><i class="fas fa-user"></i></span>Update My Profile</a>
                                <a class="dropdown-item" href="{{ route('front.user.cngpwd') }}"><span><i class="fas fa-cog"></i></span>Change Password</a>
                                <a class="dropdown-item" href="{{ route('front.user.logout') }}"><span><i class="fas fa-sign-out-alt"></i></span>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>