                 <div class="row">
                <div class="col-lg-12">
                    <ul class="nav mt-3 justify-content-end post-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('front.user.myfavposts') }}" title="My Favourites">
                                <i class="fas fa-heart"></i>
                                <span class="d-none d-sm-block">My Favourites</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" title="Notification">
                                <i class="fas fa-bell"></i>
                                <span class="d-none d-sm-block">Notification</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownFilter"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Filter">
                                    <i class="fas fa-filter"></i>
                                    <span class="d-none d-sm-block">Filter</span>
                                </a>
								<form action="{{ route('front.user.allcheckpost') }}" method= "GET">
								
                                <div class="dropdown-menu checkbox-menu allow-focus">
                                    <div class="relevant-post px-3">
                                        <h5>Relevant Post</h5>
										<div class="twin-box">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox1" >
                                            <label class="custom-control-label" for="checkbox1">All Posts</label>
                                        </div>
										
										
										@if(!empty($category))
										@foreach($category as $c)
										
										<div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input filterCheckbox" id="checkbox{{ $c->id }}" name="post_filter[]" value="{{$c->id}}" 
											>
                                            <label class="custom-control-label" for="checkbox{{ $c->id }}">{{ $c->name }}</label>
                                        </div>
										
										@endforeach
										@endif
										
									
                                        {{--<div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox3">
                                            <label class="custom-control-label" for="checkbox3">Achievements</label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox4">
                                            <label class="custom-control-label" for="checkbox4">Opportunities</label>
                                        </div>--}}
									</div>	
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    {{--<div class="relevant-post px-3">
									<div class="twin-box">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox5">
                                            <label class="custom-control-label" for="checkbox5">IIMCIP Posts</label>
                                        </div>
									</div>	
                                    </div>--}}
                                    <div class="dropdown-divider"></div>
                                    <div class="relevant-post px-3">
                                        <h5>Industry Verticals</h5>
										<div class="twin-box">
										@foreach($industry_category as $ic)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="industry_filter[]" class="custom-control-input" id="checkbox{{ $ic->id }}" value="{{$ic->id}}">
                                            <label class="custom-control-label" for="checkbox{{$ic->id}}">{{$ic->industry_category}}</label>
                                        </div>
										@endforeach
										
										{{-- <div class="custom-control custom-checkbox">
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
                                        </div>--}}
										</div>
                                    </div>
									<div class="dropdown-divider"></div>
                                    <div class="px-3 text-center">
                                        <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
                                    </div>
                                </div>
								</form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
     
	
	
	