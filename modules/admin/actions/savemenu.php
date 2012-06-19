<?php

foreach $key (keys %_REQUEST) {
  if (substr($key, 0, 3) == 'id_') {
    $id = lavnn($key);
    substr($key, 0, 3, 'seq_no_');
    $value = lavnn($key);
    $runtime->db->sqlrun($module, 'SetMenuOrder', array('id' => $id, 'seq_no' => $value)) if ($value > 0 && $id > 0);
  }
}

go("?p=$module/menu");

?>
