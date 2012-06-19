<?php

$runtime->db->sqlrun($module, 'DeleteAccessTag', $_REQUEST);
go('?p='.$module.'/access');

?>
