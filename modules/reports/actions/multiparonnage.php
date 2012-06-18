<?php

$op = lavnn('op', $_REQUEST, '');
$ids = ref(\$_REQUEST['ids[]']) == 'REF' ? $_REQUEST['ids[]']} : ($_REQUEST['ids[]']);
formdebug($ids);  
die("TODO reports/multiparonnage; doing $op to " . count($ids) . "elements");

?>
