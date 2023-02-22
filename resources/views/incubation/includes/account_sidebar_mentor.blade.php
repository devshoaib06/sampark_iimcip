@php
    $block1Arr = array(
       
        'administrator' => 'IIMCIP Posts'
    )


@endphp

<!-- 'public' => 'Public',-->
<div class="leftPanel pn-ProductNav_Wrapper">
    <nav id="pnProductNav" class="pn-ProductNav dragscroll">
        <div id="pnProductNavContents" class="pn-ProductNav_Contents">
            <a href="{{ route('front.mentor.startup') }}" aria-selected="true" @if(!isset($_GET['category']) && !isset($_GET['industry']) && !isset($_GET['post'])) aria-selected="true" @endif class="pn-ProductNav_Link">View All StartUps</a>
            <span id="pnIndicator" class="pn-ProductNav_Indicator"></span>
        </div>
    </nav>
</div>

<?php 
 if (Auth::check()) {
echo $user_type = Auth::user()->user_type;

 }
?>


<div class="leftPanel pn-ProductNav_Wrapper">
    <nav id="pnProductNav" class="pn-ProductNav dragscroll">
        <div id="pnProductNavContents" class="pn-ProductNav_Contents">
            <a href="{{ route('front.mentor.incubereports') }}" aria-selected="true" @if(!isset($_GET['category']) && !isset($_GET['industry']) && !isset($_GET['post'])) aria-selected="true" @endif class="pn-ProductNav_Link">Incubate Reoprt</a>
            <span id="pnIndicator" class="pn-ProductNav_Indicator"></span>
        </div>
    </nav>
</div>

