<?php

$op = lavnn('op', $_REQUEST, '');
$id = lavnn('id', $_REQUEST, 0);
if ($op == 'absence' && $id > 0) {
  s2r($module, 'ChangeUserAbsenceComment', $_REQUEST);
  print $_REQUEST['comment'];
} elseif ($op == 'overwork' && $id > 0) {
  s2r($module, 'ChangeUserOverworkComment', $_REQUEST);
  print $_REQUEST['comment'];
} else {
  print '';
}

1;

?>
