@extends('incubation.layouts.app')
@section('content')
    <div class="listing-block">
        <div class="listing-block-heading">
            <h3>{{ Auth::user()->user_type == 6 ? 'MY MENTEES' : 'MY INCUBATEES' }}</h3>
            {{-- <h4>Over {{$incubatees->get_mentor_name_count}} members</h4> --}}
        </div>
        <div class="clearfix"></div>
        <div class="listing-block-from">
            <div class="left-block block1">SI#</div>
            <div class="left-block block2">Logo</div>
            <div class="left-block block3">Organization Name</div>
            {{-- <div class="left-block block4">Organization Type</div> --}}
            <div class="left-block block5">{{ Auth()->user()->user_type == 6 ? 'Mentee' : 'Mentor' }} Name

            </div>
            <div class="left-block block6">Service Location</div>
            <div class="left-block block8">Programme</div>
            <div class="left-block block7">Action</div>
            <div class="clearfix"></div>
        </div>
        @php $i = 1; @endphp
        @foreach ($mentorList as $list)
            @if ($list->member_company != '')
                {{-- {{ dd($list->getCompanyType->company_type) }} --}}
                <div class="listing-block-inner-from">
                    <div class="left-block block-inner1">{{ $i++ }}</div>
                    <div class="left-block block-inner2">

                        @if ($list->image == '')
                            <img src="{{ asset('public/images/dummy_logo.png') }}" width="50" align="left">
                        @else
                            <img src="{{ asset('public/uploads/user_images/thumb') }}/{{ $list->image }}" width="50"
                                align="left">
                        @endif


                    </div>
                    <div class="left-block block-inner3">

                        {{ $list->member_company?$list->member_company: 'NA' }}
                    </div>


                    
                    <div class="left-block block-inner5">
                        @forelse ($list->getMentor as $menKey=>$mentor)
                            {{-- @if ()
                                
                            @endif --}}
                            {{ $mentor->first_name }} {{ $mentor->last_name }} {{ $menKey > 0 ? ',' : 'NA'  }}

                        @empty
                            NA
                        @endforelse
                    </div>
                    <div class="left-block block-inner6">
                        @forelse ($list->getServiceLocation as $locKey=>$loc)
                            {{ $loc->name }} {{ $locKey > 0 ? ',' : '' }}

                        @empty
                            NA
                        @endforelse
                    </div>
                    <div class="left-block block-inner8">
                        @forelse ($list->getProgramme as $progKey=>$prog)
                            {{ $prog->name }} {{ $progKey > 0 ? ',' : '' }}

                        @empty
                            NA
                        @endforelse
                    </div>
                    <div class="left-block block-inner7">
                        <a href="{{ route('diagnosticsList', ['startUpId' => $list->id]) }}" data-toggle="tooltip"
                            data-placement="bottom" data-original-title="Diagnostics ">

                            <img src="{{ asset('public/images/diagnostics.png') }}" width="25" alt=""
                                srcset="">

                        </a>
                        <a href="{{ route('startup.view', ['startUpId' => $list->id]) }}" target="_blank"
                            data-toggle="tooltip" data-placement="bottom" data-original-title="View Details ">

                            <img src="{{ asset('public/images/details.png') }}" width="25" alt=""
                                srcset="">

                        </a>

                        <a href="{{ route('startup.chart', ['startUpId' => $list->id]) }}" data-toggle="tooltip"
                            data-placement="bottom" data-original-title="Reports">
                            <img src="{{ asset('public/images/reports.png') }}" width="25" alt=""
                                srcset="">
                        </a>
                        @if (Auth::user()->user_type == 4)
                            <a onclick="javascript:memberAssign({{ $list->id }});" href="javascript:void(0);"
                                data-toggle="tooltip" data-placement="bottom" data-original-title="Assign Mentor">
                                <i class="fa fa-2x fa fa-plus"></i>
                            </a>
                        @endif


                        <!-- Modal -->
                        <div class="modal fade" id="postDetails_{{ $list->id }}" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">

                                <div class="modal-content" style="min-height:400px;">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Assign Mentor</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group col-md-8">
                                            <label>Mentors : </label>
                                            <select name="mentor_member" id="mentor_member_{{ $list->id }}"
                                                class="form-control select2" autocomplete="off" style="width: 300px">
                                                @foreach ($memberList as $member)
                                                    <option value="{{ $member->id }}">{{ $member->first_name }}
                                                        {{ $member->last_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" onclick="javascript:assignAjax({{ $list->id }});"
                                            class="btn btn-primary">Assign</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                    </div>
                    <div class="clearfix"></div>
                </div>
            @endif
        @endforeach



    </div>
@endsection

@push('page_js')
    <script>
        function memberAssign(memberID) {
            $('#postDetails_' + memberID).modal('show');
        }

        function assignAjax(memberID) {
            var startupID = memberID;
            var mentorID = $('#mentor_member_' + memberID).find(":selected").val();
            //alert(startupID);
            // alert(mentorID);
            $.ajax({
                type: "POST",
                url: "{{ route('startup_mentor') }}",
                data: {
                    "startupID": startupID,
                    "mentorID": mentorID,
                    "_token": "{{ csrf_token() }}"
                },

                success: function(scatJson) {
                    alert("Mentor Assigned");
                    window.location.href = window.location.href;
                }
            });

        }
    </script>
@endpush
