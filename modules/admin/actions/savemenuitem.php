<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) { #update
  sid($module, 'UpdateMenuItem', $_REQUEST);
} else { #insert
  $id = sid($module, 'InsertMenuItem', $_REQUEST);  
}

if ($id > 0) {
  go("?p=$module/menuitem&id=$id");
} else {
  go("?p=$module/menu");
}

?>
