@extends ('layouts.admindashboard')
@section('page_heading','Add Incubation Report')
@section('section')
<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
<div class="col-sm-12">
@if(Session::has('flash_message'))
    <div class="alert alert-success" style="text-align:center;">
        {{ Session::get('flash_message') }}
    </div>
@endif
    <div class="row">
        <div class="col-lg-12">
         {{ Form::open(array('url' => 'admin/update_report' , 'method' => 'post' , 'enctype' => 'multipart/form-data','id'=>'report_form')) }}
         <input type="hidden" name="master_id" value="{{$data['master_id']}}">
         
            <div class="form-group">
                <label>1) Company Name :</label><?php if(isset($data['startupDetails']->name) && !empty($data['startupDetails']->name)){ echo $data['startupDetails']->name; } ?>
                <!--<input type="text" class="form-control">-->
            </div>
            <div class="form-group">
                <label>2) Company Profile :</label><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?nl2br($data['startupInfoDetails']->summary_start_up):"" ?>
            </div>
            <div class="form-group">
                <label>3) Other Information :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Company Website</td><td><?php if(isset($data['startupInfoDetails']->website) && !empty($data['startupInfoDetails']->website)){ echo $data['startupInfoDetails']->website; }else{ echo 'NA';  } ?></td>
                    </tr>
                    <tr>
                        <td>Date Of Incorporation</td><td><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails']->incorporation_date)) ?date('d-m-Y',strtotime($data['startupInfoDetails']->incorporation_date)):"" ?></td>
                    </tr>
                    <tr>
                        <td>Start date of Incubation</td><td><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails']->incubation_start_date)) ?date('F Y',strtotime($data['startupInfoDetails']->incubation_start_date)):""; ?></td>
                    </tr>
                    <tr>
                        <td>Registered Address</td>
                        <td><?php echo (isset($data['startupDetails']) && !empty($data['startupDetails'])) ?nl2br($data['startupDetails']->address):""; ?></td>
                    </tr>
                    <tr>
                        <td>Operation Address</td>
                        <td><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?nl2br($data['startupInfoDetails']->operation_address):""; ?></td>
                    </tr>
                    <tr>
                        <td>Legal Status of the Company</td>
                        <td><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->legal_status:""; ?></td>
                    </tr>
                    <tr>
                        <td>Other Registration Information</td>
                        <td>
                            <table class="table table-bordered">
                                <tr><td>PAN: <?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->pan:""; ?></td></tr>
                                <tr><td>CIN:<?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->cin:""; ?></td></tr>
                                <tr><td>GSTIN: <?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->gstin:""; ?></td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>IIMCIP Share holding Percentage</td>
                        <td><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->share_holding_percentage:"" ?>%</td>
                    </tr>
                    <tr>
                        <td>Mentor Name </td>
                        <td><?php echo (isset($data['startupDetails']) && !empty($data['startupDetails'])) ?$data['startupDetails']->investor_name:"" ?></td>
                    </tr>
                </table>
            </div>
            <div class="form-group">
                <label>4) Target &amp; Achievement :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Particulars</td>
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
         <?php #echo '<pre>'; print_r($data['reportFinancialInformationDetails']); echo '</pre>'; ?>
            <div class="form-group">
                <label>6) Other Financial Information :</label>
                <table class="table table-bordered">
                    <tr>
                        <td>Current Cash In Hand as on date(Rs lacs)</td>
                        <td colspan='3'><input class="form-control-custom" type="text" name="cash_in_hand" value="<?php  if(isset($data['last_month_info']) && !empty($data['last_month_info'])){ echo $data['last_month_info']['cash_in_hand']+0; }?>"></td>
                    </tr>
                    <tr>
                        <th>Month</th>
                        <th>Revenue (Rs lacs)</th>
                        <th>ExpenseExpense (Rs lacs)</th>
                        <th>Burn Rate (Rs lacs)</th>
                    </tr>                    
                    
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("-3 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                            <input type="hidden" name="id_revenue_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['id']; ?>">
                            <input type="hidden" name="date_revenue_3" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("-3 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        </td>
                        <td> 
                            <?php if(isset($data['prev_prev_prev_InformationDetails']['revenue']) && count($data['prev_prev_prev_InformationDetails'])) {
                                if($data['last_month_info']['created_at']==$data['prev_prev_prev_InformationDetails']['created_at']){ ?>
                                    <input class="form-control-custom" type="text" id="revenue_3" name="revenue_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['revenue']+0; ?>">
                                <?php }else{
                                echo $data['prev_prev_prev_InformationDetails']['revenue']+0; ?>
                            <input class="form-control-custom" type="hidden" id="revenue_3" name="revenue_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['revenue']; ?>">
                            <?php }}else{ ?>
                            <input class="form-control-custom" type="number" id="revenue_3" name="revenue_3" step="any" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prev_prev_prev_InformationDetails']['expense']) && count($data['prev_prev_prev_InformationDetails'])) {
                                if($data['last_month_info']['created_at']==$data['prev_prev_prev_InformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="expense_3" name="expense_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['expense']+0; ?>">
                                <?php }else{ echo $data['prev_prev_prev_InformationDetails']['expense']+0; ?>
                                <input class="form-control-custom" type="hidden" id="expense_3" name="expense_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['expense']; ?>">
                                <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="expense_3" name="expense_3" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prev_prev_prev_InformationDetails']['burn_rate']) && count($data['prev_prev_prev_InformationDetails'])) { 
                                if($data['last_month_info']['created_at']==$data['prev_prev_prev_InformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="burn_rate_3" name="burn_rate_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['burn_rate']+0; ?>">
                                <?php }else{echo $data['prev_prev_prev_InformationDetails']['burn_rate']+0;  ?>
                            <input class="form-control-custom" type="hidden" id="burn_rate_3" name="burn_rate_3" value="<?php echo $data['prev_prev_prev_InformationDetails']['burn_rate']; ?>">
                            <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="burn_rate_3" name="burn_rate_3" value="">
                            <?php } ?>
                            </td>
                    </tr>
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("-2 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                        <input type="hidden" name="id_revenue_2" value="<?php echo $data['prev_prevInformationDetails']['id']; ?>">
                        <input type="hidden" name="date_revenue_2" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("-2 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        </td>
                        <td> 
                            <?php if(isset($data['prev_prevInformationDetails']['revenue']) && count($data['prev_prevInformationDetails'])) {
                                if($data['last_month_info']['created_at']==$data['prev_prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="revenue_2" name="revenue_2" value="<?php echo $data['prev_prevInformationDetails']['expense']+0; ?>">
                                <?php }else{ echo $data['prev_prevInformationDetails']['revenue']+0; ?>
                                <input class="form-control-custom" type="hidden" id="revenue_2" name="revenue_2" value="<?php echo $data['prev_prevInformationDetails']['expense']; ?>">
                            <?php }} else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="revenue_2" name="revenue_2" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prev_prevInformationDetails']['expense']) && count($data['prev_prevInformationDetails'])) {
                                if($data['last_month_info']['created_at']==$data['prev_prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="expense_2" name="expense_2" value="<?php echo $data['prev_prevInformationDetails']['expense']+0; ?>">
                                <?php }else{ echo $data['prev_prevInformationDetails']['expense']+0;  ?>
                            <input class="form-control-custom" type="hidden" id="expense_2" name="expense_2" value="<?php echo $data['prev_prevInformationDetails']['expense']; ?>">
                            <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="expense_2" name="expense_2" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prev_prevInformationDetails']['burn_rate']) && count($data['prev_prevInformationDetails'])) { 
                                if($data['last_month_info']['created_at']==$data['prev_prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="burn_rate_2" name="burn_rate_2" value="<?php echo $data['prev_prevInformationDetails']['burn_rate']+0; ?>">
                                <?php }else{ echo $data['prev_prevInformationDetails']['burn_rate']+0;  ?>
                            <input class="form-control-custom" type="hidden" id="burn_rate_2" name="burn_rate_2" value="<?php echo $data['prev_prevInformationDetails']['burn_rate']; ?>">
                            <?php }} else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="burn_rate_2" name="burn_rate_2" value="">
                            <?php } ?>
                            </td>
                    </tr>
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("-1 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                        <input type="hidden" name="id_revenue_1" value="<?php echo $data['prevInformationDetails']['id']; ?>">
                        <input type="hidden" name="date_revenue_1" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("-1 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        </td>
                        <td> 
                            <?php if(isset($data['prevInformationDetails']['revenue']) && count($data['prevInformationDetails'])) { 
                                if($data['last_month_info']['created_at']==$data['prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="revenue_1" name="revenue_1" value="<?php echo $data['prevInformationDetails']['revenue']+0; ?>">
                                <?php }else{ echo $data['prevInformationDetails']['revenue']+0; ?>
                             <input class="form-control-custom" type="hidden" id="revenue_1" name="revenue_1" value="<?php echo $data['prevInformationDetails']['revenue']; ?>">
                            <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="revenue_1" name="revenue_1" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prevInformationDetails']['expense']) && count($data['prevInformationDetails'])) { 
                                 if($data['last_month_info']['created_at']==$data['prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="expense_1" name="expense_1" value="<?php echo $data['prevInformationDetails']['expense']+0; ?>">
                                <?php }else{ echo $data['prevInformationDetails']['expense']+0; ?>
                            <input class="form-control-custom" type="hidden" id="expense_1" name="expense_1" value="<?php echo $data['prevInformationDetails']['expense']; ?>">
                            <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="expense_1" name="expense_1" value="">
                            <?php } ?>
                            </td>
                        <td> 
                            <?php if(isset($data['prevInformationDetails']['burn_rate']) && count($data['prevInformationDetails'])) { 
                                if($data['last_month_info']['created_at']==$data['prevInformationDetails']['created_at']){ ?>
                            <input class="form-control-custom" type="text" id="burn_rate_1" name="burn_rate_1" value="<?php echo $data['prevInformationDetails']['burn_rate']+0; ?>">
                                <?php }else{ echo $data['prevInformationDetails']['burn_rate']+0; ?>
                            <input class="form-control-custom" type="hidden" id="burn_rate_1" name="burn_rate_1" value="<?php echo $data['prevInformationDetails']['burn_rate']; ?>">
                                <?php }}else{ ?>
                            <input class="form-control-custom" type="number" step="any" id="burn_rate_1" name="burn_rate_1" value="">
                            <?php } ?>
                            </td>
                    </tr>
                    
                    
                    <tr>
                        <th>Projected</th>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                    
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("+0 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                        <input type="hidden" name="id_revenue_plan_0" value="<?php echo $data['currentInformationDetails']['id']; ?>">
                        <input type="hidden" name="date_revenue_plan_0" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("+0 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        
                        </td>
                        <td><input class="form-control-custom" type="number" step="any" id="revenue_plan_0" name="revenue_plan_0" value="<?php echo (isset($data['currentInformationDetails']) && !empty($data['currentInformationDetails'])) ? $data['currentInformationDetails']['revenue']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="expense_plan_0" name="expense_plan_0" value="<?php echo (isset($data['currentInformationDetails']) && !empty($data['currentInformationDetails'])) ? $data['currentInformationDetails']['expense']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="burn_rate_plan_0" name="burn_rate_plan_0" value="<?php echo (isset($data['currentInformationDetails']) && !empty($data['currentInformationDetails'])) ? $data['currentInformationDetails']['burn_rate']+0 : ''; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("+1 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                        <input type="hidden" name="id_revenue_plan_1" value="<?php echo $data['next_monthInformationDetails']['id']; ?>">
                        <input type="hidden" name="date_revenue_plan_1" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("+1 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        </td>
                        <td><input class="form-control-custom" type="number" step="any" id="revenue_plan_1" name="revenue_plan_1" value="<?php echo (isset($data['next_monthInformationDetails']) && !empty($data['next_monthInformationDetails'])) ? $data['next_monthInformationDetails']['revenue']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="expense_plan_1" name="expense_plan_1" value="<?php echo (isset($data['next_monthInformationDetails']) && !empty($data['next_monthInformationDetails'])) ? $data['next_monthInformationDetails']['expense']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="burn_rate_plan_1" name="burn_rate_plan_1" value="<?php echo (isset($data['next_monthInformationDetails']) && !empty($data['next_monthInformationDetails'])) ? $data['next_monthInformationDetails']['burn_rate']+0 : ''; ?>"></td>
                    </tr>
                    <tr>
                        <td><?php echo date("F'y",strtotime(date("Y-m", strtotime("+2 month",strtotime($data['last_month_info']['submit_date']))))); ?>
                        <input type="hidden" name="id_revenue_plan_2" value="<?php echo $data['next_next_monthInformationDetails']['id']; ?>">
                        <input type="hidden" name="date_revenue_plan_2" value="<?php echo date("Y-m-d",strtotime(date("Y-m", strtotime("+2 month",strtotime($data['last_month_info']['submit_date']))))); ?>">
                        </td>
                        <td><input class="form-control-custom" type="number" step="any" id="revenue_plan_2" name="revenue_plan_2" value="<?php echo (isset($data['next_next_monthInformationDetails']) && !empty($data['next_next_monthInformationDetails'])) ? $data['next_next_monthInformationDetails']['revenue']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="expense_plan_2" name="expense_plan_2" value="<?php echo (isset($data['next_next_monthInformationDetails']) && !empty($data['next_next_monthInformationDetails'])) ? $data['next_next_monthInformationDetails']['expense']+0 : ''; ?>"></td>
                        <td><input class="form-control-custom" type="number" step="any" id="burn_rate_plan_2" name="burn_rate_plan_2" value="<?php echo (isset($data['next_next_monthInformationDetails']) && !empty($data['next_next_monthInformationDetails'])) ? $data['next_next_monthInformationDetails']['burn_rate']+0 : ''; ?>"></td>
                    </tr>
                   
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
                        <td><textarea rows="5" class="form-control" name="challenges"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['challenges']:""; ?></textarea>
                            
                        </td>
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
                        <td>
                            <textarea rows="5" class="form-control" name="progress_key_activities"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['progress_key_activities']:""; ?></textarea>
                            
                        </td>
                    </tr>                             
                </table>
            </div>
            <div class="form-group">
                <label>10) Any funding conversations had or are having and are you expecting any fund raiser in the
next six months - if so estimate of how much , from whom</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <textarea rows="5" class="form-control" name="funding_conversation"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['funding_conversation']:""; ?></textarea>
                        </td>
                    </tr>                            
                </table>
            </div>
            <div class="form-group">
                <label>11) How many months of runway they have and how you are planning to continue beyond that if
funding does not happen</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <textarea rows="5" class="form-control" name="planning" ><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['planning']:""; ?></textarea>
                            
                        </td>
                    </tr>                           
                </table>
            </div>
            <div class="form-group">
                <label>12) Please mention your achievements / awards won</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <textarea rows="5" class="form-control" name="awards_won" ><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['awards_won']:""; ?></textarea>
                            
                        </td>
                    </tr>                           
                </table>
            </div>
            
            <div class="form-group">
                <label>13) Type</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <select class="form-control" name="type">
                                <option <?php echo (isset($data['last_month_info']['incubate_status']) && $data['last_month_info']['type']=='Physical')? "selected":""; ?> value="Physical">Physical</option>
                                <option <?php echo (isset($data['last_month_info']['incubate_status']) && $data['last_month_info']['type']=='Virtual')? "selected":""; ?> value="Virtual">Virtual</option>
                            </select>
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>14) Technology</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="technology" value="<?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['technology']:""; ?>">
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>15) Date of disbursement of IIMCIP seed fund</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input class="form-control form_date" placeholder="Date of disbursement" data-date-format="yyyy-mm-dd" data-link-format="yyyy-mm-dd" name="dateof_disbursement" type="text" value="<?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? date('Y-m-d',strtotime($data['last_month_info']['dateof_disbursement'])):""; ?>">
                            
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>16) Share transfer status</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="share_transfer_status" value="<?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['share_transfer_status']:""; ?>">
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>17) No of shares transferred for incubation support</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="share_transfer_count" value="<?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['share_transfer_count']:""; ?>">
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>18) Additional shares </label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input class="form-control" type="text" name="additional_share" value="<?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['additional_share']:""; ?>">
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>19) IIMCIP support</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <textarea rows="5" class="form-control"  name="support"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['support']:""; ?></textarea>
                           
                        </td>
                    </tr>                          
                </table>
            </div>
            <div class="form-group">
                <label>20) IIMCIP comments</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <textarea rows="5" class="form-control" name="comments"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['comments']:""; ?></textarea>
                            
                        </td>
                    </tr>                           
                </table>
            </div>
         <style type="text/css">
/*        option.red {background-color: #FF0000; font-weight: bold; font-size: 12px;}
        option.yellow {background-color: #FFFF00;}
        option.green {background-color: #008000;}*/
        </style>
            <div class="form-group">
                <label>21) Status</label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <select class="form-control" name="incubate_status">
                                <option class="green" <?php echo (isset($data['last_month_info']['incubate_status']) && $data['last_month_info']['incubate_status']=='008000')? "selected":""; ?> value="008000">Good</option>
                                <option class="yellow" <?php echo (isset($data['last_month_info']['incubate_status']) && $data['last_month_info']['incubate_status']=='FFFF00')? "selected":""; ?> value="FFFF00">Average</option>
                                <option class="red" <?php echo (isset($data['last_month_info']['incubate_status']) && $data['last_month_info']['incubate_status']=='FF0000')? "selected":""; ?> value="FF0000">Poor</option>
                            </select>
                            <!--<input class="form-control" type="text" name="incubate_status" value="<?php //echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? $data['last_month_info']['incubate_status']:""; ?>">-->
                            
                        </td>
                    </tr>                           
                </table>
            </div>
            <div class="form-group">
                <label>22) Investible status </label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <select class="form-control" name="investible_status">
                                <option <?php echo (isset($data['last_month_info']['investible_status']) && $data['last_month_info']['investible_status']=='008000')? "selected":""; ?> value="008000">Good</option>
                                <option <?php echo (isset($data['last_month_info']['investible_status']) && $data['last_month_info']['investible_status']=='FFFF00')? "selected":""; ?> value="FFFF00">Average</option>
                                <option <?php echo (isset($data['last_month_info']['investible_status']) && $data['last_month_info']['investible_status']=='FF0000')? "selected":""; ?> value="FF0000">Poor</option>
                            </select>
                            
                            
                        </td>
                    </tr>                           
                </table>
            </div>
            <div class="form-group">
                <label>23) Suggested type of fund </label>
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <select class="form-control" name="fund_type">
                                <option <?php echo (isset($data['last_month_info']['fund_type']) && $data['last_month_info']['fund_type']=='Equity')? "selected":""; ?> value="Equity">Equity</option>
                                <option <?php echo (isset($data['last_month_info']['fund_type']) && $data['last_month_info']['fund_type']=='Soft loan')? "selected":""; ?> value="Soft loan">Soft loan</option>
                                <option <?php echo (isset($data['last_month_info']['fund_type']) && $data['last_month_info']['fund_type']=='Grant')? "selected":""; ?> value="Grant">Grant</option>
                            </select>
                            
                            
                        </td>
                    </tr>                           
                </table>
            </div>
           <div class="form-group">
                <label>24) Save As</label>
                <table class="table table-bordered">
                    <tr>
                        <td><input type="radio" id="is_draft" name="is_draft" value="1" checked="checked">Draft</td>
                        <td><input type="radio" id="is_final" name="is_draft" value="0" >Final</td>
                    </tr>                          
                </table>
            </div>
	
           
            <button type="submit" class="btn btn-default">Submit</button>
            <?php if(isset($data['startupDetails']) && !empty($data['startupDetails']->id)){  ?>
            <!--<a class="btn btn-small btn-info" href="{{ URL::to('admin/list_report/'.$data['startupDetails']->id) }}">Back</a>-->
            <?php } ?>
            <!--<button type="button" name="final_save" value="1" class="btn btn-default" onclick="finalSave()">Save</button>-->
            
        {{ Form::close() }}
    </div>
    </div>
</div>
<script src="{{asset('public/datepicker/js/bootstrap.min.js')}}"></script>        
<script src="{{asset('public/datepicker/js/bootstrap-datetimepicker.js')}}" charset="UTF-8"></script>  
<script type="text/javascript">
	$('.form_date').datetimepicker({
        //language:  'en',
        minDate:new Date(),
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
</script>
<script>
    $("#revenue_plan_0,#expense_plan_0,#burn_rate_plan_0").keyup(function(){
            var a = $("#revenue_plan_0").val();
            var b = $("#expense_plan_0").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_plan_0").val(total);
        });
        $("#revenue_plan_1,#expense_plan_1,#burn_rate_plan_1").keyup(function(){
            var a = $("#revenue_plan_1").val();
            var b = $("#expense_plan_1").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_plan_1").val(total);
        });
        $("#revenue_plan_2,#expense_plan_2,#burn_rate_plan_2").keyup(function(){
            var a = $("#revenue_plan_2").val();
            var b = $("#expense_plan_2").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_plan_2").val(total);
        });
        $("#revenue_3,#expense_3,#burn_rate_3").keyup(function(){
            var a = $("#revenue_3").val();
            var b = $("#expense_3").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_3").val(total);
        });
        $("#revenue_2,#expense_2,#burn_rate_2").keyup(function(){
            var a = $("#revenue_2").val();
            var b = $("#expense_2").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_2").val(total);
        });
        $("#revenue_1,#expense_1,#burn_rate_1").keyup(function(){
            var a = $("#revenue_1").val();
            var b = $("#expense_1").val();
            var sum = parseFloat(b) - parseFloat(a);
            var total = parseFloat(sum,6) + parseFloat(0);
            $("#burn_rate_1").val(total);
        });
    $("#is_final").click(function(){
            var confirmation = confirm('Do you want to final submit? No change will available once you finally submitted.');
            if(confirmation){
                $("#is_final").prop("checked", true);
                $("#is_draft").prop("checked", false);
            }else{
               $("#is_draft").prop("checked", true);
               $("#is_final").prop("checked", false);
            }
        });
</script>		
@stop