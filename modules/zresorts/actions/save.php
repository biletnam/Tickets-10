<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  $runtime->db->sqlrun('resorts', 'UpdateResort', $_REQUEST);
} else {
  $id = $runtime->sid('resorts', 'InsertResort', $_REQUEST);
}

go("?p=resorts/list");

?>
