<?php
$id = lavnn('id');
if ($id > 0) {
  $emailInfo = $runtime->s2r($module, 'GetEmailInfo', $_REQUEST); 
  if (count($emailInfo) > 0) {
    $domain = $emailInfo['domain'] || 0;
    set_cookie('flash', 'E-mail address deleted') if (0 < srun($module, 'DeleteEmailAddress', $_REQUEST));
    if ($domain > 0) {
      go("?p=$module/search&domain=$domain");
    }
  }
}
go("?p=$module/search");
?>
