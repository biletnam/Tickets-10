<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id > 0) {
  srun('resorts', 'UpdateResort', $_REQUEST);
} else {
  $id = sid('resorts', 'InsertResort', $_REQUEST);
}

go("?p=resorts/list");

?>
