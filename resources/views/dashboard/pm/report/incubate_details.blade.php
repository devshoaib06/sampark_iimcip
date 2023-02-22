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
        //print_r($row); die();
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
                . "Turnover ".$current_year.'-'.date('y',strtotime($next_year.'-01-01'))."\t"                
                . "Projected ".$current_year.'-'.date('y',strtotime($next_year.'-01-01'))."\t"
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
            .cleanData($row['total_revenue_2018']). "\t"            
            .cleanData($row['total_projected_2018']). "\t"            
            .cleanData($row['ytd']). "\t"            
            .cleanData($row['avg_burn_pm']). "\t"            
            .cleanData($row['type']). "\t"            
            .cleanData($row['technology']). "\t"            
            .cleanData($row['employee_generated_2017']). "\t"            
            .cleanData($row['employee_generated_2018']). "\t"            
            .cleanData($row['external_fund']). "\t"            
            .cleanData($row['seed_funding_from_iimcip']). "\t"            
            .cleanData($row['disbursement_date']). "\t"            
            .cleanData($row['iimcip_share_holding_percentage']). "\t"            
            .cleanData($row['share_status_transfer']). "\t"            
            .cleanData($row['transfered_share_for_incubation_support']). "\t"            
            .cleanData($row['additional_shares']). "\t"            
            .cleanData($row['mentor_name']). "\t"            
            .cleanData($row['iimcip_comments']). "\n";
      //echo implode("\t", array_values($row)) . "\n";
        $i++;
    }
}else{
    echo 'No Records Found.';
}
  //exit;
  ?>