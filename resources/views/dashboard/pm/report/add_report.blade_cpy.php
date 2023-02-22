@extends ('layouts.admindashboard')
@section('page_heading','Add Report')
@section('section')

<div class="col-sm-12">
@if(Session::has('flash_message'))
    <div class="alert alert-success" style="text-align:center;">
        {{ Session::get('flash_message') }}
    </div>
@endif
    <div class="row">
        <div class="col-lg-12">
         {{ Form::open(array('url' => 'admin/save_report' , 'method' => 'post' , 'enctype' => 'multipart/form-data','id'=>'report_form')) }}
         <input type="hidden" name="startup_id" value="{{$data['startupDetails']->id}}">
         <?php 
            //echo strtotime(date('Y-m')).'<br>'.strtotime('2018-5').'<br>';
            $join_date = $data['join_date'];
            //echo date('Y-m',strtotime($join_date)).'<br>';
            $time = strtotime($join_date);
            $final = date("Y-m", strtotime("+1 month", $time));
            //echo $final;
            $todaysDate = date('Y-m-d');
            if( is_string( $join_date)) $join_date = date_create( $join_date);
            if( is_string( $todaysDate)) $todaysDate = date_create( $todaysDate);            
            $diff = date_diff( $join_date, $todaysDate);
            //echo $diff->format("%m Month")
         ?>
            <div class="form-group">
                <label>1) Company Name :</label><?php if(isset($data['startupDetails']->name) && !empty($data['startupDetails']->name)){ echo $data['startupDetails']->name; } ?>
                <!--<input type="text" class="form-control">-->
            </div>
            <div class="form-group">
                <label>2) Company Profile :</label>Magasool (meaning ‘yield’ in Tamil), is a technology service provider to small and
