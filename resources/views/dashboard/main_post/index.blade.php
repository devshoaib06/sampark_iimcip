@extends('dashboard.layouts.app')

@section('content_header')
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet"> -->
<style>

.postDetails .modal-content {
    border: 5px solid #3c8dbc;
}

.postDetails .modal-header {
    background-color: #3c8dbc;
    border-radius: 0px;
    padding: 10px;
    color: #ffffff;
}

.postTitle h3 {
    font-family: 'Source Sans Pro', sans-serif;
    font-size: 24px;
    font-weight: 600;
    margin-top: 10px;
    margin-bottom: 20px;
}

.postDetails p {
    font-size: 16px;
    margin: 15px 0px;
}

.postComment {
    margin-top: 5px;
}

.numComment {
    padding: 10px 0px;
    border-top: 1px solid #dddddd;
}

.numComment h4 {
    font-size: 20px;
    color: #464646;
}

.numComment h4 span {
    float: right;
    padding: 2px 10px;
    cursor: pointer;
}

.commentList ul {
    margin: 0px;
    padding: 0px;
}


/*
.commentList ul li ul {
    margin: 0px;
    padding: 0px;
    display: none;
}
*/

.replyList {
    display: none;
}

ul.active {
    display: block;
}

.commentList>ul>li {
    display: none;
    margin-top: 20px;
    cursor: pointer;
}

.commentWrap {
    display: flex;
}

.userImg img {
    width: 45px;
    height: auto;
    border: 4px solid #eaeaea;
}

.userCommemt {
    width: 100%;
    padding-left: 10px;
}

.userCommemt h4 {
    font-size: 16px;
    color: #3c8dbc;
    font-weight: 600;
    display: block;
    border-bottom: 1px solid #eaeaea;
    padding-bottom: 5px;
    margin-bottom: 0px;
}

.userCommemt h4 span {
    float: right;
    font-weight: 400;
    color: #464646;
    color: #a29f9f;
    font-size: 12px;
}

.userCommemt h4 small {
    display: inline-block;
    font-size: 12px;
    font-style: italic;
    color: #464646;
    font-weight: 400;
}

.userCommemt p {
    font-size: 14px;
    line-height: 18px;
    margin: 6px 0px;
}

.userCommemt>ul>li {
    display: inline-block !important;
    font-size: 10px;
    font-weight: 800;
    margin: 0px 10px 0px 0px !important;
    padding: 0px !important;
}

.userCommemt>ul>li a {
    color: #8e8e8e;
}

.userCommemt>ul>li a:hover {
    text-decoration: none;
    color: #757575;
}

.commentList ul li ul li {
    display: block;
    margin-top: 20px;
    margin-left: 50px;
}

.modal-content {
  white-space: initial;
}
</style>
<section class="content-header">
      <h1>
        All Topics/Q&A
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Topics/Q&A</li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
    
      <a href="{{ route('main_post.add') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Topic/Q&A</a>
    
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
  <form name="frmx" action="{{ route('main_post.blkAct') }}" method="post">
  {{ csrf_field() }}
    <div class="box-header with-border">
      


    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th style="width:30px;"></th>
            <th style="width:150px;">Action</th>
            <th style="width:100px;">Topics/Q&A</th>
            <th style="width:100px;">Added By</th>
            <th style="width:50px;">Type</th>
            <!-- <th style="width:100px;">Assigned To</th> -->
            <th style="width:100px;">Industry</th>
            <th style="width:100px;">Category</th>
            <th style="width:100px;">Reply</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($alldata))
          @php $sl = 1; @endphp
          
          
          @forelse($alldata as $v)
          <tr>
            <td>
              {{ $sl }}
              
            </td>
            <td>
                <a title="Edit" href="{{ route('main_post.edit', array('id' => $v->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
                <a title="Delete" href="{{ route('main_post.delete', array('id' => $v->id)) }}" onclick="return confirm('Are you sure want to Delete ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

                 @if($v->status == '1')
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=0&tab=main_posts"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($v->status == 0)
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=1&tab=main_posts"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
              
            </td>
            
            
            <?php
            if($v->updated_at == null || $v->updated_at == '0000-00-00'){
                $updated_at = '';
            }else{
               $updated_at = date('m-d-Y', strtotime($v->updated_at));
            }
            
            if($v->post_type=='1'){
                $post_type = 'Public';
            }elseif($v->post_type=='2'){
                $post_type = 'Private';
            }
            
            ?>
            
            <td><a onclick="javascript:postDetl(<?php echo $v->id ;?>);" href="javascript:void(0);">{{ $v->post_title }}</a></td>
           
            <td>{{ $v->contact_name }}</td>
            <td>{{ $post_type }} <br/>(@if(!empty($v->to_contact_name)) {{ $v->to_contact_name }}@else{{ "All" }}@endif)</td>
            
            <td><?php echo str_replace(',', '<br/>', $v->postIndustry);?></td>

            <td><?php 

            if(!empty($v->postCategory))
            {
               echo str_replace(',', '<br/>', $v->postCategory);

            }
            else
            {
               echo "N/A";
            }
           

            ?></td>
            <td>
              <!-- <a data-toggle="modal" data-target="#postDetails">{{ $v->replyCnt }}</a> -->
              <a onclick="javascript:commentDetl(<?php echo $v->id ;?>);" href="javascript:void(0);"><strong>{{ $v->replyCnt }} <?php if($v->replyCnt > 1){echo 'Replies';}else{echo 'Reply';} ?></strong></a>

              <div class="modal fade postDetl" id="postDetails_<?php echo $v->id;?>" tabindex="-1" role="dialog" aria-labelledby="postDetails" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                           <!--  <h5>About this post</h5> -->
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                        </div>
                        <div class="modal-body">
                          <div id="postdetail_<?php echo $v->id;?>">
                            <div class="postTitle">
                                <h3>{{ $v->post_title }} </h3>
                            </div>
                            <div class="postDetails">
                                
                                   <p> <?php echo html_entity_decode($v->post_info); ?> </p>
                                   
                               
                            </div>
                          </div>
                            <div class="postComment">
                                <div class="numComment" id="numComment_<?php echo $v->id;?>">
                                    <h4>{{ $v->replyCnt }} Comments <span><i class="fa fa-angle-down"></i></span></h4>
                                </div>
                                <div class="commentList" id="commentList_<?php echo $v->id ;?>">


                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              

            </td>
          </tr>
          @php $sl++; @endphp
          @empty
          @endforelse
        @endif
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </form>
  </div>

  
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
  // code for comment 

