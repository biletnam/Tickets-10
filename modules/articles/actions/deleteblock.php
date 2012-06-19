<?php

$runtime->db->sqlrun($module, 'DeleteArticleBlock', $_REQUEST);
go("?p=$module/blocks");

?>
