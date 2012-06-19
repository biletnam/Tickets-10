<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $_SESSION['flash'] = 'Table updated') if $runtime->db->sqlrun($module, 'DeleteEditableTable', $_REQUEST) > 0; 
}

go("?p=$module/editables");

?>
