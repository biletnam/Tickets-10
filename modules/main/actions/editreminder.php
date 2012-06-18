<?php

$id = lavnn('id', $_REQUEST, '');

if ($id <> '') {
  $reminderInfo = $runtime->s2r($module, 'GetReminderInfo', $_REQUEST);
  if (count($reminderInfo) > 0) {
    print dot('editreminder', $reminderInfo);
  }
}

exit();

?>
