<?php 
if(isset($excelData) && count($excelData)>0){
    

  // file name for download
$filename = "IncubateDetailsReport" . date('Y-m-d') . ".xls";

header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: application/vnd.ms-excel");

$flag = false;
$i = 1;
    foreach($excelData as $row) {
        $row = (array)$row;
        //echo '<pre>';
        $allRelatedData = getMasterData($row['id'],$month,$year);
        
        $master_data  = (isset($allRelatedData['master_data']) && count($allRelatedData['master_data'])>0)? $allRelatedData['master_data'][0]:"";
        $type  = (isset($master_data->type) && !empty($master_data->type))? $master_data->type:"";
        $technology  = (isset($master_data->technology) && !empty($master_data->technology))? $master_data->technology:"";
        $dateof_disbursement  = (isset($master_data->dateof_disbursement) && !empty($master_data->dateof_disbursement))? date('Y-m-d',strtotime($master_data->dateof_disbursement)):"";
        $share_transfer_status  = (isset($master_data->share_transfer_status) && !empty($master_data->share_transfer_status))? $master_data->share_transfer_status:"";
        $share_transfer_count  = (isset($master_data->share_transfer_count) && !empty($master_data->share_transfer_count))? $master_data->share_transfer_count:"";
        $additional_share  = (isset($master_data->additional_share) && !empty($master_data->additional_share))? $master_data->additional_share:"";
        $incubate_status  = (isset($master_data->incubate_status) && !empty($master_data->incubate_status))? $master_data->incubate_status:"";
        
        
        
        $burnRate  = (isset($allRelatedData['burnRate']) && count($allRelatedData['burnRate'])>0)? $allRelatedData['burnRate'][0]->avg_burn_rate:"";
        $achievementDetails  = (isset($allRelatedData['achievement']) && count($allRelatedData['achievement'])>0)? $allRelatedData['achievement'][0]->sales_achived_till_date:"";
        $external_fundDetails  = (isset($allRelatedData['funding_status']) && count($allRelatedData['funding_status'])>0)? $allRelatedData['funding_status'][0]->remarks_fund_form_external:"";
        $seed_funding_from_iimcipDetails  = (isset($allRelatedData['funding_status']) && count($allRelatedData['funding_status'])>0)? $allRelatedData['funding_status'][0]->remarks_iimcip_investment:"";
        $projected  = (isset($allRelatedData['projected']) && count($allRelatedData['projected'])>0)? $allRelatedData['projected'][0]->revenue:"";
        //echo $burnRate->avg_burn_rate;
        //var_dump($burnRate);
        //print_r($master_data); die();
        if(!$flag) {
            $current_year = date('Y');
            $prev_year = $current_year - 1;
            $next_year = $current_year + 1;
          // display field/column names as first row
            echo "Sr No\t"
                . "Name of the incubatee company\t"
                . "name of the main founder\t"
                . "Age\t"
                . "Qualification\t"
                . "Month and year of incubation in the TBI\t"
                . "Website\t"
                . "Email Id\t"
                . "Mobile No\t"
                . "Industry Sector\t"
                . "Company Profile\t"
                . "Turnover ".$prev_year.'-'.date('y',strtotime($current_year.'-01-01'))."\t"               
                . "Projected turnover ".$current_year.'-'.date('y',strtotime($next_year.'-01-01'))."\t"
                . "YTD\t"
                . "Avg Burn Rate pm(in lakhs)\t"
                . "Type\t"
                . "Technology\t"
                . "Employee Generated previous Year\t"
                . "No of Employees\t"
                . "External Funds Received(in lakhs)\t"
                . "Seed Funding Received From IIMCIP(in lakhs)\t"
                . "Date of disbursement\t"
                . "IIMCIP Share holding percentage\t"
                . "Share Transfer Status\t"
                . "No of Shares transfered for incubation support\t"
                . "Additional shares\t"
                . "Mentor Name\t"                
                . "IIMCIP comments\n";
                //. "Status\n";
          //echo implode("\t", array_keys($row)) . "\n";
          $flag = true;
          //die();
        }
//      array_walk($row, __NAMESPACE__ . '\cleanData');
        echo $i."\t"
            .cleanData($row['company_name']). "\t"
            .cleanData($row['founder_name']). "\t"
            .cleanData($row['age']). "\t"
            .cleanData($row['qualification']). "\t"
            .cleanData($row['year_of_induction']). "\t"
            .cleanData($row['website']). "\t"
            .cleanData($row['email_id']). "\t"            
            .cleanData($row['mobile_no']). "\t"            
            .cleanData($row['sector']). "\t"            
            .cleanData($row['summary_start_up']). "\t"            
            .cleanData($row['total_revenue_2017']). "\t"           
            //.cleanData($row['total_projected_2018']). "\t"            
            .cleanData($projected). "\t"            
            .cleanData($achievementDetails). "\t"            
            .cleanData($burnRate). "\t"            
            .cleanData($type). "\t"            
            .cleanData($technology). "\t"            
            .cleanData($row['employee_generated_2017']). "\t"            
            .cleanData($row['employee_generated_2018']). "\t"            
            .cleanData($external_fundDetails). "\t"            
            .cleanData($seed_funding_from_iimcipDetails). "\t"            
            .cleanData($dateof_disbursement). "\t"            
            .cleanData($row['iimcip_share_holding_percentage']). "\t"            
            .cleanData($share_transfer_status). "\t"            
            .cleanData($share_transfer_count). "\t"            
            .cleanData($additional_share). "\t"            
            .cleanData($row['mentor_name']). "\t"            
            .cleanData($row['iimcip_comments']). "\n";
            //.cleandata($incubate_status)."\n";
      //echo implode("\t", array_values($row)) . "\n";
        $i++;
        //die();
    }
}else{
    echo 'No Records Found.';
}
  //exit;
  ?>