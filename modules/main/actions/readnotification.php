<?php

$id = lavnn('id', $_REQUEST, 0);
if ($id <> 0) {
  $runtime->db->sqlrun('main', 'MarkNotificationAsRead', array('id' => $id));
}

?>
