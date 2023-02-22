<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mail</title>
</head>

<body  style="font-style: italic;">

<table width="700" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    
    <table width="100%" border="0" style="font-family:verdana; font-size:12px; line-height:17px;">
  <tr>
    <!-- <td style="text-align:center; background:#f3f3f3; padding:12px;"><img src="{{url('public/assets/layouts/layout/img/logo-big.png')}}" style=" width:200px;"></td> -->
  </tr>
  <tr>
    <td style="background:#f3f3f3; padding:5px;">
    
    
    <table width="100%" border="0">
  <tr>
    <td style=" background:#fff; padding:12px;">
     
   <p> Dear <b>{{$emailData['name']}}</b>,</p>

    
   You are Invited to Join <b>IIMCP</b>
   <br>
   <p>Your Invitation Code is : <b>{{$emailData['code']}}</b></p>

   <p>Link : <a href="http://iimcip.net/email-signup?email={{$emailData['email_id']}}">http://iimcip.net/email-signup?email={{$emailData['email_id']}}</a></p>
    
<p>Thanks & Regards</p>
<p><b>IIMCP</b></p>
 
    </td>
  </tr>
</table>
 
    </td>
  </tr>
  <tr>
   
  </tr>
</table>

    
    </td>
  </tr>
</table>



</body>
</html>
