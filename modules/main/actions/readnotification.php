<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> 0) {
  srun('main', 'MarkNotificationAsRead', array('id' => $id));
}

?>
