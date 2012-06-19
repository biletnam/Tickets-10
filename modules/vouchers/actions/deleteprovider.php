<?php
$runtime->db->sqlrun($module, 'DeleteVoucherProvider', $_REQUEST);
go("?p=$module/home&tab=providers");

?>
