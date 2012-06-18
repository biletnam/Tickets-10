<?php

$office = $_REQUEST['id'] || 0;

if ($office != 0) {
  srun($module, 'UpdateOffice', $_REQUEST);
  set_cookie('flash', 'Office data updated');
}
go("?p=$module/office&office=$office");

?>
