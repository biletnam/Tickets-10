<?php

$office = $_REQUEST['id'] || 0;

if ($office != 0) {
  $runtime->db->sqlrun($module, 'UpdateOffice', $_REQUEST);
  $_SESSION['flash'] = 'Office data updated');
}
go("?p=$module/office&office=$office");

?>
