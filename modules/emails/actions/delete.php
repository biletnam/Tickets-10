<?php
$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $emailInfo = $runtime->s2r($module, 'GetEmailInfo', $_REQUEST); 
  if (count($emailInfo) > 0) {
    $domain = lavnn('domain', $emailInfo, 0);
    $_SESSION['flash'] = 'E-mail address deleted' if (0 < $runtime->db->sqlrun($module, 'DeleteEmailAddress', $_REQUEST));
    if ($domain > 0) {
      go("?p=$module/search&domain=$domain");
    }
  }
}
go("?p=$module/search");
?>
