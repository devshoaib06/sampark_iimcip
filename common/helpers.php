<?php

 function sendEmailCommon($to,$subject,$message){
		require_once Config::get('app.base_url')."mailgun_latest/vendor/autoload.php";
		
		$mgClient = new Mailgun\Mailgun(Config::get('app.mg_client'),new \Http\Adapter\Guzzle6\Client());
		$domain = Config::get('app.mg_domain');		
		 $from=Config::get('app.from_email');
		
		
			
		//---------- strat for mail send to admin -----------//
					
		 $mail_temp=file_get_contents(Config::get('app.base_url').'public/mail_template/templ.php');
							
		 $mail_temp=str_replace("{{message}}",$message,$mail_temp); 
					
		$send=$mgClient->sendMessage($domain, array('from'=> $from,'to'=> $to,'subject' => $subject,'html' => $mail_temp));
		return true;	
			

	}
function cleanData($str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    $str = preg_replace('/[,]+/', ' ', $str);
    if(strstr($str, '"')) { $str = '"' . str_replace('"', '""', $str) . '"'; }
    return $str;
}
/*
function checkFinancialStart($year){
    $next_year = $year + 1;
    $start = $year.'-04-01';
    $end = $next_year.'-03-31';
    return $start;
}
function checkFinancialEnd($year){
    $next_year = $year + 1;
    $start = $year.'-04-01';
    $end = $next_year.'-03-31';
    return $end;
}*/
function getMasterData($id,$month,$year){
    $var = new NewReportIncubateMaster();
    $allData = $var->getAllData($id,$month,$year);
    return $allData;    
}
function checkReportStatus($id){
    $var = new NewReportIncubateMaster();
    $flag = $var->getReportStatus($id);
    return $flag;
}

function financial_year($year)
{
	if (date('m') <= 3) {//Upto June 2014-2015
    $financial_year = ($year-1) . '-' . $year;
} else {//After June 2015-2016
    $financial_year = $year . '-' . ($year + 1);
}

return $financial_year;
}

function checkFinancialStart($year)
{
	if (date('m') <= 3) {
		$financial_year = ($year-1);
	}
	else{
		$financial_year = $year;
	}
	
	$financial_year=$financial_year.'-04-01';
	
	return $financial_year;
}

function checkFinancialEnd($year)
{
	if (date('m') <= 3) {
		$financial_year = $year;
	}
	else{
		$financial_year = ($year+1);
	}
	
	$financial_year=$financial_year.'-03-31';
	
	return $financial_year;
}