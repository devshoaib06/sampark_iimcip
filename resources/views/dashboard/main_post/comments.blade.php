<?php  
//dd($alldata);        

                 if(!empty($alldata)){
                             foreach($alldata as $c){
                                 ?>
                                    <ul>
                                        <li>
                                            <div class="commentWrap">
                                                
                                                <div class="userCommemt">
                                                    <h4><?php echo  $c->contact_name;?> <small><?php echo  date('d-m-Y',strtotime($c->created_at));?></small> <span class="downArrow" onclick="getReply(<?php echo  $c->id;?>)"><i class="fa fa-angle-down" ></i></span></h4>
                                                    
                                                    <div class="attachImg-wrap">
                                                    <p>
                                                        <?php echo $c->reply_text; ?>

                                                     <br />

                                                        
                                                        <?php if(!empty($c->images)){

                                                

                                                        foreach($c->images as $im){

                                                             $postImage = asset('public/uploads/posts/images/'. $im->media_path);

                                                          ?>

                                                          <div class="attached-img">
                                                                <a class="fancybox" rel="group" href="<?php echo  $postImage;?>"><img src="<?php echo  $postImage;?>" width="100" height="100"  class="hover-shadow" /></a>
                                                            </div>

                                                          <?php }
                                                         } ?>

                                                         @if(!empty($c->video_url))
                                                            <iframe width="140" height="70" src={{ $c->video_url }} frameborder="0" allowfullscreen></iframe>

                                                        @endif
                                                    </p>
                                                    
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                            <!-- <div id="reply_<?php echo  $c->id;?>"></div> -->

                                            <?php if(!empty($c->reply)){

                                                //dd($c->reply);

                                                foreach($c->reply as $r){

                                                  ?>
                                            <ul class="replyList rep_<?php echo  $c->id;?>">
                                                <li>
                                                    <div class="commentWrap">
                                                        
                                                        <div class="userCommemt">
                                                            <h4><?php  $r->contact_name ;?><small> <?php echo date('d-m-Y',strtotime($r->created_at));?></small> </h4>
                                                            <p>
                                                                <?php echo  $r->reply_text ;?>


                                                            </p>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                </li>
                                            </ul>

                                          <?php }
                                        } ?>
                                        </li>
                                    </ul>

                                  <?php }
                                }
                                ?>

