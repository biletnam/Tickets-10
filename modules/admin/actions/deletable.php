<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  set_cookie('flash', 'Table updated') if srun($module, 'DeleteEditableTable', $_REQUEST) > 0; 
}

go("?p=$module/editables");

?>
