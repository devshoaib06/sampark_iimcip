 <?php if(!empty($alldata)){

                                                //dd($alldata);

                                                foreach($alldata as $r){

                                                  ?>
                                            <ul class="replyList">
                                                <li>
                                                    <div class="commentWrap">
                                                        
                                                        <div class="userCommemt">
                                                            <h4><?php echo  $r->contact_name ?> <small> <?php echo  date('d-m-Y',strtotime($r->created_at)) ;?></small> </h4>
                                                            <p>
                                                                <?php echo  $r->reply_text ;?>
                                                            </p>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                </li>
                                            </ul>

                                          <?php }
                                        } ?>