<?php

$logo = "<div style='background-color:#003;padding-left:5px;padding-top:5px;padding-bottom:1px;'>
<img src='http://ritzkey.com/pics/ritzkey-logo5.png' style='height:68px;width:auto;'/>
</div><p>";

$sendgrid = new SendGrid('admin@abstracthealth.com','wonder');	
	$mail = new SendGrid\Mail();
$mail->
  addTo($email)->
  setFrom('admin@RitzKey.com')->
  setFromName('RitzKey')->
  setSubject($subject)->
  setText($body_text)->
  setHtml($logo.$body_html);
 $sendgrid->
web->
  send($mail);
  
?>