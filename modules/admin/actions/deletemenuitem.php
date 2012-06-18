<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) { 
  srun($module, 'DeleteMenuItem', $_REQUEST);
  srun($module, 'DeleteMenuItemAccess', $_REQUEST);  
}

go("?p=$module/menu");

?>
