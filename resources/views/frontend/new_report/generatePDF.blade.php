<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo (isset($data['pdf_name']) && !empty($data['pdf_name'])) ? $data['pdf_name'] : "Incubate report"; ?></title>
</head>

<body>
<table widtd="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left">
      <p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>1) Company Name</strong>:  <?php if(isset($data['startupDetails']->name) && !empty($data['startupDetails']->name)){ echo $data['startupDetails']->name; } ?></p></td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>2) Company Profile</strong>: <?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?nl2br($data['startupInfoDetails']->summary_start_up):"" ?></p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>3) Other Information : </strong></p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>Company Website</strong></p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php if(isset($data['startupInfoDetails']->website) && !empty($data['startupInfoDetails']->website)){ echo $data['startupInfoDetails']->website; }else{ echo 'NA';  } ?></strong></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Date Of Incorporation</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails']->incorporation_date)) ?date('d-m-Y',strtotime($data['startupInfoDetails']->incorporation_date)):"" ?></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Start date of Incubation</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails']->incubation_start_date)) ?date('F Y',strtotime($data['startupInfoDetails']->incubation_start_date)):""; ?></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Registered Address</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupDetails']) && !empty($data['startupDetails'])) ?nl2br($data['startupDetails']->address):""; ?></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Operation Address</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?nl2br($data['startupInfoDetails']->operation_address):""; ?></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Legal Status of the Company</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->legal_status:""; ?></p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Other Registration Information</p></td>
        <td>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">PAN: <?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->pan:""; ?></em> </p>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">CIN:<?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->cin:""; ?> </p>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">GSTIN: <?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails'])) ?$data['startupInfoDetails']->gstin:""; ?> </p>
            </td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">IIMCIP Share holding Percentage</p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupInfoDetails']) && !empty($data['startupInfoDetails']->share_holding_percentage)) ?$data['startupInfoDetails']->share_holding_percentage:"0" ?>%</p></td>
      </tr>
      <tr>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Mentor Name </p></td>
        <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['startupDetails']) && !empty($data['startupDetails'])) ?$data['startupDetails']->investor_name:"" ?></p></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>4) Target &amp; Achievement</strong> :</p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Particular </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Volume( No Of Units) </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Sales in (Lakhs) </p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Annual Target FY <?php echo date('Y',strtotime($data['financial_year']));echo '-'.date('y',strtotime("+1 year", strtotime($data['financial_year']))); ?> </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_annual_target']:""; ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_annual_target']:""; ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Achieved till date </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_achived_till_date']:""; ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_achived_till_date']:""; ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Sales Revenue <?php echo date('Y',strtotime("-1 year", strtotime($data['financial_year']))); echo '-'.date('y',strtotime($data['financial_year'])); ?> </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['volume_sales_revenue']:""; ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['sales_sales_revenue']:""; ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Order Pipeline </p></td>
        <td valign="top" colspan="2" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTargetAchievementDetails']) && !empty($data['reportTargetAchievementDetails']))? $data['reportTargetAchievementDetails']['order_pipeline']:""; ?></p></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>5) Funding Status</strong> :</p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Sl No </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Particulars </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Remarks </p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">i) </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Funds from own sources (self, friends &amp; relatives): </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_fund_from_own_resources']:""; ?> </p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">ii) </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Funds raised from External Sources: Source and Amount </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_fund_form_external']:""; ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">iii) </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">IIMCIP Investment </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportFundingStatusDetails']) && !empty($data['reportFundingStatusDetails']))? $data['reportFundingStatusDetails']['remarks_iimcip_investment']:""; ?></p></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>6) Other Financial Information :</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Current Cash In Hand (Rs lacs)</p></td>
        <td valign="top" colspan="3" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php  if(isset($data['last_month_info']) && !empty($data['last_month_info'])){ echo $data['last_month_info']['cash_in_hand']+0; }?> </p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>Month</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong> Revenue (Rs lacs)</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong> Expense (Rs lacs)</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>Burn Rate (Rs lacs)</strong><strong> </strong></p></td>
      </tr>
      
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("-3 month",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prev_prev_InformationDetails']['revenue']) && count($data['prev_prev_prev_InformationDetails'])) { echo $data['prev_prev_prev_InformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prev_prev_InformationDetails']['expense']) && count($data['prev_prev_prev_InformationDetails'])) { echo $data['prev_prev_prev_InformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prev_prev_InformationDetails']['burn_rate']) && count($data['prev_prev_prev_InformationDetails'])) { echo $data['prev_prev_prev_InformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("-2 month",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prevInformationDetails']['revenue']) && count($data['prev_prevInformationDetails'])) { echo $data['prev_prevInformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prevInformationDetails']['expense']) && count($data['prev_prevInformationDetails'])) { echo $data['prev_prevInformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prev_prevInformationDetails']['burn_rate']) && count($data['prev_prevInformationDetails'])) { echo $data['prev_prevInformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("-1 month",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prevInformationDetails']['revenue']) && count($data['prevInformationDetails'])) { echo $data['prevInformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prevInformationDetails']['expense']) && count($data['prevInformationDetails'])) { echo $data['prevInformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['prevInformationDetails']['burn_rate']) && count($data['prevInformationDetails'])) { echo $data['prevInformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
      
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>Projected</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>&nbsp;</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>&nbsp;</strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong>&nbsp;</strong><strong> </strong></p></td>
      </tr>
      
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("+0",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['currentInformationDetails']['revenue']) && count($data['currentInformationDetails'])) { echo $data['currentInformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['currentInformationDetails']['expense']) && count($data['currentInformationDetails'])) { echo $data['currentInformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['currentInformationDetails']['burn_rate']) && count($data['currentInformationDetails'])) { echo $data['currentInformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("+1 month",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_monthInformationDetails']['revenue']) && count($data['next_monthInformationDetails'])) { echo $data['next_monthInformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_monthInformationDetails']['expense']) && count($data['next_monthInformationDetails'])) { echo $data['next_monthInformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_monthInformationDetails']['burn_rate']) && count($data['next_monthInformationDetails'])) { echo $data['next_monthInformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><strong><?php echo date("F'y",strtotime(date("Y-m", strtotime("+2 month",strtotime($data['last_month_info']['submit_date']))))); ?></strong><strong> </strong></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_next_monthInformationDetails']['revenue']) && count($data['next_next_monthInformationDetails'])) { echo $data['next_next_monthInformationDetails']['revenue']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_next_monthInformationDetails']['expense']) && count($data['next_next_monthInformationDetails'])) { echo $data['next_next_monthInformationDetails']['expense']+0; } ?></p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php if(isset($data['next_next_monthInformationDetails']['burn_rate']) && count($data['next_next_monthInformationDetails'])) { echo $data['next_next_monthInformationDetails']['burn_rate']+0; } ?></p></td>
      </tr>
    </table>    </td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>7) Team Details :</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Total No Employees: <?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['total_employee']:""; ?> </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Full Time: <?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['fulltime_employee']:""; ?> </p></td>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Part time: <?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['parttime_employee']:""; ?> </p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Founder / Co-Founder&#8217;s Name </p></td>
        <td valign="top" colspan="2" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['founder_name']:""; ?></p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Key Functionaries &amp; their role </p></td>
        <td valign="top" colspan="2" ><ol style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">
          <?php echo (isset($data['reportTeamDetails']) && !empty($data['reportTeamDetails']))? $data['reportTeamDetails']['role_function']:""; ?>
        </ol></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>8) Current Challenges :</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr> 
        <td widtd="597" valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Please mention tde problem areas / challenges faced currently and steps taken to mitigate tdese </p></td>
      </tr>
      <tr>
        <td widtd="597" valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['challenges']):""; ?></p></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>9) Progress &amp; Key Activities so far :</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt">Please mention the key achievements made / milestones reached by your business till date</p></td>
      </tr>
      <tr>
        <td valign="top" ><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['progress_key_activities']):""; ?></p></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>10) Any funding conversations had or are having and are you&#160;expecting any fund raiser in tde next six montds - if so estimate of how much&#160;, from whom&#160;</strong></p> </td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0" >
      <tr>
        <td valign="top" ><table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
        <tr>
        <td>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['funding_conversation']):""; ?></p></td></tr></table></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>11) How many months of runway they have and how you are planning to continue beyond that if funding does not happen</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" >
        <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
        <tr><td>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['planning']):""; ?></p></td></tr></table></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>12) Please mention your achievements / awards won</strong></p></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td valign="top" >
        <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
        <tr><td>
            <p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['awards_won']):""; ?></p></td></tr></table></td>
      </tr>
    </table>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>13) IIMCIP comments</strong></p></td>
  </tr>
  <tr>
      <td>
    <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
    <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['comments']):""; ?></p></td></tr></table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:12pt"><strong>14) IIMCIP support</strong></p></td>
  </tr>
  <tr>
    <td>
    <table width="100%" border="1" cellpadding="5" cellspacing="1" bordercolor="#000000">
    <tr>
    <td><p style="font-family:Arial, Helvetica, sans-serif; font-size:10pt"><?php echo (isset($data['last_month_info']) && !empty($data['last_month_info']))? nl2br($data['last_month_info']['support']):""; ?></p></td></tr></table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
