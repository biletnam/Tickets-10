<?php

$id = lavnn('id', $_REQUEST, 0);

if ($id > 0) { #update
  sid($module, 'UpdateProduct', $_REQUEST);
} else { #insert
  $id = sid($module, 'InsertProduct', $_REQUEST);  
}

if ($id > 0) {
  go("?p=$module/view&id=$id");
} else {
  go("?p=$module/list")
}

?>
