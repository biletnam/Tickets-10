<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) { 
  $runtime->db->sqlrun($module, 'DeleteMenuItem', $_REQUEST);
  $runtime->db->sqlrun($module, 'DeleteMenuItemAccess', $_REQUEST);  
}

go("?p=$module/menu");

?>