$(document).ready(function() {

   
    $('.numComment> h4> span').click(function() {
        $(this).find('i').toggleClass('fa fa-angle-down fa fa-angle-up');
        $('.commentList>ul>li').slideToggle().css('display', 'block');
    });
    $('.commentWrap').click(function() {
        $(this).next('.replyList').slideToggle();
        $(this).find('.downArrow>i').toggleClass('fa fa-angle-down fa fa-angle-up');
    });
});

  function postDetl(postId)
  {
    //alert(postId);
    

    $.ajax({
      type : "POST",
      url : "{{ route('main_post.comment') }}",
      data : {
        "postId" : postId,
        "_token" : "{{ csrf_token() }}"
      },
     
      success: function(scatJson) {
          //alert(scatJson);
          // return false;
         
         
         $('#postdetail_'+postId).css('display','');
         $('#numComment_'+postId).removeClass('numComment');
         $('#commentList_'+postId).html(scatJson);
         $('#postDetails_'+postId).modal('show');
      }
    });
  }

  function commentDetl(postId)
  {
    $.ajax({
      type : "POST",
      url : "{{ route('main_post.comment') }}",
      data : {
        "postId" : postId,
        "_token" : "{{ csrf_token() }}"
      },
     
      success: function(scatJson) {
          //alert(scatJson);
          // return false;

         
         $('#postdetail_'+postId).css('display','none');
         $('#numComment_'+postId).removeClass('numComment');
         $('#commentList_'+postId).html(scatJson);
         $('#postDetails_'+postId).modal('show');
      }
    });
  }

  function getReply(commnetId){
    // $.ajax({
    //   type : "POST",
    //   url : "{{ route('main_post.reply') }}",
    //   data : {
    //     "commnetId" : commnetId,
    //     "_token" : "{{ csrf_token() }}"
    //   },
    //   success: function(scatJson) {
    //      $('#reply_'+commnetId).html(scatJson);
    //   }
    // });
    //alert(commnetId);
    //$('.replyList').css('display','block'); 
    $('.replyList').css('display','none');
    $('.rep_'+commnetId).css('display','block');
  }
$(function() {
  $('.ar-datatable').DataTable({
    "scrollX": true,
    "columnDefs": [ {
      "targets": [ 1, 2, 3 ],
      "orderable": false
    } ]
  });
});
$( function() {
  $("#ckAll").on('click',function(){
    var isCK = $(this).is(':checked');
    if(isCK == true){
      $('.ckbs').prop('checked', true);
      $('#delAll').removeAttr('disabled');
    }
    if(isCK == false){
      $('.ckbs').prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    colMark();
    $('#delAll').val('Delete Selected');
  });
  $(".ckbs").on('click', function(){
    var c = 0;
    $(".ckbs").each(function(){
      colMark();
      if($(this).is(':checked')){
        c++;
      }
    });
    if(c == 0){
      $("#ckAll").prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    if(c > 0){
      $("#ckAll").prop('checked',true);
      $('#delAll').removeAttr('disabled');
    }
    $('#delAll').val('Delete Selected ('+c+')');
  });
} );
function colMark() {
  $( '.ckbs' ).each(function() {
    if($(this).is(':checked')) {
      $(this).parents('tr').css('background-color', '#ffe6e6');
    } else {
      $(this).parents('tr').removeAttr('style');
    }
  });
}
</script>
@endpush