marginal farmers. They aim to introduce personalized interventions to increase agricultural yield,
decrease input costs, and value add to the end produce. Magasool Agro Pvt Ltd’s first service will be
procurement, processing and marketing of millet’s for tribal farmers of Javadimalai in Tamil Nadu.
Their intervention will increase farmer profits by 20% and supply high quality millet’s at affordable prices
to urban and semi-urban customers.
            </div>
            <div class="form-group">
                <label>3) Other Information :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Company Website</td><td><?php if(isset($data['startupInfoDetails']->website) && !empty($data['startupInfoDetails']->website)){ echo $data['startupInfoDetails']->website; }else{ echo 'NA';  } ?></td>
                    </tr>
                    <tr>
                        <td>Date Of Incorporation</td><td>07-12-2015</td>
                    </tr>
                    <tr>
                        <td>Start date of Incubation</td><td>August 2015</td>
                    </tr>
                    <tr>
                        <td>Registered Address</td>
                        <td>410 Ashok Heights, No. 11, 9th Cross,
                            Srikantheshwara Nagar, Mahalakshmi Layout,
                            Bangalore 96</td>
                    </tr>
                    <tr>
                        <td>Operation Address</td>
                        <td>483, Indira Gandhi Nagar, Pelasur Village and Post,
                        Polur Taluk, Thiruvannamalai District</td>
                    </tr>
                    <tr>
                        <td>Legal Status of the Company</td>
                        <td>Pvt Ltd Company</td>
                    </tr>
                    <tr>
                        <td>Other Registration Information</td>
                        <td>
                            <table class="table table-bordered">
                                <tr><td>PAN: AAJCM8527C</td></tr>
                                <tr><td>CIN:U01407KA2015PTC081656</td></tr>
                                <tr><td>GSTIN: 33AAJCM8527C1ZK</td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>IIMCIP Share holding Percentage</td>
                        <td>5%</td>
                    </tr>
                    <tr>
                        <td>Mentor Name &amp; Ownership Percentage</td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <label>4) Target &amp; Achievement :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Particular</td>
                        <td>Volume( No Of Units)</td>
                        <td>Sales in (Lakhs)</td>
                    </tr>
                    <tr>
                        <td>Annual Target FY <?php echo date('Y'); ?>-<?php echo date('y')+1; ?></td>
                        <td><input class="form-control" type="text" name="volume_annual_target" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_annual_target']:""; ?>"></td>
                        <td><input class="form-control" type="text" name="sales_annual_target" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_annual_target']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>Achieved till date</td>
                        <td><input class="form-control" type="text" name="volume_achived_till_date" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_achived_till_date']:""; ?>"></td>
                        <td><input class="form-control" type="text" name="sales_achived_till_date" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_achived_till_date']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>Sales Revenue <?php echo date('Y')-1; ?>-<?php echo date('y'); ?></td>
                        <td><input class="form-control" type="text" name="volume_sales_revenue" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_sales_revenue']:""; ?>"></td>
                        <td><input class="form-control" type="text" name="sales_sales_revenue" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_sales_revenue']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>Order Pipeline</td>
                        <td colspan="2"><input class="form-control" type="text" name="order_pipeline" value="<?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['order_pipeline']:""; ?>"></td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <label>5) Funding Status :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Sl No</td>
                        <td>Particulars</td>
                        <td>Remarks</td>
                    </tr>
                    <tr>
                        <td>i)</td>
                        <td>Funds from own sources (self,friends &amp; relatives)</td>
                        <td><input class="form-control" type="text" name="remarks_fund_from_own_resources"  value="<?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_fund_from_own_resources']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>ii)</td>
                        <td>Funds raised from External Sources: Source and Amount</td>
                        <td><input class="form-control" type="text" name="remarks_fund_form_external" value="<?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_fund_form_external']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>iii)</td>
                        <td>IIMCIP Investment</td>
                        <td><input class="form-control" type="text" name="remarks_iimcip_investment" value="<?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_iimcip_investment']:""; ?>"></td>
                    </tr>                 
                </table>
            </div>
            <div class="form-group">
                <label>6) Other Financial Information :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Current Cash In Hand</td>
                        <td colspan='3'>Rs 20,000/-</td>
                    </tr>
                    <tr>
                        <th>Month</th>
                        <th>Revenue</th>
                        <th>Expense</th>
                        <th>Burn rate</th>
                    </tr>
                    <?php 
                    $join_date = $data['join_date'];
                    $time = strtotime($join_date);
                    $final = date("Y-m", strtotime("+0 month", $time));
                    
                    ?>
                    <?php if(!empty($join_date) && (strtotime(date('Y-m'))>strtotime(date('Y-m',strtotime($join_date))))){
                        $i=0;
                        while((date("Y-m", strtotime("+".$i." month", $time)))<=(date("Y-m"))) {
                            if(strtotime(date("Y-m", strtotime("+3 month", strtotime(date('Y-00'))))) < strtotime(date("Y-m", strtotime("+".$i." month", $time)))){
                            if((date("Y-m", strtotime("+".$i." month", $time)))<(date("Y-m"))){ ?>
                            <tr>
                                <td><?php echo date("M y", strtotime("+".$i." month", $time)); ?></td>
                                <td>revenue</td>
                                <td>expense</td>
                                <td>burn rate</td>
                            </tr>     
                        <?php    }else{
                        ?>
                            <input type="hidden" name="editable_date" value="<?php echo date("Y-m-d", strtotime("+".$i." month", $time)); ?>">
                            <tr>
                                <td><?php echo date("M y", strtotime("+".$i." month", $time)); ?></td>
                                <td><input class="form-control" type="text" name="revenue"></td>
                                <td><input class="form-control" type="text" name="expenses"></td>
                                <td><input class="form-control" type="text" name="burn_rate"></td>
                            </tr>
                        <?php  }
                        }
                            $i++;  
                            
                        }
                    } ?>
                    
                </table>
            </div>
            <div class="form-group">
                <label>7) Team Details :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Total No Employees: <input class="form-control" type="text" name="total_employee" value="<?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['total_employee']:""; ?>"></td>
                        <td>Full Time: <input class="form-control" type="text" name="fulltime_employee" value="<?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['fulltime_employee']:""; ?>"></td>
                        <td>Part time: <input class="form-control" type="text" name="parttime_employee" value="<?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['parttime_employee']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>Founder / Co-Founder’s Name</td>
                        <td colspan="2"><input class="form-control" type="text" name="founder_name"  value="<?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['founder_name']:""; ?>"></td>
                    </tr>
                    <tr>
                        <td>Key Functionaries &amp; their role</td>
                        <td colspan="2">
                            <input class="form-control" type="text" name="role_function"  value="<?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['role_function']:""; ?>">
                        </td>
                    </tr>                                   
                </table>
            </div>
            <div class="form-group">
                <label>8) Current Challenges :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Please mention the problem areas / challenges faced currently and steps taken to mitigate these</td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" name="challenges" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['challenges']:""; ?>"></td>
                    </tr>                             
                </table>
            </div>
            <div class="form-group">
                <label>9) Progress &amp; Key Activities so far :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Please mention the key achievements made / milestones reached by your business till date</td>
                    </tr>
                    <tr>
                        <td><input class="form-control" type="text" name="progress_key_activities" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['progress_key_activities']:""; ?>"></td>
                    </tr>                             
                </table>
            </div>
            <div class="form-group">
                <label>10) Any funding conversations had or are having and are you expecting any fund raiser in the
next six months - if so estimate of how much , from whom</label>
                <table class="table table-bordered">
                    <tr>
                        <td><input class="form-control" type="text" name="funding_conversation" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['funding_conversation']:""; ?>"></td>
                    </tr>                            
                </table>
            </div>
            <div class="form-group">
                <label>11) How many months of runway they have and how you are planning to continue beyond that if
funding does not happen</label>
                <table class="table table-bordered">
                    <tr>
                        <td><input class="form-control" type="text" name="planning" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['planning']:""; ?>"></td>
                    </tr>                           
                </table>
            </div>
            <div class="form-group">
                <label>12)IIMCIP comments</label>
                <table class="table table-bordered">
                    <tr>
                        <td><input class="form-control" type="text" name="comments" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['comments']:""; ?>"></td>
                    </tr>                           
                </table>
            </div>
            <div class="form-group">
                <label>13) IIMCIP support</label>
                <table class="table table-bordered">
                    <tr>
                        <td><input class="form-control" type="text" name="support" value="<?php echo (isset($data['reportOtherDetails']) && !empty($data['reportOtherDetails']))? $data['reportOtherDetails']['support']:""; ?>"></td>
                    </tr>                          
                </table>
            </div>
	
           
            <button type="submit" class="btn btn-default">Draft</button>
            <!--<button type="button" name="final_save" value="1" class="btn btn-default" onclick="finalSave()">Save</button>-->
            
        {{ Form::close() }}
    </div>
    </div>
</div>
<script>
    function finalSave(){
        if(confirm('Are you sure to submit')){
            $('#report_form').submit();
        }else{
            
        }
    }
</script>		
@stop